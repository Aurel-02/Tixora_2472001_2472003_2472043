<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FaceScanController extends Controller
{
    public function index(Request $request)
    {
        $total = $request->query('total', 1); 
        return view('face-scan', compact('total'));
    }

    public function upload(Request $request)
    {
        $filename = '';
        $facesPath = public_path('faces');
        
        if (!file_exists($facesPath)) {
            mkdir($facesPath, 0755, true);
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
            $latestDetail = \Illuminate\Support\Facades\DB::table('detail_transaksi')
                ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
                ->where('transaksi.id_user', \Illuminate\Support\Facades\Auth::id())
                ->orderBy('detail_transaksi.id_detail', 'desc')
                ->select('detail_transaksi.id_detail', 'detail_transaksi.faceID')
                ->first();

            if ($latestDetail) {
                $currentFaceID = $latestDetail->faceID;
                if (empty($currentFaceID)) {
                    $newFaceID = $filename;
                } else {
                    $newFaceID = $currentFaceID . ',' . $filename;
                }

                \Illuminate\Support\Facades\DB::table('detail_transaksi')
                    ->where('id_detail', $latestDetail->id_detail)
                    ->update(['faceID' => $newFaceID]);
            }

            return response()->json(['status' => 'success', 'message' => 'Image processed.', 'filename' => $filename]);
        }

        return response()->json(['status' => 'error', 'message' => 'No image uploaded.']);
    }
}
