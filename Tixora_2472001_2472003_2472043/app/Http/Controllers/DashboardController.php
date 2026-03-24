<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $frontendData = $this->prepareFrontendData();
        return view('dashboard', compact('frontendData'));
    }

    public function organizer()
    {
        $frontendData = $this->prepareFrontendData();
        return view('organizerdashboard', compact('frontendData'));
    }

    private function prepareFrontendData(): array
    {
        $now = Carbon::now();
        $threeMonthsLater = $now->copy()->addMonths(3);

        $kategoriMap = [
            1 => 'indonesia',
            2 => 'western',
            3 => 'kpop'
        ];

        $frontendData = [
            'indonesia' => ['artists' => [], 'events' => []],
            'western' => ['artists' => [], 'events' => []],
            'kpop' => ['artists' => [], 'events' => []]
        ];

        $allEvents = Event::all();

        foreach ($allEvents as $event) {
            $catId = $event->id_kategori;

            if (array_key_exists($catId, $kategoriMap)) {
                $slug = $kategoriMap[$catId];

                $tanggal = $event->tanggal_pelaksanaan ? Carbon::parse($event->tanggal_pelaksanaan) : null;
                $isUpcoming = $tanggal && $tanggal->isAfter($threeMonthsLater);

                if ($isUpcoming) {
                    $frontendData[$slug]['events'][] = [
                        'month' => strtoupper($tanggal->format('M')),
                        'day' => $tanggal->format('d'),
                        'name' => $event->nama_event,
                        'location' => $event->lokasi_event ?? 'Lokasi belum diset'
                    ];
                } else {
                    $frontendData[$slug]['artists'][] = [
                        'name' => $event->nama_event,
                        'desc' => $tanggal ? $tanggal->format('d M Y') : 'TBD'
                    ];
                }
            }
        }

        return $frontendData;
    }
}
