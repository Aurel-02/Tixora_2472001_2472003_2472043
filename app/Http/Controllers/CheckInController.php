<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckInController extends Controller
{
    /**
     * Tampilkan halaman check-in.
     */
    public function index()
    {
        $organizerId = Auth::id();

        $ownedEventIds = DB::table('event')
            ->where('id_user', $organizerId)
            ->pluck('id_event')
            ->toArray();

        $approvedEventIds = DB::table('permohonan_events')
            ->where('id_user', $organizerId)
            ->where('status', 'approved')
            ->pluck('id_event')
            ->toArray();

        $myEventIds = array_unique(array_merge($ownedEventIds, $approvedEventIds));

        $events = DB::table('event')
            ->whereIn('id_event', $myEventIds)
            ->orderBy('tanggal_pelaksanaan', 'desc')
            ->get();

        return view('checkin', compact('events'));
    }

    private function isOrganizerOfEvent($eventId, $organizerId): bool
    {

        $isOwner = DB::table('event')
            ->where('id_event', $eventId)
            ->where('id_user', $organizerId)
            ->exists();

        if ($isOwner) return true;

        $isApproved = DB::table('permohonan_events')
            ->where('id_event', $eventId)
            ->where('id_user', $organizerId)
            ->where('status', 'approved')
            ->exists();

        return $isApproved;
    }

    public function scanQr(Request $request)
    {
        $kodeQr = trim($request->input('kode_qr', ''));

        if (empty($kodeQr)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Kode QR tidak boleh kosong.',
            ]);
        }

        $detail = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('tiket', 'detail_transaksi.id_tiket', '=', 'tiket.id_tiket')
            ->join('event', 'tiket.id_event', '=', 'event.id_event')
            ->join('user', 'transaksi.id_user', '=', 'user.id_user')
            ->where('detail_transaksi.kode_QR', $kodeQr)
            ->where('detail_transaksi.status_item', 'berhasil')
            ->select(
                'detail_transaksi.id_detail',
                'detail_transaksi.jumlah_beli',
                'detail_transaksi.status_item',
                'detail_transaksi.kode_QR',
                'detail_transaksi.faceID',
                'detail_transaksi.nama_pemilik',
                'detail_transaksi.email_pemilik',
                'tiket.jenis_tiket',
                'tiket.harga',
                'event.id_event',
                'event.nama_event',
                'event.tanggal_pelaksanaan',
                'event.waktu_pelaksanaan',
                'event.lokasi_event',
                'event.poster',
                'user.nama_lengkap',
                'user.email',
                'transaksi.tanggal_transaksi'
            )
            ->first();

        if (!$detail) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'QR tidak ditemukan atau tiket tidak valid.',
            ]);
        }

        $organizerId = Auth::id();
        if (!$this->isOrganizerOfEvent($detail->id_event, $organizerId)) {
            return response()->json([
                'status'  => 'unauthorized',
                'message' => 'Anda bukan organizer dari event "' . $detail->nama_event . '". Anda tidak dapat melakukan check-in untuk tiket event ini.',
                'event_name' => $detail->nama_event,
            ]);
        }

        $selectedEventId = $request->input('id_event');
        if ($selectedEventId && $detail->id_event != $selectedEventId) {
            return response()->json([
                'status'  => 'unauthorized',
                'message' => 'Tiket ini adalah untuk "' . $detail->nama_event . '", bukan event yang sedang Anda pilih.',
                'event_name' => $detail->nama_event,
            ]);
        }

        $alreadyCheckedIn = DB::table('detail_transaksi')
            ->where('id_detail', $detail->id_detail)
            ->value('checked_in');

        return response()->json([
            'status'  => 'found',
            'message' => 'Tiket ditemukan!',
            'ticket'  => [
                'id_detail'           => $detail->id_detail,
                'nama_penonton'       => $detail->nama_pemilik ?: $detail->nama_lengkap,
                'email'               => $detail->email_pemilik ?: $detail->email,
                'nama_event'          => $detail->nama_event,
                'tanggal_pelaksanaan' => $detail->tanggal_pelaksanaan,
                'waktu_pelaksanaan'   => $detail->waktu_pelaksanaan,
                'lokasi_event'        => $detail->lokasi_event,
                'poster'              => $detail->poster ? asset($detail->poster) : null,
                'jenis_tiket'         => $detail->jenis_tiket,
                'harga'               => 'Rp ' . number_format($detail->harga, 0, ',', '.'),
                'jumlah_beli'         => $detail->jumlah_beli,
                'kode_qr'             => $detail->kode_QR,
                'status_item'         => $detail->status_item,
                'tanggal_transaksi'   => $detail->tanggal_transaksi,
                'already_checked_in'  => (bool) $alreadyCheckedIn,
            ],
        ]);
    }

    public function confirmCheckin(Request $request)
    {
        $idDetail = $request->input('id_detail');

        if (!$idDetail) {
            return response()->json(['status' => 'error', 'message' => 'ID detail tidak valid.']);
        }

        $detail = DB::table('detail_transaksi')->where('id_detail', $idDetail)->first();

        if (!$detail) {
            return response()->json(['status' => 'error', 'message' => 'Tiket tidak ditemukan.']);
        }

        if ($detail->checked_in) {
            return response()->json(['status' => 'already', 'message' => 'Tiket ini sudah digunakan untuk check-in.']);
        }

        DB::table('detail_transaksi')
            ->where('id_detail', $idDetail)
            ->update(['checked_in' => 1]);

        return response()->json(['status' => 'success', 'message' => 'Check-in berhasil!']);
    }

    public function syncFace(Request $request)
    {
        $descriptorInput = $request->input('descriptor');

        if (!$descriptorInput) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Descriptor wajah tidak diterima.',
            ]);
        }

        $incomingDescriptor = is_string($descriptorInput)
            ? json_decode($descriptorInput, true)
            : $descriptorInput;

        if (!is_array($incomingDescriptor) || count($incomingDescriptor) !== 128) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Format descriptor wajah tidak valid.',
            ]);
        }

        $facesPath   = public_path('faces');
        $organizerId = Auth::id();

        $selectedEventId = $request->input('id_event');

        if ($selectedEventId) {
            $myEventIds = [$selectedEventId];
        } else {
            $ownedEventIds = DB::table('event')
                ->where('id_user', $organizerId)
                ->pluck('id_event')
                ->toArray();

            $approvedEventIds = DB::table('permohonan_events')
                ->where('id_user', $organizerId)
                ->where('status', 'approved')
                ->pluck('id_event')
                ->toArray();

            $myEventIds = array_unique(array_merge($ownedEventIds, $approvedEventIds));
        }

        if (empty($myEventIds)) {
            return response()->json([
                'status'  => 'not_match',
                'message' => 'Anda belum memiliki event yang aktif.',
            ]);
        }

        $allDetails = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('tiket', 'detail_transaksi.id_tiket', '=', 'tiket.id_tiket')
            ->join('event', 'tiket.id_event', '=', 'event.id_event')
            ->join('user', 'transaksi.id_user', '=', 'user.id_user')
            ->whereNotNull('detail_transaksi.faceID')
            ->where('detail_transaksi.faceID', '!=', '')
            ->where('detail_transaksi.status_item', 'berhasil')
            ->whereIn('event.id_event', $myEventIds)
            ->select(
                'detail_transaksi.id_detail',
                'detail_transaksi.faceID',
                'detail_transaksi.kode_QR',
                'detail_transaksi.jumlah_beli',
                'detail_transaksi.nama_pemilik',
                'tiket.jenis_tiket',
                'event.nama_event',
                'event.tanggal_pelaksanaan',
                'event.lokasi_event',
                'event.poster',
                'user.nama_lengkap'
            )
            ->get();

        $bestMatch = null;
        $bestDist  = PHP_FLOAT_MAX;
        $threshold = 0.58;

        foreach ($allDetails as $detail) {
            $faceFiles = explode(',', $detail->faceID);

            foreach ($faceFiles as $faceFile) {
                $faceFile = trim($faceFile);
                if (empty($faceFile)) continue;

                $jsonFile = preg_replace('/\.[^.]+$/', '.json', $faceFile);
                $jsonPath = $facesPath . '/' . $jsonFile;

                if (!file_exists($jsonPath)) continue;

                $storedDescriptor = json_decode(file_get_contents($jsonPath), true);

                if (!is_array($storedDescriptor) || count($storedDescriptor) !== 128) continue;

                $distance = $this->euclideanDistance($incomingDescriptor, $storedDescriptor);

                if ($distance < $bestDist) {
                    $bestDist  = $distance;
                    $bestMatch = [
                        'detail'   => $detail,
                        'faceFile' => $faceFile,
                        'distance' => $distance,
                    ];
                }
            }
        }

        if ($bestMatch && $bestDist <= $threshold) {
            $det     = $bestMatch['detail'];
            $faceUrl = asset('faces/' . $bestMatch['faceFile']);

            return response()->json([
                'status'   => 'match',
                'message'  => 'Wajah Cocok!',
                'distance' => round($bestDist, 4),
                'face_url' => $faceUrl,
                'ticket'   => [
                    'nama_penonton'       => $det->nama_pemilik ?: $det->nama_lengkap,
                    'nama_event'          => $det->nama_event,
                    'tanggal_pelaksanaan' => $det->tanggal_pelaksanaan,
                    'lokasi_event'        => $det->lokasi_event,
                    'poster'              => $det->poster ? asset($det->poster) : null,
                    'jenis_tiket'         => $det->jenis_tiket,
                    'jumlah_beli'         => $det->jumlah_beli,
                    'kode_qr'             => $det->kode_QR,
                ],
            ]);
        }

        return response()->json([
            'status'  => 'not_match',
            'message' => 'Wajah tidak ditemukan dalam sistem.',
        ]);
    }

    private function euclideanDistance(array $a, array $b): float
    {
        $sum = 0.0;
        $len = min(count($a), count($b));
        for ($i = 0; $i < $len; $i++) {
            $sum += ($a[$i] - $b[$i]) ** 2;
        }
        return sqrt($sum);
    }

    public function report(Request $request)
    {
        $organizerId = Auth::id();

        $ownedEventIds = DB::table('event')
            ->where('id_user', $organizerId)
            ->pluck('id_event')
            ->toArray();

        $approvedEventIds = DB::table('permohonan_events')
            ->where('id_user', $organizerId)
            ->where('status', 'approved')
            ->pluck('id_event')
            ->toArray();

        $myEventIds = array_unique(array_merge($ownedEventIds, $approvedEventIds));

        $events = DB::table('event')
            ->whereIn('id_event', $myEventIds)
            ->orderBy('tanggal_pelaksanaan', 'desc')
            ->get();

        $selectedEventId = $request->input('event_id', $events->first()->id_event ?? null);
        
        $reportData = collect();
        $totalCheckIn = 0;
        $totalNotCheckIn = 0;
        $totalTickets = 0;

        if ($selectedEventId && in_array($selectedEventId, $myEventIds)) {
            $tickets = DB::table('detail_transaksi')
                ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
                ->join('tiket', 'detail_transaksi.id_tiket', '=', 'tiket.id_tiket')
                ->join('user', 'transaksi.id_user', '=', 'user.id_user')
                ->where('tiket.id_event', $selectedEventId)
                ->where('detail_transaksi.status_item', 'berhasil')
                ->select(
                    'detail_transaksi.id_detail',
                    'detail_transaksi.kode_QR',
                    'detail_transaksi.checked_in',
                    'detail_transaksi.jumlah_beli',
                    'detail_transaksi.nama_pemilik',
                    'detail_transaksi.email_pemilik',
                    'tiket.jenis_tiket',
                    'user.nama_lengkap',
                    'user.email'
                )
                ->get();
            
            $reportData = $tickets;
            $totalTickets = $tickets->sum('jumlah_beli');
            $totalCheckIn = $tickets->where('checked_in', 1)->sum('jumlah_beli');
            $totalNotCheckIn = $tickets->where('checked_in', 0)->sum('jumlah_beli');
        }

        return view('checkin-report', compact('events', 'selectedEventId', 'reportData', 'totalTickets', 'totalCheckIn', 'totalNotCheckIn'));
    }
}
