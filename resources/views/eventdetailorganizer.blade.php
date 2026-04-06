<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tixora - Event Detail Organizer</title>
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

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
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

        .event-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .event-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 30px;
            text-shadow: 0 0 15px rgba(243, 200, 221, 0.4);
        }

        .poster-placeholder {
            width: 100%;
            max-height: 500px;
            aspect-ratio: 16/9;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(243, 200, 221, 0.1);
            border-radius: 20px;
            margin: 0 auto 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: rgba(243, 200, 221, 0.3);
            font-size: 1.5rem;
            font-weight: 500;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            overflow: hidden;
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

        /* Stats Table */
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
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }

        .stats-table td {
            padding: 15px;
            border-bottom: 1px solid rgba(243, 200, 221, 0.05);
        }

        .stats-table tr:last-child td {
            border-bottom: none;
        }

        .badge {
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-info { background: rgba(144, 202, 249, 0.2); color: #90caf9; }

        /* Actions Section */
        .actions-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .action-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(243, 200, 221, 0.1);
            border-radius: 12px;
            padding: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            background: rgba(58, 52, 91, 0.4);
            border: 1px solid rgba(243, 200, 221, 0.2);
            border-radius: 8px;
            color: #fff;
            padding: 12px;
            outline: none;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: var(--middle-purple);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--middle-purple);
            color: var(--jacarta);
        }

        .btn-primary:hover {
            background: var(--queen-pink);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(209, 131, 169, 0.4);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--middle-purple);
            color: var(--middle-purple);
        }

        .btn-outline:hover {
            background: rgba(209, 131, 169, 0.1);
        }

        @media (max-width: 900px) {
            .actions-grid { grid-template-columns: 1fr; }
            .content-container { padding: 0 20px; }
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

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-content" style="display: flex; flex-direction: column; height: calc(100vh - var(--topbar-height));">
            <ul class="sidebar-menu" style="flex-grow: 1; padding-top: 20px;">
                <li>
                    <a href="{{ url('/organizerdashboard') }}" class="sidebar-item">
                        <i class="ph ph-house sidebar-icon"></i>
                        <span class="sidebar-text">Home</span>
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
            @if(session('success'))
                <div style="background: rgba(132, 216, 165, 0.2); color: #84d8a5; padding: 15px; border-radius: 10px; margin-bottom: 25px; border: 1px solid rgba(132, 216, 165, 0.3);">
                    <i class="ph ph-check-circle" style="margin-right: 8px;"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div style="background: rgba(239, 83, 80, 0.2); color: #ef5350; padding: 15px; border-radius: 10px; margin-bottom: 25px; border: 1px solid rgba(239, 83, 80, 0.3);">
                    <ul style="list-style: none;">
                        @foreach($errors->all() as $error)
                            <li><i class="ph ph-warning-circle" style="margin-right: 8px;"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="event-header">
                <h1 class="event-title">{{ $event->nama_event }}</h1>
                
                <div style="display: flex; justify-content: center; gap: 30px; margin-bottom: 30px; color: var(--queen-pink); font-size: 1.1rem; opacity: 0.9;">
                    <span><i class="ph ph-calendar" style="margin-right: 8px; color: var(--middle-purple);"></i> {{ $event->tanggal_pelaksanaan ? \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->format('d F Y') : 'Date TBD' }}</span>
                    <span><i class="ph ph-clock" style="margin-right: 8px; color: var(--middle-purple);"></i> {{ $event->waktu_pelaksanaan ? \Carbon\Carbon::parse($event->waktu_pelaksanaan)->format('H:i') : 'Time TBD' }} WIB</span>
                    <span><i class="ph ph-map-pin" style="margin-right: 8px; color: var(--middle-purple);"></i> {{ $event->lokasi_event ?? 'Lokasi belum ditentukan' }}</span>
                </div>

                <div class="poster-placeholder">
                    @if($event->gambar_event)
                        <img src="{{ $event->gambar_event }}" alt="{{ $event->nama_event }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span><i class="ph ph-image" style="font-size: 3rem; display: block; margin-bottom: 10px; opacity: 0.5;"></i> [ Poster Placeholder ]</span>
                    @endif
                </div>
            </div>

            <div class="section-card">
                <h2 class="section-title"><i class="ph ph-chart-bar"></i> Statistik Penjualan Tiket</h2>
                <div style="overflow-x: auto;">
                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Total Kuota</th>
                                <th>Terjual</th>
                                <th>Sisa</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ticketStats as $stat)
                            <tr>
                                <td style="font-weight: 600; color: #fff;">{{ $stat->jenis_tiket }}</td>
                                <td>Rp {{ number_format($stat->harga, 0, ',', '.') }}</td>
                                <td>{{ number_format($stat->kuota) }}</td>
                                <td style="color: #84d8a5;">{{ number_format($stat->terjual) }}</td>
                                <td style="color: var(--middle-purple);">{{ number_format($stat->sisa) }}</td>
                                <td>
                                    @if($stat->sisa <= 0)
                                        <span class="badge" style="background: rgba(239, 83, 80, 0.2); color: #ef5350;">SOLD OUT</span>
                                    @elseif($stat->sisa < 10)
                                        <span class="badge" style="background: rgba(255, 167, 38, 0.2); color: #ffa726;">LOW STOCK</span>
                                    @else
                                        <span class="badge" style="background: rgba(132, 216, 165, 0.2); color: #84d8a5;">AVAILABLE</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="section-card">
                <h2 class="section-title"><i class="ph ph-gear"></i> Fitur Aksi Manajer</h2>
                <div class="actions-grid">

                    <div class="action-card">
                        <form action="{{ route('organizer.event.update-description', $event->id_event) }}" method="POST">
                            @csrf
                            <label class="form-label"><i class="ph ph-note-pencil"></i> Ubah Deskripsi Event</label>
                            <textarea name="deskripsi" class="form-control" rows="4" placeholder="Masukkan deskripsi baru untuk event ini..."></textarea>
                            <button type="submit" class="btn btn-primary" style="margin-top: 15px; width: 100%;">Update Deskripsi</button>
                        </form>
                    </div>

                    <div class="action-card">
                        <form action="{{ route('organizer.event.add-quota', $event->id_event) }}" method="POST">
                            @csrf
                            <label class="form-label"><i class="ph ph-plus-circle"></i> Tambah Kuota Tiket</label>
                            <select name="id_tiket" class="form-control" style="margin-bottom: 12px;" required>
                                <option value="" disabled selected>Pilih Kategori Tiket</option>
                                @foreach($ticketStats as $stat)
                                    <option value="{{ $stat->id_tiket }}">{{ $stat->jenis_tiket }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="jumlah_tambah" class="form-control" placeholder="Jumlah kuota tambahan" min="1" required>
                            <button type="submit" class="btn btn-primary" style="margin-top: 15px; width: 100%;">Tambah Kuota</button>
                        </form>
                    </div>

                    <div class="action-card" style="grid-column: 1 / -1; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h3 style="color: #fff; font-size: 1.1rem; margin-bottom: 5px;">Daftar Peserta</h3>
                            <p style="font-size: 0.85rem; opacity: 0.7;">Download seluruh data pembeli tiket untuk event ini (CSV/Excel).</p>
                        </div>
                        <button class="btn btn-outline" style="border-radius: 50px; padding: 10px 30px;">
                            <i class="ph ph-download-simple"></i> Ekspor Data Peserta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>

    </script>
</body>
</html>
