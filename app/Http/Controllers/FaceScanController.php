<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FaceScanController extends Controller
{
    public function index(Request $request)
    {
        $total = $request->query('total', 1); 
        return view('face-scan', compact('total'));
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

        $latestDetail = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('transaksi.id_user', Auth::id())
            ->orderBy('detail_transaksi.id_detail', 'desc')
            ->select('detail_transaksi.id_detail', 'detail_transaksi.faceID')
            ->first();

        // Backend duplicate validation across the current transaction
        if ($latestDetail && !empty($latestDetail->faceID)) {
            $previousFaces = explode(',', $latestDetail->faceID);
            foreach ($previousFaces as $prevFace) {
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

            if ($latestDetail) {
                $currentFaceID = $latestDetail->faceID;
                if (empty($currentFaceID)) {
                    $newFaceID = $filename;
                } else {
                    $newFaceID = $currentFaceID . ',' . $filename;
                }

                DB::table('detail_transaksi')
                    ->where('id_detail', $latestDetail->id_detail)
                    ->update(['faceID' => $newFaceID]);
            }

            return response()->json(['status' => 'success', 'message' => 'Image processed.', 'filename' => $filename]);
        }

        return response()->json(['status' => 'error', 'message' => 'No image uploaded.']);
    }
}
