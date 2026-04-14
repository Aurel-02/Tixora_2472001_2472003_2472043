<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tixora - Laporan Check In</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
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
            --green: #84d8a5;
            --warning: #f6ad55;
            --danger: #fc8181;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }

        body {
            background: linear-gradient(135deg, var(--jacarta), var(--brown-chocolate));
            color: var(--queen-pink);
            min-height: 100vh;
            overflow-x: hidden;
            background-attachment: fixed;
        }

        /* ── Header & Sidebar ── */
        .topbar {
            position: fixed; top: 0; left: var(--sidebar-width-collapsed); right: 0;
            height: var(--topbar-height);
            background: rgba(58,52,91,0.6); backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(243,200,221,0.1);
            display: flex; justify-content: space-between; align-items: center;
            padding: 0 40px; z-index: 900;
        }
        .topbar .logo { font-size: 1.8rem; font-weight: 700; letter-spacing: 2px; color: var(--queen-pink); text-shadow: 0 0 10px rgba(243,200,221,0.5); text-transform: uppercase; }
        .topbar .profile { width: 42px; height: 42px; border-radius: 50%; background: var(--middle-purple); color: var(--jacarta); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem; cursor: pointer; text-decoration: none; }

        .sidebar { position: fixed; top: 0; left: 0; width: var(--sidebar-width-collapsed); height: 100vh; background: rgba(113,85,122,0.4); backdrop-filter: blur(15px); border-right: 1px solid rgba(243,200,221,0.15); z-index: 1000; transition: width 0.3s ease; overflow: hidden; display: flex; flex-direction: column; padding-top: var(--topbar-height); }
        .sidebar:hover { width: var(--sidebar-width-expanded); box-shadow: 5px 0 20px rgba(0,0,0,0.4); }
        .sidebar-menu { list-style: none; margin-top: 20px; display: flex; flex-direction: column; gap: 10px; }
        .sidebar-item { display: flex; align-items: center; padding: 15px 22px; color: var(--queen-pink); text-decoration: none; transition: background 0.2s, border-left 0.2s; border-left: 3px solid transparent; cursor: pointer; white-space: nowrap; }
        .sidebar-item:hover, .sidebar-item.active { background: rgba(209,131,169,0.2); border-left: 3px solid var(--middle-purple); }
        .sidebar-icon { font-size: 1.4rem; min-width: 25px; margin-right: 20px; }
        .sidebar-text { font-size: 1rem; font-weight: 500; opacity: 0; transition: opacity 0.3s ease; }
        .sidebar:hover .sidebar-text { opacity: 1; }

        /* ── Main Layout ── */
        .main-wrapper {
            margin-left: var(--sidebar-width-collapsed);
            padding: calc(var(--topbar-height) + 40px) 40px 60px;
            min-height: 100vh;
        }

        .header-section {
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;
            margin-bottom: 30px;
        }
        .judul { font-size: 2.2rem; font-weight: 700; color: #fff; display: flex; align-items: center; gap: 10px; }
        .judul i { color: var(--middle-purple); }
        
        .filter-form { display: flex; align-items: center; gap: 15px; }
        .event-select {
            background: rgba(58,52,91,0.5); border: 1px solid rgba(243,200,221,0.25);
            color: #fff; padding: 12px 18px; border-radius: 12px; font-size: 0.95rem; outline: none; cursor: pointer;
            transition: 0.3s;
        }
        .event-select:focus { border-color: var(--middle-purple); }
        .event-select option { background: var(--jacarta); color: #fff; }
        
        .btn-back {
            background: rgba(243,200,221,0.1); border: 1px solid rgba(243,200,221,0.2);
            color: var(--queen-pink); padding: 12px 20px; border-radius: 12px; text-decoration: none;
            display: flex; align-items: center; gap: 8px; font-weight: 500; transition: 0.3s;
        }
        .btn-back:hover { background: rgba(209,131,169,0.2); }

        /* ── Metrik ── */
        .metrics-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-bottom: 40px;
        }
        .metric-card {
            background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px; padding: 25px; display: flex; gap: 20px;
            align-items: center; backdrop-filter: blur(10px);
            transition: all 0.3s;
        }
        .metric-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        
        .mc-icon { width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; }
        .mc-info { display: flex; flex-direction: column; gap: 4px; }
        .mc-label { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.6; }
        .mc-value { font-size: 2.2rem; font-weight: 700; color: #fff; line-height: 1; }
        
        .m-blue .mc-icon { background: rgba(99,179,237,0.15); color: #63b3ed; }
        .m-green .mc-icon { background: rgba(132,216,165,0.15); color: var(--green); }
        .m-red .mc-icon { background: rgba(252,129,129,0.15); color: var(--danger); }

        /* ── Report Table ── */
        .table-container {
            background: rgba(113,85,122,0.2); border: 1px solid rgba(243,200,221,0.15);
            border-radius: 20px; overflow: hidden; backdrop-filter: blur(15px);
            padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .table-header { padding: 5px 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        .table-header h3 { font-size: 1.2rem; color: #fff; font-weight: 600; }
        
        table { width: 100%; border-collapse: collapse; }
        thead th {
            text-align: left; padding: 15px 20px; font-size: 0.82rem;
            color: var(--middle-purple); text-transform: uppercase; letter-spacing: 1px;
            border-bottom: 1px solid rgba(243,200,221,0.1);
        }
        tbody tr { border-bottom: 1px solid rgba(243,200,221,0.05); transition: background 0.2s; }
        tbody tr:hover { background: rgba(209,131,169,0.08); }
        tbody td { padding: 16px 20px; font-size: 0.95rem; vertical-align: middle; }
        
        .status-badge {
            display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px;
            border-radius: 50px; font-size: 0.8rem; font-weight: 600;
        }
        .sb-checked { background: rgba(132,216,165,0.15); color: var(--green); border: 1px solid rgba(132,216,165,0.3); }
        .sb-not { background: rgba(252,129,129,0.15); color: var(--danger); border: 1px solid rgba(252,129,129,0.3); }

        .empty-state { text-align: center; padding: 60px 20px; opacity: 0.5; }
        .empty-state i { font-size: 4rem; margin-bottom: 15px; display: block; }
        .empty-state p { font-size: 1.1rem; }
    </style>
</head>
<body>

<header class="topbar">
    <div class="logo">TIXORA</div>
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="{{ route('profile.edit') }}" class="profile" title="My Profile">
            @if(auth()->check() && auth()->user()->photo_profile)
                <img src="{{ asset(auth()->user()->photo_profile) }}" alt="Profile" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
            @else
                {{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'O', 0, 1)) }}
            @endif
        </a>
    </div>
</header>

<aside class="sidebar">
    <div class="sidebar-content" style="display:flex;flex-direction:column;height:calc(100vh - var(--topbar-height));">
        <ul class="sidebar-menu" style="flex-grow:1;padding-top:20px;">
            <li><a href="{{ url('/organizerdashboard') }}" class="sidebar-item"><i class="ph ph-house sidebar-icon"></i><span class="sidebar-text">Home</span></a></li>
            <li><a href="{{ route('organizer.statistik') }}" class="sidebar-item"><i class="ph ph-chart-bar sidebar-icon"></i><span class="sidebar-text">Analitik Penjualan</span></a></li>
            <li><a href="{{ route('organizer.revenue') }}" class="sidebar-item"><i class="ph ph-currency-dollar sidebar-icon"></i><span class="sidebar-text">Revenue</span></a></li>
            <li><a href="{{ route('organizer.checkin') }}" class="sidebar-item active"><i class="ph ph-qr-code sidebar-icon"></i><span class="sidebar-text">Check In</span></a></li>
            <li><a href="{{ route('organizer.notifications') }}" class="sidebar-item"><i class="ph ph-bell sidebar-icon"></i><span class="sidebar-text">Notifications</span></a></li>
        </ul>
        <div style="padding:10px 0;">
            <form action="{{ route('logout') }}" method="POST" style="margin:0;width:100%;">
                @csrf
                <button type="submit" class="sidebar-item" style="background:transparent;border:none;color:var(--queen-pink);width:100%;text-align:left;padding:15px 22px;cursor:pointer;">
                    <i class="ph ph-sign-out sidebar-icon"></i><span class="sidebar-text">Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<main class="main-wrapper">
    <div class="header-section">
        <div class="judul"><i class="ph ph-chart-line-up"></i> Laporan Check-in</div>
        <div style="display: flex; gap: 15px; align-items: center;">
            <form action="{{ route('organizer.checkin.report') }}" method="GET" class="filter-form">
                <select name="event_id" class="event-select" onchange="this.form.submit()">
                    @foreach($events as $ev)
                        <option value="{{ $ev->id_event }}" {{ $selectedEventId == $ev->id_event ? 'selected' : '' }}>
                            {{ $ev->nama_event }} - {{ \Carbon\Carbon::parse($ev->tanggal_pelaksanaan)->format('d M Y') }}
                        </option>
                    @endforeach
                    @if($events->isEmpty())
                        <option value="">Belum ada event</option>
                    @endif
                </select>
            </form>
            <a href="{{ route('organizer.checkin') }}" class="btn-back">
                <i class="ph ph-qr-code"></i> Ke Halaman Scan
            </a>
        </div>
    </div>

    @if($events->isEmpty())
        <div class="empty-state">
            <i class="ph ph-calendar-blank"></i>
            <p>Anda belum memiliki event aktif. Tidak ada laporan untuk ditampilkan.</p>
        </div>
    @else
        <div class="metrics-grid">
            <div class="metric-card m-blue">
                <div class="mc-icon"><i class="ph ph-tickets"></i></div>
                <div class="mc-info">
                    <div class="mc-label">Total Tiket Terjual</div>
                    <div class="mc-value">{{ $totalTickets }}</div>
                </div>
            </div>
            <div class="metric-card m-green">
                <div class="mc-icon"><i class="ph ph-check-circle"></i></div>
                <div class="mc-info">
                    <div class="mc-label">Sudah Check-In</div>
                    <div class="mc-value">{{ $totalCheckIn }}</div>
                </div>
            </div>
            <div class="metric-card m-red">
                <div class="mc-icon"><i class="ph ph-warning-circle"></i></div>
                <div class="mc-info">
                    <div class="mc-label">Belum Check-In</div>
                    <div class="mc-value">{{ $totalNotCheckIn }}</div>
                </div>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h3>Detail Peserta Event</h3>
            </div>
            
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode QR</th>
                            <th>Nama Penonton</th>
                            <th>Email</th>
                            <th>Jenis Tiket</th>
                            <th style="text-align: center;">Jml Beli</th>
                            <th style="text-align: center;">Status Check-in</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData as $i => $d)
                            <tr>
                                <td style="opacity: 0.5;">{{ $i + 1 }}</td>
                                <td style="font-family: monospace; letter-spacing: 1px;">{{ $d->kode_QR }}</td>
                                <td style="font-weight: 600; color: #fff;">{{ $d->nama_pemilik ?: $d->nama_lengkap }}</td>
                                <td style="opacity: 0.8;">{{ $d->email_pemilik ?: $d->email }}</td>
                                <td>{{ $d->jenis_tiket }}</td>
                                <td style="text-align: center; font-weight: 600;">{{ $d->jumlah_beli }}</td>
                                <td style="text-align: center;">
                                    @if($d->checked_in)
                                        <span class="status-badge sb-checked"><i class="ph ph-check-circle"></i> Sudah</span>
                                    @else
                                        <span class="status-badge sb-not"><i class="ph ph-x-circle"></i> Belum</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 40px; opacity: 0.6;">
                                    <i class="ph ph-ticket" style="font-size: 2.5rem; display: block; margin-bottom: 10px;"></i>
                                    Belum ada tiket terjual untuk acara ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</main>

</body>
</html>
