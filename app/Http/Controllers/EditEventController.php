<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EditEventController extends Controller
{
    /**
     * Update the event description.
     */
    public function updateDescription(Request $request, $id)
    {
        $request->validate([
            'deskripsi' => 'required|string',
        ]);

        $event = Event::findOrFail($id);
        
        $event->deskripsi = $request->deskripsi;
        $event->save();

        return redirect()->back()->with('success', 'Deskripsi event berhasil diperbarui.');
    }

    /**
     * Add more quota to a specific ticket category.
     */
    public function addQuota(Request $request, $id)
    {
        $request->validate([
            'id_tiket' => 'required|exists:tiket,id_tiket',
            'jumlah_tambah' => 'required|integer|min:1',
        ]);

        $id_tiket = $request->id_tiket;
        $jumlah_tambah = $request->jumlah_tambah;

        // Tambah Kuota Dahulu
        DB::table('tiket')
            ->where('id_tiket', $id_tiket)
            ->where('id_event', $id)
            ->increment('kuota', $jumlah_tambah);

        // Ambil nama event untuk keperluan notifikasi
        $eventRow = DB::table('event')->where('id_event', $id)->first();
        $nama_event = $eventRow ? $eventRow->nama_event : "Event #$id";

        // Proses Antrean Waiting List (jika ada) sesuai jumlah tiket yang ditambahkan
        for ($i = 0; $i < $jumlah_tambah; $i++) {
            $antrian = DB::table('antrian')
                ->where('id_tiket', $id_tiket)
                ->orderBy('waktu_antri', 'asc')
                ->first();

            if ($antrian) {
                // Notifikasi sukses mendapatkan tiket dari antrean
                DB::table('notifikasi')->insert([
                    'id_user'    => $antrian->id_user,
                    'pesan'      => "Selamat! Tiket event {$nama_event} berhasil didapatkan secara otomatis setelah penambahan kuota.",
                    'link_url'   => null,
                    'is_read'    => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Notifikasi peringatan memindai wajah
                $faceScanUrl = route('face-scan.index', ['total' => 1]);
                DB::table('notifikasi')->insert([
                    'id_user'    => $antrian->id_user,
                    'pesan'      => "Tiket event {$nama_event} otomatis Anda belum melewati verifikasi wajah. Klik untuk melakukan scan wajah sekarang.",
                    'link_url'   => $faceScanUrl,
                    'is_read'    => 0,
                    'created_at' => now()->addSecond(),
                    'updated_at' => now()->addSecond(),
                ]);

                DB::statement("CALL sp_ProsesAntreanDetail(?, ?)", [$id, $id_tiket]);
            } else {
                break; // Jika antrean sudah kosong, berhentikan loop
            }
        }

        return redirect()->back()->with('success', "Kuota tiket berhasil ditambahkan dan " . ($i > 0 ? "berhasil memberikan $i tiket ke peserta Waiting List." : "saat ini tidak ada antrean."));
    }
}
