<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketMail;

class FaceScanController extends Controller
{
    public function index(Request $request)
    {
        $total = $request->query('total', 1); 
        return view('face-scan', compact('total'));
    }

    public function checkStatus(Request $request)
    {
        $userId = Auth::id();

        // Cari tiket berhasil yang belum ada faceID (berasal dari waiting list yang baru dapat tiket)
        $unscannedDetail = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('transaksi.id_user', $userId)
            ->where('detail_transaksi.status_item', 'berhasil')
            ->where(function($q) {
                $q->whereNull('detail_transaksi.faceID')
                  ->orWhere('detail_transaksi.faceID', '');
            })
            ->first();

        if ($unscannedDetail) {
            // Belum scan wajah, arahkan ke face scan
            return response()->json(['done' => false]);
        }

        // Sudah scan wajah
        return response()->json(['done' => true]);
    }

    private function calculateEuclideanDistance($desc1, $desc2) {
        $sum = 0;
        for ($i = 0; $i < count($desc1); $i++) {
            $sum += pow($desc1[$i] - $desc2[$i], 2);
        }
        return sqrt($sum);
    }

    public function upload(Request $request)
    {
        $filename = '';
        $facesPath = public_path('faces');
        
        if (!file_exists($facesPath)) {
            mkdir($facesPath, 0755, true);
        }

        $descriptor = null;
        if ($request->has('descriptor')) {
            $descriptor = json_decode($request->input('descriptor'), true);
        }

        if (!$descriptor || count($descriptor) !== 128) {
             return response()->json(['status' => 'error', 'message' => 'Gagal mengidentifikasi wajah (Descriptor tidak valid). Pastikan wajah Anda terlihat jelas dalam foto.']);
        }

        $namaPemilik = $request->input('nama');
        $emailPemilik = $request->input('email');

        // Find the oldest unscanned ticket (now used for both identity and duplicate checking scope)
        $unscannedDetail = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('tiket', 'detail_transaksi.id_tiket', '=', 'tiket.id_tiket')
            ->join('event', 'tiket.id_event', '=', 'event.id_event')
            ->where('transaksi.id_user', Auth::id())
            ->where('detail_transaksi.status_item', 'berhasil')
            ->where(function($q) {
                $q->whereNull('detail_transaksi.faceID')
                  ->orWhere('detail_transaksi.faceID', '');
            })
            ->orderBy('detail_transaksi.id_detail', 'desc')
            ->select(
                'detail_transaksi.id_detail', 
                'detail_transaksi.kode_QR', 
                'event.nama_event',
                'event.id_event'
            )
            ->first();

        // Backend duplicate validation across faces for this user for the SAME event
        if ($unscannedDetail) {
            $previousDetails = DB::table('detail_transaksi')
                ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
                ->join('tiket', 'detail_transaksi.id_tiket', '=', 'tiket.id_tiket')
                ->where('transaksi.id_user', Auth::id())
                ->where('tiket.id_event', $unscannedDetail->id_event)
                ->whereNotIn('detail_transaksi.status_item', ['cancel', 'batal'])
                ->whereNotNull('detail_transaksi.faceID')
                ->where('detail_transaksi.faceID', '!=', '')
                ->select('detail_transaksi.faceID')
                ->get();

            foreach ($previousDetails as $detail) {
                $prevFaceFiles = explode(',', $detail->faceID);
                foreach ($prevFaceFiles as $prevFace) {
                    $prevFaceName = trim($prevFace);
                    if (empty($prevFaceName)) continue;

                    $jsonFilename = preg_replace('/\.[^.]+$/', '.json', $prevFaceName);
                    $jsonPath = $facesPath . '/' . $jsonFilename;

                    if (file_exists($jsonPath)) {
                        $prevDescriptor = json_decode(file_get_contents($jsonPath), true);
                        if (is_array($prevDescriptor) && count($prevDescriptor) === 128) {
                            $distance = $this->calculateEuclideanDistance($descriptor, $prevDescriptor);
                            if ($distance < 0.5) {
                                return response()->json([
                                    'status' => 'error', 
                                    'message' => 'Wajah ini telah digunakan pada tiket sebelumnya. Harap gunakan wajah yang berbeda.'
                                ]);
                            }
                        }
                    }
                }
            }
        }

        if ($request->has('image')) {
            $imageParts = explode(";base64,", $request->image);
            if (count($imageParts) == 2) {
                $imageBase64 = base64_decode($imageParts[1]);
                $filename = 'face_' . time() . '_' . uniqid() . '.png';
                file_put_contents($facesPath . '/' . $filename, $imageBase64);
            }
        } else if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'face_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($facesPath, $filename);
        }

        if ($filename) {
            // Save descriptor tracking info alongside the image
            $jsonFilename = preg_replace('/\.[^.]+$/', '.json', $filename);
            file_put_contents($facesPath . '/' . $jsonFilename, json_encode($descriptor));

            if ($unscannedDetail) {
                DB::table('detail_transaksi')
                    ->where('id_detail', $unscannedDetail->id_detail)
                    ->update([
                        'faceID' => $filename,
                        'nama_pemilik' => $namaPemilik,
                        'email_pemilik' => $emailPemilik,
                    ]);

                // Send email explicitly to the user's provided ticket email
                if ($emailPemilik) {
                    try {
                        Mail::to($emailPemilik)->send(new TicketMail($unscannedDetail->kode_QR, $unscannedDetail->nama_event));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Failed to send individual QR Code Email: ' . $e->getMessage());
                    }
                }
            }

            return response()->json(['status' => 'success', 'message' => 'Image processed.', 'filename' => $filename]);
        }

        return response()->json(['status' => 'error', 'message' => 'No image uploaded.']);
    }
}
