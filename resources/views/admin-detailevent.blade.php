<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tixora - Admin Event Detail</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
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
            padding-bottom: 60px;
        }

        .content-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 40px;
        }

        .back-nav {
            margin-bottom: 25px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--middle-purple);
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: transform 0.2s;
        }

        .btn-back:hover {
            transform: translateX(-5px);
        }

        .event-hero {
            position: relative;
            height: 450px;
            border-radius: 24px;
            overflow: hidden;
            margin-bottom: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            border: 1px solid rgba(243, 200, 221, 0.1);
        }

        .event-hero img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .event-hero-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 60px 40px 40px;
            background: linear-gradient(transparent, rgba(58, 52, 91, 0.95));
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .hero-title-group h1 {
            font-size: 2.8rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
            text-shadow: 0 0 20px rgba(0,0,0,0.5);
        }

        .event-badge {
            background: var(--middle-purple);
            color: var(--jacarta);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
        }

        .section-card {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(243, 200, 221, 0.12);
            border-radius: 24px;
            padding: 35px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
            letter-spacing: -0.5px;
        }

        .section-title i {
            color: var(--middle-purple);
            font-size: 1.8rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 16px;
            border: 1px solid rgba(243, 200, 221, 0.05);
        }

        .info-icon {
            width: 48px;
            height: 48px;
            background: rgba(209, 131, 169, 0.2);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: var(--middle-purple);
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(209, 131, 169, 0.1);
        }

        .info-text h4 {
            font-size: 0.8rem;
            opacity: 0.5;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .info-text p {
            font-size: 1.15rem;
            font-weight: 600;
            color: #fff;
        }

        .stats-summary {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .stat-mini-card {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            border: 1px solid rgba(243, 200, 221, 0.08);
            transition: transform 0.3s ease;
        }

        .stat-mini-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.05);
        }

        .stat-value {
            display: block;
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.8rem;
            opacity: 0.6;
            text-transform: uppercase;
        }

        .ticket-table {
            width: 100%;
            border-collapse: collapse;
        }

        .ticket-table th {
            text-align: left;
            padding: 15px;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--middle-purple);
            border-bottom: 1px solid rgba(243, 200, 221, 0.1);
        }

        .ticket-table td {
            padding: 15px;
            border-bottom: 1px solid rgba(243, 200, 221, 0.05);
        }

        .ticket-name {
            font-weight: 600;
            color: #fff;
        }

        .ticket-status {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .status-active { background: rgba(132, 216, 165, 0.15); color: #84d8a5; }
        .status-warning { background: rgba(255, 167, 38, 0.15); color: #ffa726; }
        .status-danger { background: rgba(239, 83, 80, 0.15); color: #ef5350; }

        @media (max-width: 900px) {
            .detail-grid { grid-template-columns: 1fr; }
            .event-hero { height: 320px; }
            .hero-title-group h1 { font-size: 2.22rem; }
            .content-container { padding: 0 20px; }
        }
    </style>
</head>
<body>

    <header class="topbar">
        <div class="logo">TIXORA</div>
        <a href="{{ route('profile.edit') }}" class="profile" title="My Profile" style="text-decoration:none;">
            {{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'U', 0, 1)) }}
        </a>
    </header>

    <aside class="sidebar">
        <div class="sidebar-content" style="display: flex; flex-direction: column; height: calc(100vh - var(--topbar-height));">
            <ul class="sidebar-menu" style="flex-grow: 1; padding-top: 20px;">
                <li>
                    <a href="{{ url('/admin/dashboard') }}" class="sidebar-item active">
                        <i class="ph ph-house sidebar-icon"></i>
                        <span class="sidebar-text">Home</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.revenue') }}" class="sidebar-item">
                        <i class="ph ph-currency-dollar sidebar-icon"></i>
                        <span class="sidebar-text">Revenue</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <main class="main-wrapper">
        <div class="content-container">

            <div class="event-hero">
                @if($event->poster)
                    <img src="{{ asset($event->poster) }}" alt="{{ $event->nama_event }}">
                @else
                    <div style="width: 100%; height: 100%; background: var(--old-lavender); display: flex; align-items: center; justify-content: center;">
                        <i class="ph ph-image" style="font-size: 5rem; opacity: 0.2;"></i>
                    </div>
                @endif
                <div class="event-hero-overlay">
                    <div class="hero-title-group">
                        <h1>{{ $event->nama_event }}</h1>
                    </div>
                </div>
            </div>

            <div class="detail-grid">
                <div class="detail-left">
                    <div class="section-card">
                        <h2 class="section-title"><i class="ph ph-info"></i> Event Information</h2>
                        
                        <div class="info-item">
                            <div class="info-icon"><i class="ph ph-calendar"></i></div>
                            <div class="info-text">
                                <h4>Tanggal Pelaksanaan</h4>
                                <p>{{ $event->tanggal_pelaksanaan ? \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->format('d F Y') : 'TBD' }}</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon"><i class="ph ph-map-pin"></i></div>
                            <div class="info-text">
                                <h4>Lokasi</h4>
                                <p>{{ $event->lokasi_event ?? 'Lokasi belum ditentukan' }}</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon"><i class="ph ph-tag"></i></div>
                            <div class="info-text">
                                <h4>Kategori</h4>
                                <p>{{ $event->id_kategori == 1 ? 'Indonesia' : ($event->id_kategori == 2 ? 'Western' : 'K-Pop') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="section-card">
                        <h2 class="section-title"><i class="ph ph-ticket"></i> Ticket Breakdown</h2>
                        <table class="ticket-table">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Sold</th>
                                    <th>Remaining</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ticketStats as $stat)
                                <tr>
                                    <td class="ticket-name">{{ $stat->jenis_tiket }}</td>
                                    <td>Rp {{ number_format($stat->harga, 0, ',', '.') }}</td>
                                    <td>{{ number_format($stat->terjual) }}</td>
                                    <td>
                                        @if($stat->sisa <= 0)
                                            <span class="ticket-status status-danger">SOLD OUT</span>
                                        @elseif($stat->sisa < 10)
                                            <span class="ticket-status status-warning">LOW ({{ $stat->sisa }})</span>
                                        @else
                                            <span class="ticket-status status-active">{{ $stat->sisa }} Left</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="detail-right">
                    <div class="section-card">
                        <h2 class="section-title"><i class="ph ph-chart-pie-slice"></i> Sales Summary</h2>
                        <div class="stats-summary">
                            <div class="stat-mini-card">
                                <span class="stat-value">{{ number_format($totalTickets) }}</span>
                                <span class="stat-label">Total Quota</span>
                            </div>
                            <div class="stat-mini-card">
                                <span class="stat-value" style="color: #84d8a5;">{{ number_format($ticketsSold) }}</span>
                                <span class="stat-label">Tickets Sold</span>
                            </div>
                        </div>
                        <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid rgba(243, 200, 221, 0.1);">
                            <span class="card-label" style="display: block; margin-bottom: 10px; font-size: 0.8rem; text-align: center;">Sales Progress</span>
                            <div style="width: 100%; height: 8px; background: rgba(255,255,255,0.05); border-radius: 10px; overflow: hidden;">
                                <div style="width: {{ $totalTickets > 0 ? ($ticketsSold / $totalTickets) * 100 : 0 }}%; height: 100%; background: var(--middle-purple); box-shadow: 0 0 10px var(--middle-purple);"></div>
                            </div>
                            <span style="display: block; margin-top: 8px; font-size: 0.9rem; text-align: right; font-weight: 600;">
                                {{ $totalTickets > 0 ? round(($ticketsSold / $totalTickets) * 100, 1) : 0 }}%
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
</body>
</html>
