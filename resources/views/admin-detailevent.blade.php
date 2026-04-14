<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tixora - Admin Event Detail</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .event-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .event-title {
            font-size: 2.8rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 30px;
            text-shadow: 0 0 20px rgba(243, 200, 221, 0.4);
        }

        .metadata {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 40px;
            color: var(--queen-pink);
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .metadata span {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .metadata i {
            color: var(--middle-purple);
        }

        .countdown-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 40px;
        }

        .countdown-item {
            background: rgba(113, 85, 122, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(209, 131, 169, 0.2);
            border-radius: 15px;
            min-width: 90px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .countdown-value {
            display: block;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--queen-pink);
            line-height: 1;
            margin-bottom: 5px;
        }

        .countdown-label {
            display: block;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.6;
        }

        .event-hero {
            width: 100%;
            max-height: 500px;
            aspect-ratio: 16/9;
            border-radius: 24px;
            overflow: hidden;
            margin-bottom: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            border: 1px solid rgba(243, 200, 221, 0.1);
            background: rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .event-hero img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .management-section {
            width: 100%;
        }

        .section-card {
            background: rgba(113, 85, 122, 0.25);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(243, 200, 221, 0.15);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        .section-title {
            font-size: 1.4rem;
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

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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

        .btn-danger {
            background: rgba(239, 83, 80, 0.2);
            color: #ef5350;
            border: 1px solid rgba(239, 83, 80, 0.3);
        }

        .btn-danger:hover {
            background: rgba(239, 83, 80, 0.3);
            transform: scale(1.02);
        }

        .stats-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .stat-mini-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            border: 1px solid rgba(243, 200, 221, 0.05);
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
            font-size: 0.85rem;
            text-transform: uppercase;
            color: var(--middle-purple);
            border-bottom: 1px solid rgba(243, 200, 221, 0.1);
        }

        .ticket-table td {
            padding: 15px;
            border-bottom: 1px solid rgba(243, 200, 221, 0.05);
        }

        .ticket-status {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .status-danger { background: rgba(239, 83, 80, 0.15); color: #ef5350; }

        .status-danger { background: rgba(239, 83, 80, 0.15); color: #ef5350; }

        .preview-container {
            width: 100%;
            height: 200px;
            border: 1px dashed rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-top: 10px;
            background: rgba(0,0,0,0.2);
        }

        .preview-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

    </style>
</head>
<body>

    <header class="topbar">
        <div class="logo">TIXORA</div>
            <a href="{{ route('profile.edit') }}" class="profile" title="My Profile" style="text-decoration:none;">
                @php
                    $displayName = session('login_admin.name') ?? (auth()->check() ? auth()->user()->nama_lengkap : 'Admin');
                @endphp
                @if(auth()->check() && auth()->user()->photo_profile)
                    <img src="{{ asset(auth()->user()->photo_profile) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                @else
                    {{ strtoupper(substr($displayName, 0, 1)) }}
                @endif
            </a>
    </header>

    <aside class="sidebar">
        <div class="sidebar-content" style="display: flex; flex-direction: column; height: calc(100vh - var(--topbar-height));">
            <ul class="sidebar-menu" style="flex-grow: 1; padding-top: 20px;">
                    <li>
                        <a href="{{ url('/admin/dashboard') }}" class="sidebar-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                            <i class="ph ph-house sidebar-icon"></i>
                            <span class="sidebar-text">Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.revenue') }}" class="sidebar-item {{ Request::is('admin/revenue') ? 'active' : '' }}">
                            <i class="ph ph-currency-dollar sidebar-icon"></i>
                            <span class="sidebar-text">Revenue</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.events.create') }}" class="sidebar-item {{ Request::is('admin/events/create') ? 'active' : '' }}">
                            <i class="ph ph-plus-circle sidebar-icon"></i>
                            <span class="sidebar-text">Tambah Event</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.statistik') }}" class="sidebar-item {{ Request::is('admin/statistik') ? 'active' : '' }}">
                            <i class="ph ph-chart-bar sidebar-icon"></i>
                            <span class="sidebar-text">Analitik Penjualan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.notifications') }}" class="sidebar-item {{ Request::is('admin/notifikasi') ? 'active' : '' }}" style="position: relative;">
                            <i class="ph ph-bell sidebar-icon"></i>
                            <span class="sidebar-text">Notifikasi</span>
                            @if(isset($unreadCount) && $unreadCount > 0)
                            <span style="position: absolute; right: 15px; margin-top: -3px; background: #ef4444; color: white; font-size: 0.65rem; font-weight: bold; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border-radius: 50%; box-shadow: 0 0 10px rgba(239, 68, 68, 0.6);">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/user-management') }}" class="sidebar-item {{ Request::is('admin/user-management*') ? 'active' : '' }}">
                            <i class="ph ph-users-three sidebar-icon"></i>
                            <span class="sidebar-text">User Management</span>
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

            <div class="event-header">
                <h1 class="event-title">{{ $event->nama_event }}</h1>
                
                <div class="metadata">
                    <span><i class="ph ph-calendar"></i> {{ $event->tanggal_pelaksanaan ? \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->format('d F Y') : 'Date TBD' }}</span>
                    <span><i class="ph ph-clock"></i> {{ $event->waktu_pelaksanaan ? \Carbon\Carbon::parse($event->waktu_pelaksanaan)->format('H:i') : 'Time TBD' }} WIB</span>
                    <span><i class="ph ph-map-pin"></i> {{ $event->lokasi_event ?? 'Lokasi belum ditentukan' }}</span>
                </div>

                <div class="countdown-container" id="countdown" data-target="{{ $event->tanggal_pelaksanaan }} {{ $event->waktu_pelaksanaan }}">
                    <div class="countdown-item">
                        <span class="countdown-value" id="days">00</span>
                        <span class="countdown-label">Days</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-value" id="hours">00</span>
                        <span class="countdown-label">Hours</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-value" id="minutes">00</span>
                        <span class="countdown-label">Minutes</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-value" id="seconds">00</span>
                        <span class="countdown-label">Seconds</span>
                    </div>
                </div>

                <div class="event-hero">
                    @if($event->poster)
                        <img src="{{ asset($event->poster) }}" alt="{{ $event->nama_event }}">
                    @else
                        <span><i class="ph ph-image" style="font-size: 5rem; opacity: 0.2;"></i></span>
                    @endif
                </div>
            </div>

            <div class="management-section">
                <!-- 1. Sales Summary (Moved to top after poster) -->
                <div class="section-card">
                    <h2 class="section-title"><i class="ph ph-chart-bar"></i> Sales Summary</h2>
                    <div class="stats-summary">
                        <div class="stat-mini-card">
                            <span class="stat-value">{{ number_format($totalTickets) }}</span>
                            <span class="stat-label">Total Quota</span>
                        </div>
                        <div class="stat-mini-card">
                            <span class="stat-value" style="color: #84d8a5;">{{ number_format($ticketsSold) }}</span>
                            <span class="stat-label">Tickets Sold</span>
                        </div>
                        <div class="stat-mini-card">
                            <span class="stat-value" style="color: var(--middle-purple);">{{ number_format($ticketsAvailable) }}</span>
                            <span class="stat-label">Available</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Management Hub (Inline Full Edit + Add Quota) -->
                <div class="section-card">
                    <h2 class="section-title"><i class="ph ph-pencil-line"></i>Management Event</h2>
                    
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
                        <!-- Full Edit Form -->
                        <div class="action-card">
                            <h3 class="form-label" style="font-size: 1.1rem; margin-bottom: 20px; color: var(--middle-purple);">Edit Event Details</h3>
                            <form action="{{ route('admin.events.update', $event->id_event) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                    <div style="grid-column: span 2;">
                                        <label class="form-label">Nama Event</label>
                                        <input type="text" name="nama_event" class="form-control" value="{{ $event->nama_event }}" required>
                                    </div>

                                    <div>
                                        <label class="form-label">Kategori</label>
                                        <select name="id_kategori" class="form-control" required>
                                            <option value="1" {{ $event->id_kategori == 1 ? 'selected' : '' }}>Indonesia</option>
                                            <option value="2" {{ $event->id_kategori == 2 ? 'selected' : '' }}>Western</option>
                                            <option value="3" {{ $event->id_kategori == 3 ? 'selected' : '' }}>K-Pop</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="form-label">Lokasi Event</label>
                                        <input type="text" name="lokasi_event" class="form-control" value="{{ $event->lokasi_event }}" required>
                                    </div>

                                    <div>
                                        <label class="form-label">Tanggal Pelaksanaan</label>
                                        <input type="date" name="tanggal_pelaksanaan" class="form-control" value="{{ $event->tanggal_pelaksanaan }}" required>
                                    </div>

                                    <div>
                                        <label class="form-label">Waktu Pelaksanaan</label>
                                        <input type="time" name="waktu_pelaksanaan" class="form-control" value="{{ \Carbon\Carbon::parse($event->waktu_pelaksanaan)->format('H:i') }}" required>
                                    </div>

                                    <div style="grid-column: span 2;">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea name="deskripsi" class="form-control" rows="4" required>{{ $event->deskripsi }}</textarea>
                                    </div>

                                    <div style="grid-column: span 2;">
                                        <label class="form-label">Poster Event</label>
                                        <input type="file" name="poster" id="poster-input" class="form-control" accept="image/*" style="padding: 10px;">
                                        <div class="preview-container" id="poster-preview">
                                            @if($event->poster)
                                                <img src="{{ asset($event->poster) }}" alt="Current Poster">
                                            @else
                                                <span style="opacity: 0.5;">No image selected</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div style="grid-column: span 2; margin-top: 10px;">
                                        <button type="submit" class="btn btn-primary" style="width: 100%;">Save All Changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Add Quota Area -->
                        <div style="display: flex; flex-direction: column; gap: 20px;">
                            <div class="action-card">
                                <h3 class="form-label" style="font-size: 1.1rem; margin-bottom: 20px; color: var(--middle-purple);">Quick Add Quota</h3>
                                <form action="{{ route('organizer.event.add-quota', $event->id_event) }}" method="POST">
                                    @csrf
                                    <label class="form-label">Category</label>
                                    <select name="id_tiket" class="form-control" style="margin-bottom: 12px;" required>
                                        @foreach($ticketStats as $stat)
                                            <option value="{{ $stat->id_tiket }}">{{ $stat->jenis_tiket }}</option>
                                        @endforeach
                                    </select>
                                    <label class="form-label">Quantity</label>
                                    <input type="number" name="jumlah_tambah" class="form-control" placeholder="Qty" min="1" required>
                                    <button type="submit" class="btn btn-primary" style="margin-top: 20px; width: 100%;">Add Quota</button>
                                </form>
                            </div>
                            
                            <div class="action-card" style="background: rgba(209, 131, 169, 0.05);">
                                <p style="font-size: 0.85rem; opacity: 0.7; line-height: 1.6;">
                                    <i class="ph ph-info" style="color: var(--middle-purple);"></i>
                                    Updating event details or adding quota will take effect immediately for all users.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Ticket Table -->
                <div class="section-card">
                    <h2 class="section-title"><i class="ph ph-ticket"></i> Ticket Category Breakdown</h2>
                    <table class="ticket-table">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Price</th>
                                <th style="text-align: center;">Sold</th>
                                <th style="text-align: right;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ticketStats as $stat)
                            <tr>
                                <td style="font-weight: 600; color: #fff;">{{ $stat->jenis_tiket }}</td>
                                <td style="color: rgba(243, 200, 221, 0.8);">Rp {{ number_format($stat->harga, 0, ',', '.') }}</td>
                                <td style="text-align: center; font-weight: 700;">{{ number_format($stat->terjual) }}</td>
                                <td style="text-align: right;">
                                    @if($stat->sisa <= 0)
                                        <span class="ticket-status status-danger">SOLD OUT</span>
                                    @elseif($stat->sisa < 10)
                                        <span class="ticket-status status-warning" style="background: rgba(255, 167, 38, 0.15); color: #ffa726;">LOW ({{ $stat->sisa }})</span>
                                    @else
                                        <span class="ticket-status status-active" style="background: rgba(132, 216, 165, 0.15); color: #84d8a5;">{{ $stat->sisa }} Left</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- 4. Delete Event -->
                <div class="section-card" style="border-color: rgba(239, 83, 80, 0.2); text-align: center; padding: 40px;">
                    <button id="btnHapusEvent" class="btn btn-danger" style="padding: 18px 60px; font-size: 1.1rem; border-radius: 50px;">
                        <i class="ph ph-trash" style="margin-right: 8px;"></i> Hapus Event
                    </button>
                </div>
            </div>
        </div>
    </main>

    <script>
        function updateCountdown() {
            const countdownEl = document.getElementById('countdown');
            if (!countdownEl) return;
            
            const targetStr = countdownEl.getAttribute('data-target');
            const targetDate = new Date(targetStr).getTime();
            
            const timer = setInterval(() => {
                const now = new Date().getTime();
                const distance = targetDate - now;
                
                if (distance < 0) {
                    clearInterval(timer);
                    countdownEl.innerHTML = "<h3 style='color: var(--middle-purple);'>EVENT HAS STARTED</h3>";
                    return;
                }
                
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                document.getElementById('days').innerText = days.toString().padStart(2, '0');
                document.getElementById('hours').innerText = hours.toString().padStart(2, '0');
                document.getElementById('minutes').innerText = minutes.toString().padStart(2, '0');
                document.getElementById('seconds').innerText = seconds.toString().padStart(2, '0');
            }, 1000);
        }

        updateCountdown();

        // Poster preview logic
        const posterInput = document.getElementById('poster-input');
        const posterPreview = document.getElementById('poster-preview');

        if(posterInput) {
            posterInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        posterPreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }


        const eventId   = {{ $event->id_event }};
        const eventName = @json($event->nama_event);
        const checkUrl  = "{{ route('admin.event.check-deletable', $event->id_event) }}";
        const deleteUrl = "{{ route('admin.events.destroy', $event->id_event) }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.getElementById('btnHapusEvent').addEventListener('click', function () {
            fetch(checkUrl, { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => {
                    if (!data.can_delete) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Event Tidak Bisa Dihapus',
                            text: data.reason,
                            confirmButtonText: 'Mengerti',
                            background: '#3A345B',
                            color: '#F3C8DD',
                            confirmButtonColor: '#D183A9',
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Hapus Event?',
                        html: `Event <strong>${eventName}</strong> akan dihapus.`,
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Hapus',
                            cancelButtonText: 'Batal',
                            background: '#3A345B',
                            color: '#F3C8DD',
                            confirmButtonColor: '#ef5350',
                            cancelButtonColor: '#71557A',
                        }).then(result => {
                            if (result.isConfirmed) {
                                fetch(deleteUrl, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify({}),
                                })
                                .then(r => r.json())
                                .then(res => {
                                    if (res.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil!',
                                            text: `Event "${res.event_name}" berhasil dihapus.`,
                                            confirmButtonText: 'OK',
                                            background: '#3A345B',
                                            color: '#F3C8DD',
                                            confirmButtonColor: '#D183A9',
                                        }).then(() => {
                                            window.location.href = '/admin/dashboard';
                                        });
                                    } else {
                                        Swal.fire({ icon: 'error', title: 'Gagal', text: res.message });
                                    }
                                });
                            }
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menghubungi server.' });
                });
        });
    </script>
</body>
</html>
