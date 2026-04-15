<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tixora - Analitik Penjualan </title>
    <meta http-equiv="refresh" content="60">
    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
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

        .chart-wrapper {
            width: 100%;
            height: 400px;
            margin-top: 20px;
            position: relative;
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
            display: flex;
            overflow-x: auto;
            gap: 20px;
            padding-bottom: 20px;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }

        .event-grid::-webkit-scrollbar {
            height: 8px;
        }

        .event-grid::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        .event-grid::-webkit-scrollbar-thumb {
            background: rgba(209, 131, 169, 0.3);
            border-radius: 10px;
        }

        .event-grid::-webkit-scrollbar-thumb:hover {
            background: rgba(209, 131, 169, 0.5);
        }

        .event-select-card {
            flex: 0 0 320px;
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
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .stats-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
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

        .progress-container {
            width: 100%;
            height: 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50px;
            overflow: hidden;
            position: relative;
            border: 1px solid rgba(243, 200, 221, 0.1);
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--middle-purple), var(--queen-pink));
            border-radius: 50px;
            transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .event-perf-item {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(243, 200, 221, 0.05);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .event-perf-item:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: scale(1.01);
            border-color: rgba(243, 200, 221, 0.2);
        }

        .event-perf-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .event-perf-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
        }

        .event-perf-stats {
            font-size: 0.9rem;
            color: var(--queen-pink);
            opacity: 0.8;
        }

        .category-pill-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .category-pill {
            background: rgba(209, 131, 169, 0.1);
            border: 1px solid rgba(209, 131, 169, 0.2);
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            color: var(--queen-pink);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .category-pill i {
            font-size: 0.8rem;
            color: var(--middle-purple);
        }

        .event-performance-list {
            max-height: 520px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .event-performance-list::-webkit-scrollbar {
            width: 6px;
        }

        .event-performance-list::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        .event-performance-list::-webkit-scrollbar-thumb {
            background: rgba(209, 131, 169, 0.3);
            border-radius: 10px;
        }

        .event-performance-list::-webkit-scrollbar-thumb:hover {
            background: rgba(209, 131, 169, 0.5);
        }

        @media (max-width: 1100px) {
            .detail-layout { grid-template-columns: 1fr; }
            .overall-stats-grid { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 768px) {
            .overall-stats-grid { grid-template-columns: 1fr; }
            .stats-summary { grid-template-columns: 1fr; }
            .event-select-card { flex: 0 0 280px; }
        }

        /* Live Pulse Indicator */
        .live-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(132, 216, 165, 0.15);
            color: #84d8a5;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-left: 15px;
            vertical-align: middle;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background: #84d8a5;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(132, 216, 165, 0.7);
            animation: pulse-green 2s infinite;
        }

        @keyframes pulse-green {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(132, 216, 165, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(132, 216, 165, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(132, 216, 165, 0); }
        }

        .search-container {
            margin-bottom: 20px;
            position: relative;
        }

        .event-search-input {
            width: 100%;
            padding: 12px 20px 12px 45px;
            background: rgba(58, 52, 91, 0.4);
            border: 1px solid rgba(243, 200, 221, 0.2);
            border-radius: 50px;
            color: #fff;
            font-size: 0.95rem;
            transition: all 0.3s;
            backdrop-filter: blur(10px);
        }

        .event-search-input:focus {
            outline: none;
            border-color: var(--middle-purple);
            box-shadow: 0 0 15px rgba(209, 131, 169, 0.3);
            background: rgba(58, 52, 91, 0.6);
        }

        .search-icon-inside {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--queen-pink);
            opacity: 0.6;
            font-size: 1.2rem;
            pointer-events: none;
        }
    </style>
</head>
<body>

    <x-organizer-topbar />

    @if(isset($role) && ($role == '1' || $role == 'admin'))
        <x-admin-sidebar />
    @else
        <x-organizer-sidebar />
    @endif

    <main class="main-wrapper">
        <div class="content-container">
            <div class="page-header">
                <h1 class="page-title">
                    Analitik Penjualan
                </h1>
                <p class="page-subtitle">
                    @if($role === 'admin' || $role === '1')
                        Pantau performa penjualan seluruh event
                    @else
                        Pantau performa penjualan tiket untuk event yang Anda kelola
                    @endif
                </p>
            </div>


            <div class="section-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 10px;">
                    <h2 class="section-title" style="margin: 0;"><i class="ph ph-presentation-chart"></i> 
                        @if($role === 'admin' || $role === '1')
                            Ringkasan Performa Seluruh Event
                        @else
                            Ringkasan Performa Event Saya
                        @endif
                    </h2>
                    <div class="current-time" style="color: var(--queen-pink); opacity: 0.7; font-size: 0.9rem; display: flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.05); padding: 5px 15px; border-radius: 50px;">
                        <i class="ph ph-calendar"></i>
                        {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>

            <div class="section-card">
                <h2 class="section-title"><i class="ph ph-calendar-check"></i> Pilih Event</h2>
                
                <div class="search-container">
                    <i class="ph ph-magnifying-glass search-icon-inside"></i>
                    <input type="text" id="eventSearch" class="event-search-input" placeholder="Cari nama event untuk melihat statistiknya...">
                </div>

                <div class="event-grid">
                    @foreach($events as $event)
                    @php
                        $thisEventData = collect($eventChartData)->firstWhere('name', $event->nama_event);
                        $routeTarget = ($role === 'admin' || $role === '1') ? 'admin.statistik' : 'organizer.statistik';
                    @endphp
                    <a href="{{ route($routeTarget, ['id' => $event->id_event]) }}" 
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
            <div id="eventDetail" class="section-card">
                <h3 class="section-title" style="margin-bottom: 25px;"><i class="ph ph-chart-line"></i> Detail Performa: {{ $selectedEvent->nama_event }}</h3>
                
                <div class="detail-layout">
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

                    <div style="overflow-x: auto;">
                        <table class="stats-table" id="detailTable">
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

                <div class="export-footer" style="margin-top: 30px; padding-top: 25px; border-top: 1px solid rgba(243, 200, 221, 0.1); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                    <div class="export-text" style="color: var(--queen-pink); font-size: 1.1rem; font-weight: 500;">
                        <i class="ph ph-file-text" style="margin-right: 8px; color: var(--middle-purple);"></i>
                        Ringkasan laporan penjualan tiket event
                    </div>
                    <div class="export-actions" style="display: flex; gap: 12px;">
                        <button onclick="exportToExcel()" class="export-btn excel" style="background: rgba(132, 216, 165, 0.2); color: #84d8a5; border: 1px solid rgba(132, 216, 165, 0.3); padding: 10px 20px; border-radius: 10px; cursor: pointer; display: flex; align-items: center; gap: 8px; font-weight: 600; transition: all 0.3s; height: 45px;">
                            <i class="ph ph-file-xls" style="font-size: 1.2rem;"></i> Export Excel
                        </button>
                        <button onclick="exportToPDF()" class="export-btn pdf" style="background: rgba(209, 131, 169, 0.2); color: var(--middle-purple); border: 1px solid rgba(209, 131, 169, 0.3); padding: 10px 20px; border-radius: 10px; cursor: pointer; display: flex; align-items: center; gap: 8px; font-weight: 600; transition: all 0.3s; height: 45px;">
                            <i class="ph ph-file-pdf" style="font-size: 1.2rem;"></i> Export PDF
                        </button>
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
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const data = @json($eventChartData);
        
        const labels = data.map(item => item.name);
        const soldData = data.map(item => item.sold);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Total Tiket Terjual',
                        data: soldData,
                        backgroundColor: 'rgba(209, 131, 169, 0.6)', 
                        borderColor: '#D183A9',
                        borderWidth: 2,
                        borderRadius: 8,
                        barThickness: 50,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#F3C8DD', 
                            font: {
                                family: "'Outfit', sans-serif",
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(58, 52, 91, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#F3C8DD',
                        borderColor: 'rgba(243, 200, 221, 0.2)',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y.toLocaleString() + ' Tiket';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#F3C8DD',
                            font: {
                                family: "'Outfit', sans-serif"
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(243, 200, 221, 0.05)'
                        },
                        ticks: {
                            color: '#F3C8DD',
                            font: {
                                family: "'Outfit', sans-serif"
                            },
                            precision: 0,
                            callback: function(value) {
                                if (Math.floor(value) === value) {
                                    return value.toLocaleString() + ' Tiket';
                                }
                            }
                        }
                    }
                }
            }
        });

        // Export Functions
        const buyerData = @json($buyerList ?? []);

        window.exportToExcel = function() {
            const eventName = "{{ $selectedEvent->nama_event ?? 'Event' }}";
            
            if (buyerData.length === 0) {
                alert('Tidak ada data penjualan untuk diexport.');
                return;
            }

            const ws = XLSX.utils.json_to_sheet(buyerData);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Daftar Pembeli");
            
            XLSX.writeFile(wb, `Laporan_Pembeli_${eventName.replace(/\s+/g, '_')}.xlsx`);
        };

        window.exportToPDF = function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'mm', 'a4');
            const eventName = "{{ $selectedEvent->nama_event ?? 'Event' }}";
            
            if (buyerData.length === 0) {
                alert('Tidak ada data penjualan untuk diexport.');
                return;
            }

            // Header
            doc.setFontSize(20);
            doc.setTextColor(58, 52, 91);
            doc.text("Laporan Daftar Pembeli Tiket", 14, 22);
            
            doc.setFontSize(12);
            doc.setTextColor(100);
            doc.text(`Event: ${eventName}`, 14, 32);
            doc.text(`Tanggal Cetak: ${new Date().toLocaleDateString('id-ID')}`, 14, 40);
            
            // Stats Summary
            doc.setFontSize(11);
            doc.text(`Total Terjual: {{ $selectedSold ?? 0 }} Tiket`, 14, 50);
            
            // Table mapping
            const columns = [
                { header: 'Nama Buyer', dataKey: 'Nama Buyer' },
                { header: 'Email', dataKey: 'Email' },
                { header: 'No Telp', dataKey: 'No Telp' },
                { header: 'Kategori Tiket', dataKey: 'Kategori Tiket' },
                { header: 'ID Transaksi', dataKey: 'ID Transaksi' },
                { header: 'Jumlah', dataKey: 'Jumlah' }
            ];

            doc.autoTable({
                columns: columns,
                body: buyerData,
                startY: 60,
                theme: 'grid',
                headStyles: { fillColor: [58, 52, 91], textColor: [255, 255, 255], fontStyle: 'bold' },
                styles: { fontSize: 9, cellPadding: 3 },
                alternateRowStyles: { fillColor: [250, 250, 250] }
            });
            
            doc.save(`Laporan_Pembeli_${eventName.replace(/\s+/g, '_')}.pdf`);
        };

        const searchInput = document.getElementById('eventSearch');
        const eventCards = document.querySelectorAll('.event-select-card');

        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const term = e.target.value.toLowerCase();
                eventCards.forEach(card => {
                    const title = card.querySelector('h4').textContent.toLowerCase();
                    if (title.includes(term)) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
</body>
</html>
