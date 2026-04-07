<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tixora - Statistik Penjualan</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --jacarta: #3A345B;
            --queen-pink: #F3C8DD;
            --middle-purple: #D183A9;
            --old-lavender: #71557A;
            --brown-chocolate: #4B1535;
            --sidebar-width-collapsed: 70px;
            --sidebar-width-expanded: 240px;
            --topbar-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--jacarta), var(--brown-chocolate));
            color: var(--queen-pink);
            min-height: 100vh;
            overflow-x: hidden;
            background-attachment: fixed;
        }

        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width-collapsed);
            right: 0;
            height: var(--topbar-height);
            background: rgba(58, 52, 91, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(243, 200, 221, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 40px;
            z-index: 1100;
        }

        .topbar .logo {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 2px;
            color: var(--queen-pink);
            text-shadow: 0 0 10px rgba(243, 200, 221, 0.5);
            text-transform: uppercase;
        }

        .topbar .profile {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: var(--middle-purple);
            color: var(--jacarta);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(209, 131, 169, 0.4);
            transition: transform 0.3s;
            text-transform: uppercase;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width-collapsed);
            height: 100vh;
            background: rgba(113, 85, 122, 0.4);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-right: 1px solid rgba(243, 200, 221, 0.15);
            z-index: 1000;
            transition: width 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            padding-top: var(--topbar-height);
        }

        .sidebar:hover {
            width: var(--sidebar-width-expanded);
            box-shadow: 5px 0 20px rgba(0, 0, 0, 0.4);
        }

        .sidebar-menu {
            list-style: none;
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 15px 22px;
            color: var(--queen-pink);
            text-decoration: none;
            transition: background 0.2s, border-left 0.2s;
            border-left: 3px solid transparent;
            cursor: pointer;
            white-space: nowrap;
        }

        .sidebar-item:hover, .sidebar-item.active {
            background: rgba(209, 131, 169, 0.2);
            border-left: 3px solid var(--middle-purple);
        }

        .sidebar-icon {
            font-size: 1.4rem;
            min-width: 25px;
            margin-right: 20px;
        }

        .sidebar-text {
            font-size: 1rem;
            font-weight: 500;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar:hover .sidebar-text {
            opacity: 1;
        }

        .main-wrapper {
            margin-left: var(--sidebar-width-collapsed);
            padding-top: calc(var(--topbar-height) + 40px);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-bottom: 60px;
        }

        .content-container {
            width: 100%;
            max-width: 1500px;
            margin: 0 auto;
            padding: 0 40px;
        }

        .page-header {
            margin-bottom: 40px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
            text-shadow: 0 0 15px rgba(243, 200, 221, 0.4);
        }

        .page-subtitle {
            font-size: 1.1rem;
            color: var(--queen-pink);
            opacity: 0.8;
        }

        .overall-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-bottom: 50px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(243, 200, 221, 0.1);
            border-radius: 24px;
            padding: 30px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(243, 200, 221, 0.05));
            pointer-events: none;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(243, 200, 221, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: rgba(209, 131, 169, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--middle-purple);
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.7;
        }

        .stat-value {
            font-size: 2.2rem;
            font-weight: 700;
            color: #fff;
        }

        .section-card {
            background: rgba(113, 85, 122, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(243, 200, 221, 0.15);
            border-radius: 18px;
            padding: 30px;
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title i {
            color: var(--middle-purple);
        }

        .event-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .event-select-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(243, 200, 221, 0.1);
            border-radius: 20px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            color: inherit;
            position: relative;
        }

        .event-select-card:hover, .event-select-card.active {
            background: rgba(209, 131, 169, 0.15);
            border-color: var(--middle-purple);
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .event-mini-poster {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            object-fit: cover;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(243, 200, 221, 0.1);
        }

        .event-mini-info {
            flex: 1;
        }

        .event-mini-info h4 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 4px;
        }

        .event-mini-info p {
            font-size: 0.85rem;
            opacity: 0.7;
            color: var(--queen-pink);
        }

        .event-sold-badge {
            background: rgba(132, 216, 165, 0.15);
            color: #84d8a5;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 8px;
        }

        .detail-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .stats-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid rgba(243, 200, 221, 0.1);
        }

        .summary-item {
            text-align: center;
            padding: 15px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            border: 1px solid rgba(243, 200, 221, 0.05);
        }

        .summary-label {
            display: block;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
            opacity: 0.7;
        }

        .summary-value {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
        }

        .chart-container {
            width: 100%;
            position: relative;
            margin-bottom: 30px;
        }

        .stats-table {
            width: 100%;
            border-collapse: collapse;
            color: var(--queen-pink);
        }

        .stats-table th {
            text-align: left;
            padding: 15px;
            background: rgba(209, 131, 169, 0.1);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
        }

        .stats-table td {
            padding: 15px;
            border-bottom: 1px solid rgba(243, 200, 221, 0.05);
        }

        .badge {
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        @media (max-width: 1100px) {
            .detail-layout { grid-template-columns: 1fr; }
            .overall-stats-grid { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 768px) {
            .overall-stats-grid { grid-template-columns: 1fr; }
            .stats-summary { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <header class="topbar">
        <div class="logo">TIXORA</div>
        <a href="{{ route('profile.edit') }}" class="profile" title="My Profile" style="text-decoration:none;">
            @if(auth()->check() && auth()->user()->photo_profile)
                <img src="{{ asset(auth()->user()->photo_profile) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            @else
                {{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'U', 0, 1)) }}
            @endif
        </a>
    </header>

    <aside class="sidebar">
        <div class="sidebar-content" style="display: flex; flex-direction: column; height: calc(100vh - var(--topbar-height));">
            <ul class="sidebar-menu" style="flex-grow: 1; padding-top: 20px;">
                <li>
                    <a href="{{ url('/organizerdashboard') }}" class="sidebar-item">
                        <i class="ph ph-house sidebar-icon"></i>
                        <span class="sidebar-text">Home</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('organizer.statistik') }}" class="sidebar-item active">
                        <i class="ph ph-chart-bar sidebar-icon"></i>
                        <span class="sidebar-text">Statistik Penjualan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('organizer.events.create') }}" class="sidebar-item">
                        <i class="ph ph-plus-circle sidebar-icon"></i>
                        <span class="sidebar-text">Tambah Event</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('organizer.checkin') }}" class="sidebar-item">
                        <i class="ph ph-qr-code sidebar-icon"></i>
                        <span class="sidebar-text">Check In</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('organizer.notifications') }}" class="sidebar-item">
                        <i class="ph ph-bell sidebar-icon"></i>
                        <span class="sidebar-text">Notifications</span>
                    </a>
                </li>
            </ul>

            <div style="padding: 10px 0;">
                <form action="{{ route('logout') }}" method="POST" style="margin: 0; width: 100%;">
                    @csrf
                    <button type="submit" class="sidebar-item" style="background: transparent; border: none; color: var(--queen-pink); width: 100%; text-align: left; padding: 15px 22px; cursor: pointer;">
                        <i class="ph ph-sign-out sidebar-icon"></i>
                        <span class="sidebar-text">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <main class="main-wrapper">
        <div class="content-container">
            <div class="page-header">
                <h1 class="page-title">Performa Tiket</h1>

            </div>

            <div class="section-card">
                <h2 class="section-title"><i class="ph ph-presentation-chart"></i> Performa Penjualan Per Event</h2>
                <div class="chart-container" style="max-width: 100%; height: 450px;">
                    <canvas id="overallStackedChart"></canvas>
                </div>
            </div>

            <div class="overall-stats-grid">
                <div class="stat-card" style="grid-column: span 2; display: flex; flex-direction: row; align-items: center; justify-content: space-between; padding: 40px;">
                    <div style="flex: 1;">
                        <div class="stat-icon" style="background: rgba(209, 131, 169, 0.2); width: 60px; height: 60px; font-size: 2rem;"><i class="ph ph-ticket"></i></div>
                        <p class="stat-label" style="font-size: 1rem; margin-top: 15px; font-weight: 500;">Total Penjualan Seluruh Event</p>
                        <div style="display: flex; align-items: baseline; gap: 15px; margin-top: 5px;">
                            <span class="stat-value" style="font-size: 4.5rem; line-height: 1; letter-spacing: -2px;">{{ number_format($overallSold) }}</span>
                            <span style="font-size: 1.2rem; opacity: 0.6; font-weight: 500;">TIKET TERJUAL</span>
                        </div>
                        <p style="opacity: 0.5; margin-top: 15px; font-size: 0.95rem; display: flex; align-items: center; gap: 8px;">
                            <i class="ph ph-info"></i> Mencakup {{ number_format($overallTotal) }} total kuota kolektif
                        </p>
                    </div>
                    <div style="text-align: right; display: flex; flex-direction: column; gap: 10px;">
                         <div style="background: rgba(209, 131, 169, 0.1); border: 1px solid rgba(209, 131, 169, 0.3); padding: 20px 30px; border-radius: 20px; text-align: center; backdrop-filter: blur(5px);">
                            <span style="display: block; font-size: 2.2rem; font-weight: 800; color: var(--middle-purple); line-height: 1.2;">{{ $overallTotal > 0 ? round(($overallSold / $overallTotal) * 100, 1) : 0 }}%</span>
                            <span style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1.5px; opacity: 0.9; font-weight: 600; color: var(--queen-pink);">UTILITY</span>
                        </div>
                    </div>
                </div>
                <div class="stat-card" style="justify-content: center; align-items: center; text-align: center; border-left: 4px solid var(--middle-purple);">
                    <div class="stat-icon" style="background: rgba(243, 200, 221, 0.2); color: var(--queen-pink); width: 70px; height: 70px; font-size: 2.5rem; border-radius: 20px;"><i class="ph ph-calendar-check"></i></div>
                    <span class="stat-label" style="margin-top: 15px; font-weight: 600; font-size: 1.1rem;">Total Events</span>
                    <span class="stat-value" style="font-size: 4rem; line-height: 1;">{{ count($events) }}</span>
                    <p style="opacity: 0.6; margin-top: 10px; font-size: 0.9rem;">Aktif & Mendatang</p>
                </div>
            </div>


            <div class="section-card">
                <h2 class="section-title"><i class="ph ph-calendar-check"></i> Pilih Event</h2>
                <div class="event-grid">
                    @foreach($events as $event)
                    @php
                        $thisEventData = collect($eventChartData)->firstWhere('name', $event->nama_event);
                    @endphp
                    <a href="{{ route('organizer.statistik', ['id' => $event->id_event]) }}" 
                       class="event-select-card {{ (isset($selectedEvent) && $selectedEvent->id_event == $event->id_event) ? 'active' : '' }}">
                        @if($event->poster)
                            <img src="{{ asset($event->poster) }}" alt="{{ $event->nama_event }}" class="event-mini-poster">
                        @else
                            <div class="event-mini-poster" style="display: flex; align-items:center; justify-content:center; background: rgba(255,255,255,0.05);">
                                <i class="ph ph-image" style="opacity: 0.3;"></i>
                            </div>
                        @endif
                        <div class="event-mini-info">
                            <h4>{{ $event->nama_event }}</h4>
                            <p><i class="ph ph-calendar"></i> {{ $event->tanggal_pelaksanaan ? \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->format('d M Y') : 'TBD' }}</p>
                            <div class="event-sold-badge">
                                <i class="ph ph-shopping-bag"></i> <strong>{{ $thisEventData['sold'] ?? 0 }}</strong> Terjual
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            @if($selectedEvent)
            <div class="section-card">
                <h3 class="section-title"><i class="ph ph-chart-line"></i> Detail Performa: {{ $selectedEvent->nama_event }}</h3>
                
                <div class="detail-layout">
                    <div>
                        <div class="chart-container">
                            <canvas id="ticketChart"></canvas>
                        </div>
                        <div class="stats-summary">
                            <div class="summary-item">
                                <span class="summary-label">Kuota</span>
                                <span class="summary-value">{{ number_format($selectedTotal) }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Terjual</span>
                                <span class="summary-value" style="color: #84d8a5;">{{ number_format($selectedSold) }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Sisa</span>
                                <span class="summary-value" style="color: var(--middle-purple);">{{ number_format($selectedAvailable) }}</span>
                            </div>
                        </div>
                    </div>

                    <div style="overflow-x: auto;">
                        <table class="stats-table">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>Terjual</th>
                                    <th>Sisa</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ticketStats as $stat)
                                <tr>
                                    <td style="font-weight: 600; color: #fff;">{{ $stat->jenis_tiket }}</td>
                                    <td style="color: #84d8a5;">{{ number_format($stat->terjual) }}</td>
                                    <td style="color: var(--middle-purple);">{{ number_format($stat->sisa) }}</td>
                                    <td>
                                        @if($stat->sisa <= 0)
                                            <span class="badge" style="background: rgba(239, 83, 80, 0.2); color: #ef5350;">SOLD OUT</span>
                                        @elseif($stat->sisa < 10)
                                            <span class="badge" style="background: rgba(255, 167, 38, 0.2); color: #ffa726;">LOW STOCK</span>
                                        @else
                                            <span class="badge" style="background: rgba(132, 216, 165, 0.2); color: #84d8a5;">ACTIVE</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div style="text-align: center; padding: 40px; background: rgba(255,255,255,0.02); border-radius: 20px; border: 1px dashed rgba(243, 200, 221, 0.1);">
                <i class="ph ph-cursor-click" style="font-size: 3rem; opacity: 0.2; display: block; margin-bottom: 15px;"></i>
                <p>Pilih salah satu event di atas untuk melihat statistik detail.</p>
            </div>
            @endif
        </div>
    </main>

    <script>
        const overallData = @json($eventChartData);
        const overallCtx = document.getElementById('overallStackedChart').getContext('2d');
        
        const allCategories = [];
        overallData.forEach(event => {
            event.categories.forEach(cat => {
                if (!allCategories.includes(cat.label)) {
                    allCategories.push(cat.label);
                }
            });
        });

        const colors = [
            'rgba(209, 131, 169, 0.8)', 
            'rgba(243, 200, 221, 0.8)', 
            'rgba(113, 85, 122, 0.8)', 
            'rgba(58, 52, 91, 0.8)',   
            'rgba(168, 230, 207, 0.8)', 
        ];

        const datasets = allCategories.map((catLabel, index) => {
            return {
                label: catLabel,
                data: overallData.map(event => {
                    const found = event.categories.find(c => c.label === catLabel);
                    return found ? found.sold : 0;
                }),
                backgroundColor: colors[index % colors.length],
                borderColor: 'transparent',
                borderWidth: 0,
                borderRadius: 4
            };
        });

        new Chart(overallCtx, {
            type: 'bar',
            data: {
                labels: overallData.map(d => d.name),
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                layout: {
                    padding: { right: 50 }
                },
                scales: {
                    x: {
                        stacked: true,
                        beginAtZero: true,
                        grid: { color: 'rgba(243, 200, 221, 0.05)' },
                        ticks: { color: 'rgba(243, 200, 221, 0.7)' }
                    },
                    y: {
                        stacked: true,
                        grid: { display: false },
                        ticks: { 
                            color: '#fff',
                            font: { weight: '600' }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            color: '#F3C8DD',
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { family: "'Outfit', sans-serif" }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(58, 52, 91, 0.95)',
                        titleFont: { size: 14, family: "'Outfit', sans-serif" },
                        padding: 12,
                        cornerRadius: 12
                    }
                }
            },
            plugins: [{
                id: 'totalLabels',
                afterDatasetsDraw(chart) {
                    const { ctx, scales: { x, y } } = chart;
                    ctx.save();
                    ctx.font = 'bold 12px Outfit';
                    ctx.fillStyle = '#fff';
                    ctx.textAlign = 'left';
                    ctx.textBaseline = 'middle';

                    chart.data.labels.forEach((label, i) => {
                        let total = 0;
                        chart.data.datasets.forEach(dataset => {
                            total += dataset.data[i];
                        });

                        const meta = chart.getDatasetMeta(chart.data.datasets.length - 1);
                        const bar = meta.data[i];
                        if (bar) {
                            ctx.fillText(` Total: ${total}`, bar.x + 5, bar.y);
                        }
                    });
                    ctx.restore();
                }
            }]
        });

        @if($selectedEvent)
        const ctx = document.getElementById('ticketChart').getContext('2d');
        const stats = @json($ticketStats);
        
        const labels = stats.map(s => s.jenis_tiket);
        const soldData = stats.map(s => s.terjual);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Tiket Terjual',
                        data: soldData,
                        backgroundColor: [
                            'rgba(209, 131, 169, 0.7)',
                            'rgba(113, 85, 122, 0.7)',
                            'rgba(58, 52, 91, 0.7)'
                        ],
                        borderColor: '#D183A9',
                        borderWidth: 2,
                        borderRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(243, 200, 221, 0.1)' },
                        ticks: { color: 'rgba(243, 200, 221, 0.7)' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: 'rgba(243, 200, 221, 0.7)' }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#F3C8DD',
                            font: { family: "'Outfit', sans-serif" }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(58, 52, 91, 0.9)',
                        padding: 12,
                        displayColors: false
                    }
                }
            }
        });
        @endif
    </script>
</body>
</html>
