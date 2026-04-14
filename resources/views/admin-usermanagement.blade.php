<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tixora - User Management</title>
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

        /* ── Topbar ── */
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
            z-index: 900;
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
            overflow: hidden;
            text-decoration: none;
        }
        .topbar .profile:hover { transform: scale(1.05); }

        /* ── Sidebar ── */
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
        .sidebar:hover .sidebar-text { opacity: 1; }

        /* ── Main ── */
        .main-wrapper {
            margin-left: var(--sidebar-width-collapsed);
            padding: calc(var(--topbar-height) + 40px) 40px 60px;
            min-height: 100vh;
        }

        /* ── Page Header ── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 35px;
            flex-wrap: wrap;
            gap: 16px;
        }
        .page-title {
            font-size: 1.9rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .page-title i { color: var(--middle-purple); }

        /* ── Stats Row ── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 35px;
        }
        .stat-card {
            background: rgba(113, 85, 122, 0.25);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(243, 200, 221, 0.15);
            border-radius: 18px;
            padding: 24px 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.25);
        }
        .stat-card .stat-icon {
            font-size: 1.8rem;
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 6px;
        }
        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            line-height: 1;
        }
        .stat-card .stat-label {
            font-size: 0.82rem;
            opacity: 0.65;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ── Filter / Search Bar ── */
        .toolbar {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        .search-field {
            flex: 1;
            min-width: 220px;
            display: flex;
            align-items: center;
            background: rgba(58, 52, 91, 0.5);
            border: 1px solid rgba(243, 200, 221, 0.2);
            border-radius: 10px;
            padding: 10px 16px;
            gap: 10px;
            transition: border-color 0.3s;
        }
        .search-field:focus-within { border-color: var(--middle-purple); }
        .search-field input {
            background: transparent;
            border: none;
            outline: none;
            color: #fff;
            font-size: 0.95rem;
            width: 100%;
        }
        .search-field input::placeholder { color: rgba(243,200,221,0.4); }
        .filter-select {
            background: rgba(58, 52, 91, 0.5);
            border: 1px solid rgba(243, 200, 221, 0.2);
            border-radius: 10px;
            color: var(--queen-pink);
            padding: 10px 16px;
            outline: none;
            cursor: pointer;
            font-size: 0.9rem;
            transition: border-color 0.3s;
        }
        .filter-select:focus { border-color: var(--middle-purple); }

        /* ── User Table Card ── */
        .table-card {
            background: rgba(113, 85, 122, 0.22);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(243, 200, 221, 0.14);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        }
        .table-card table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-card thead tr {
            background: rgba(58, 52, 91, 0.5);
            border-bottom: 1px solid rgba(243, 200, 221, 0.12);
        }
        .table-card th {
            padding: 16px 20px;
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--middle-purple);
            text-align: left;
            white-space: nowrap;
        }
        .table-card tbody tr {
            border-bottom: 1px solid rgba(243, 200, 221, 0.06);
            transition: background 0.2s;
        }
        .table-card tbody tr:last-child { border-bottom: none; }
        .table-card tbody tr:hover { background: rgba(209, 131, 169, 0.07); }
        .table-card td {
            padding: 16px 20px;
            font-size: 0.92rem;
            vertical-align: middle;
        }

        /* ── Avatar ── */
        .user-avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--middle-purple), var(--old-lavender));
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            text-transform: uppercase;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }
        .user-cell { display: flex; align-items: center; gap: 14px; }
        .user-name { font-weight: 600; color: #fff; }
        .user-email { font-size: 0.8rem; opacity: 0.6; margin-top: 2px; }

        /* ── Role Badge ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .badge-buyer {
            background: rgba(99, 179, 237, 0.15);
            color: #63b3ed;
            border: 1px solid rgba(99, 179, 237, 0.25);
        }
        .badge-organizer {
            background: rgba(236, 201, 75, 0.15);
            color: #ecc94b;
            border: 1px solid rgba(236, 201, 75, 0.25);
        }
        .badge-admin {
            background: rgba(209, 131, 169, 0.15);
            color: var(--middle-purple);
            border: 1px solid rgba(209, 131, 169, 0.3);
        }

        /* ── Status Badge ── */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
        }
        .status-pill .dot {
            width: 7px; height: 7px;
            border-radius: 50%;
        }
        .status-active {
            background: rgba(132, 216, 165, 0.12);
            color: #84d8a5;
            border: 1px solid rgba(132, 216, 165, 0.25);
        }
        .status-active .dot { background: #84d8a5; box-shadow: 0 0 6px #84d8a5; animation: pulse 2s infinite; }
        .status-inactive {
            background: rgba(239, 83, 80, 0.12);
            color: #ef5350;
            border: 1px solid rgba(239, 83, 80, 0.25);
        }
        .status-inactive .dot { background: #ef5350; }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        /* ── Action Buttons ── */
        .action-group { display: flex; align-items: center; gap: 8px; }
        .btn-icon {
            width: 36px; height: 36px;
            border-radius: 9px;
            border: none;
            display: inline-flex; align-items: center; justify-content: center;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.25s;
            text-decoration: none;
        }
        .btn-icon-warning {
            background: rgba(236, 201, 75, 0.12);
            color: #ecc94b;
            border: 1px solid rgba(236, 201, 75, 0.25);
        }
        .btn-icon-warning:hover {
            background: rgba(236, 201, 75, 0.25);
            transform: scale(1.1);
            box-shadow: 0 0 12px rgba(236, 201, 75, 0.3);
        }
        .btn-icon-danger {
            background: rgba(239, 83, 80, 0.12);
            color: #ef5350;
            border: 1px solid rgba(239, 83, 80, 0.25);
        }
        .btn-icon-danger:hover {
            background: rgba(239, 83, 80, 0.25);
            transform: scale(1.1);
            box-shadow: 0 0 12px rgba(239, 83, 80, 0.3);
        }
        .btn-icon-info {
            background: rgba(99, 179, 237, 0.12);
            color: #63b3ed;
            border: 1px solid rgba(99, 179, 237, 0.25);
        }
        .btn-icon-info:hover {
            background: rgba(99, 179, 237, 0.25);
            transform: scale(1.1);
        }

        /* ── Empty State ── */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            opacity: 0.5;
        }
        .empty-state i { font-size: 4rem; display: block; margin-bottom: 16px; }
        .empty-state p { font-size: 1rem; }

        /* ── Pagination ── */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-top: 1px solid rgba(243, 200, 221, 0.08);
            flex-wrap: wrap;
            gap: 12px;
        }
        .pagination-info { font-size: 0.85rem; opacity: 0.6; }
        .pagination-btns { display: flex; gap: 6px; }
        .pg-btn {
            width: 34px; height: 34px;
            border-radius: 8px;
            border: 1px solid rgba(243, 200, 221, 0.15);
            background: rgba(58, 52, 91, 0.4);
            color: var(--queen-pink);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        .pg-btn:hover, .pg-btn.active {
            background: var(--middle-purple);
            color: var(--jacarta);
            border-color: var(--middle-purple);
        }

        /* ── Modal Detail ── */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .modal-overlay.open { display: flex; animation: fadeInOverlay 0.25s ease; }
        @keyframes fadeInOverlay {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .modal-box {
            background: linear-gradient(145deg, rgba(58, 52, 91, 0.95), rgba(75, 21, 53, 0.95));
            border: 1px solid rgba(243, 200, 221, 0.2);
            border-radius: 24px;
            padding: 35px;
            width: 100%;
            max-width: 520px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5);
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }
        .modal-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #fff;
            display: flex; align-items: center; gap: 10px;
        }
        .modal-title i { color: var(--middle-purple); }
        .modal-close {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(243,200,221,0.15);
            color: var(--queen-pink);
            width: 34px; height: 34px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            font-size: 1.1rem;
            transition: all 0.2s;
        }
        .modal-close:hover { background: rgba(239,83,80,0.2); color: #ef5350; }
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .detail-item { display: flex; flex-direction: column; gap: 4px; }
        .detail-item .label { font-size: 0.75rem; opacity: 0.55; text-transform: uppercase; letter-spacing: 0.5px; }
        .detail-item .value { font-size: 0.95rem; color: #fff; font-weight: 500; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .main-wrapper { margin-left: 0; padding: 80px 16px 40px; }
            .stats-row { grid-template-columns: 1fr 1fr; }
            .table-card th:nth-child(3),
            .table-card td:nth-child(3),
            .table-card th:nth-child(5),
            .table-card td:nth-child(5) { display: none; }
            .detail-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- ── Topbar ── -->
    <header class="topbar">
        <div class="logo">TIXORA</div>
        <div style="display: flex; align-items: center; gap: 12px;">
            <div class="search-field" style="min-width: 200px;">
                <i class="ph ph-magnifying-glass" style="color: var(--queen-pink); font-size: 1rem;"></i>
                <input type="text" placeholder="Cari pengguna..." id="globalSearch">
            </div>
            <a href="{{ route('profile.edit') }}" class="profile" title="My Profile">
                @php
                    $displayName = session('login_admin.name') ?? (auth()->check() ? auth()->user()->nama_lengkap : 'Admin');
                @endphp
                @if(auth()->check() && auth()->user()->photo_profile)
                    <img src="{{ asset(auth()->user()->photo_profile) }}" alt="Profile" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                @else
                    {{ strtoupper(substr($displayName, 0, 1)) }}
                @endif
            </a>
        </div>
    </header>

    <!-- ── Sidebar ── -->
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
                        @php $badgeCount = isset($pageUnreadCount) ? $pageUnreadCount : ($unreadCount ?? 0); @endphp
                        @if($badgeCount > 0)
                        <span style="position: absolute; right: 15px; margin-top: -3px; background: #ef4444; color: white; font-size: 0.65rem; font-weight: bold; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border-radius: 50%; box-shadow: 0 0 10px rgba(239, 68, 68, 0.6);">{{ $badgeCount }}</span>
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

    <!-- ── Main Content ── -->
    <main class="main-wrapper">

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="ph ph-users-three"></i>
                User Management
            </h1>
            <div style="font-size: 0.85rem; opacity: 0.5;">
                Kelola dan pantau semua akun pengguna Tixora
            </div>
        </div>

        @if(session('success'))
            <div style="background: rgba(132, 216, 165, 0.15); border: 1px solid rgba(132, 216, 165, 0.3); color: #84d8a5; padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                <i class="ph ph-check-circle" style="font-size: 1.2rem;"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: rgba(239, 83, 80, 0.15); border: 1px solid rgba(239, 83, 80, 0.3); color: #ef5350; padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                <i class="ph ph-warning-circle" style="font-size: 1.2rem;"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Stats Row -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(99,179,237,0.12); color: #63b3ed;">
                    <i class="ph ph-users"></i>
                </div>
                <div class="stat-value" id="statTotal">{{ $users->count() ?? '—' }}</div>
                <div class="stat-label">Total Pengguna</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(132,216,165,0.12); color: #84d8a5;">
                    <i class="ph ph-user-check"></i>
                </div>
                <div class="stat-value" style="color: #84d8a5;" id="statActive">
                    {{ $users->where('status', 'active')->count() ?? '—' }}
                </div>
                <div class="stat-label">Akun Aktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(239,83,80,0.12); color: #ef5350;">
                    <i class="ph ph-user-minus"></i>
                </div>
                <div class="stat-value" style="color: #ef5350;" id="statInactive">
                    {{ $users->where('status', 'inactive')->count() ?? '—' }}
                </div>
                <div class="stat-label">Akun Nonaktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(236,201,75,0.12); color: #ecc94b;">
                    <i class="ph ph-star"></i>
                </div>
                <div class="stat-value" style="color: #ecc94b;" id="statOrganizer">
                    {{ $users->where('role', 'organizer')->count() ?? '—' }}
                </div>
                <div class="stat-label">Organizer</div>
            </div>
        </div>

        <!-- Toolbar -->
        <div class="toolbar">
            <div class="search-field">
                <i class="ph ph-magnifying-glass" style="color: rgba(243,200,221,0.5);"></i>
                <input type="text" placeholder="Cari nama, email..." id="tableSearch">
            </div>
            <select class="filter-select" id="filterRole">
                <option value="">Semua Role</option>
                <option value="buyer">Buyer</option>
                <option value="organizer">Organizer</option>
                <option value="admin">Admin</option>
            </select>
            <select class="filter-select" id="filterStatus">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Nonaktif</option>
            </select>
        </div>

        <!-- Users Table -->
        <div class="table-card">
            <table id="usersTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pengguna</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Bergabung</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="usersBody">
                    @forelse($users as $index => $user)
                    <tr
                        data-name="{{ strtolower($user->nama_lengkap ?? '') }}"
                        data-email="{{ strtolower($user->email ?? '') }}"
                        data-role="{{ $user->role ?? 'buyer' }}"
                        data-status="{{ $user->status ?? 'active' }}"
                    >
                        <!-- No -->
                        <td style="opacity: 0.45; font-size: 0.85rem;">{{ $index + 1 }}</td>

                        <!-- User Info -->
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">
                                    @if(!empty($user->photo_profile))
                                        <img src="{{ asset($user->photo_profile) }}" alt="{{ $user->nama_lengkap }}" style="width:100%;height:100%;object-fit:cover;">
                                    @else
                                        {{ strtoupper(substr($user->nama_lengkap ?? 'U', 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="user-name">{{ $user->nama_lengkap ?? 'Tanpa Nama' }}</div>
                                    <div class="user-email">{{ $user->email ?? '-' }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Role -->
                        <td>
                            @php $role = $user->role ?? 'buyer'; @endphp
                            @if($role === 'organizer')
                                <span class="badge badge-organizer"><i class="ph ph-star-four"></i> Organizer</span>
                            @elseif($role === 'admin')
                                <span class="badge badge-admin"><i class="ph ph-shield-check"></i> Admin</span>
                            @else
                                <span class="badge badge-buyer"><i class="ph ph-user"></i> Buyer</span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td>
                            @php $status = $user->status ?? 'active'; @endphp
                            @if($status === 'active')
                                <span class="status-pill status-active">
                                    <span class="dot"></span> Aktif
                                </span>
                            @else
                                <span class="status-pill status-inactive">
                                    <span class="dot"></span> Nonaktif
                                </span>
                            @endif
                        </td>

                        <!-- Join Date -->
                        <td style="font-size: 0.85rem; opacity: 0.7;">
                            {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d M Y') : '-' }}
                        </td>

                        <!-- Actions -->
                        <td>
                            <div class="action-group" style="justify-content: center;">
                                <!-- View Detail -->
                                <button
                                    class="btn-icon btn-icon-info"
                                    title="Lihat Detail"
                                    onclick="openDetail({
                                        id: {{ $user->id_user ?? 'null' }},
                                        name: @json($user->nama_lengkap ?? 'Tanpa Nama'),
                                        email: @json($user->email ?? '-'),
                                        role: @json($user->role ?? 'buyer'),
                                        status: @json($user->status ?? 'active'),
                                        phone: @json($user->no_telp ?? '-'),
                                        joined: @json($user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d F Y') : '-')
                                    })"
                                >
                                    <i class="ph ph-eye"></i>
                                </button>

                                <!-- Toggle Active / Inactive -->
                                @if(($user->status ?? 'active') === 'active')
                <form action="{{ route('admin.users.deactivate', $user->id_user) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button
                                        type="button"
                                        class="btn-icon btn-icon-warning"
                                        title="Nonaktifkan Akun"
                                        onclick="confirmDeactivate(this.closest('form'), @json($user->nama_lengkap ?? 'user ini'))"
                                    >
                                        <i class="ph ph-user-minus"></i>
                                    </button>
                                </form>
                                @else
                <form action="{{ route('admin.users.activate', $user->id_user) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button
                                        type="button"
                                        class="btn-icon"
                                        style="background: rgba(132,216,165,0.12); color: #84d8a5; border: 1px solid rgba(132,216,165,0.25);"
                                        title="Aktifkan Akun"
                                        onclick="confirmActivate(this.closest('form'), @json($user->nama_lengkap ?? 'user ini'))"
                                    >
                                        <i class="ph ph-user-check"></i>
                                    </button>
                                </form>
                                @endif

                                <!-- Delete -->
                                @if(($user->role ?? 'buyer') !== 'admin')
                <form action="{{ route('admin.users.destroy', $user->id_user) }}" method="POST" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="button"
                                        class="btn-icon btn-icon-danger"
                                        title="Hapus Akun"
                                        onclick="confirmDelete(this.closest('form'), @json($user->nama_lengkap ?? 'user ini'))"
                                    >
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="ph ph-users"></i>
                                <p>Belum ada pengguna terdaftar.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination">
                <div class="pagination-info" id="paginationInfo">Menampilkan semua pengguna</div>
                <div class="pagination-btns" id="paginationBtns"></div>
            </div>
        </div>
    </main>

    <!-- ── Modal Detail User ── -->
    <div class="modal-overlay" id="detailModal">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="ph ph-user-circle"></i>
                    <span id="modalUserName">Detail Pengguna</span>
                </div>
                <button class="modal-close" onclick="closeDetail()"><i class="ph ph-x"></i></button>
            </div>

            <div style="display: flex; align-items: center; gap: 18px; margin-bottom: 28px; padding-bottom: 24px; border-bottom: 1px solid rgba(243,200,221,0.1);">
                <div class="user-avatar" style="width: 60px; height: 60px; font-size: 1.5rem;" id="modalAvatar">U</div>
                <div>
                    <div style="font-size: 1.15rem; font-weight: 700; color: #fff;" id="modalNameBig">—</div>
                    <div style="font-size: 0.85rem; opacity: 0.6;" id="modalEmailBig">—</div>
                </div>
            </div>

            <div class="detail-grid">
                <div class="detail-item">
                    <span class="label">Role</span>
                    <span class="value" id="modalRole">—</span>
                </div>
                <div class="detail-item">
                    <span class="label">Status</span>
                    <span class="value" id="modalStatus">—</span>
                </div>
                <div class="detail-item">
                    <span class="label">No. Telepon</span>
                    <span class="value" id="modalPhone">—</span>
                </div>
                <div class="detail-item">
                    <span class="label">Bergabung</span>
                    <span class="value" id="modalJoined">—</span>
                </div>
                <div class="detail-item" style="grid-column: span 2;">
                    <span class="label">Email</span>
                    <span class="value" id="modalEmail">—</span>
                </div>
            </div>

            <div style="margin-top: 28px; display: flex; justify-content: flex-end;">
                <button onclick="closeDetail()" style="background: rgba(209,131,169,0.15); border: 1px solid var(--middle-purple); color: var(--middle-purple); padding: 10px 24px; border-radius: 10px; cursor: pointer; font-size: 0.9rem; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='rgba(209,131,169,0.3)'" onmouseout="this.style.background='rgba(209,131,169,0.15)'">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        // ── Filter & Search Logic ──
        const tableSearch = document.getElementById('tableSearch');
        const globalSearch = document.getElementById('globalSearch');
        const filterRole   = document.getElementById('filterRole');
        const filterStatus = document.getElementById('filterStatus');
        const rows = Array.from(document.querySelectorAll('#usersBody tr[data-name]'));
        const ROWS_PER_PAGE = 10;
        let currentPage = 1;

        function applyFilter() {
            const q      = (tableSearch.value || globalSearch.value || '').toLowerCase().trim();
            const role   = filterRole.value.toLowerCase();
            const status = filterStatus.value.toLowerCase();

            const visible = rows.filter(row => {
                const name  = row.dataset.name || '';
                const email = row.dataset.email || '';
                const r     = (row.dataset.role || '').toLowerCase();
                const s     = (row.dataset.status || '').toLowerCase();

                const matchQ      = !q      || name.includes(q) || email.includes(q);
                const matchRole   = !role   || r === role;
                const matchStatus = !status || s === status;
                return matchQ && matchRole && matchStatus;
            });

            rows.forEach(r => r.style.display = 'none');

            const total = visible.length;
            const totalPages = Math.max(1, Math.ceil(total / ROWS_PER_PAGE));
            currentPage = Math.min(currentPage, totalPages);

            const start = (currentPage - 1) * ROWS_PER_PAGE;
            const end   = start + ROWS_PER_PAGE;
            visible.slice(start, end).forEach(r => r.style.display = '');

            document.getElementById('paginationInfo').textContent =
                total === 0
                    ? 'Tidak ada pengguna ditemukan'
                    : `Menampilkan ${start + 1}–${Math.min(end, total)} dari ${total} pengguna`;

            renderPagination(total, totalPages);
        }

        function renderPagination(total, totalPages) {
            const container = document.getElementById('paginationBtns');
            container.innerHTML = '';

            if (totalPages <= 1) return;

            const prev = document.createElement('button');
            prev.className = 'pg-btn';
            prev.innerHTML = '<i class="ph ph-caret-left"></i>';
            prev.disabled = currentPage === 1;
            prev.onclick = () => { currentPage--; applyFilter(); };
            container.appendChild(prev);

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.className = 'pg-btn' + (i === currentPage ? ' active' : '');
                btn.textContent = i;
                btn.onclick = () => { currentPage = i; applyFilter(); };
                container.appendChild(btn);
            }

            const next = document.createElement('button');
            next.className = 'pg-btn';
            next.innerHTML = '<i class="ph ph-caret-right"></i>';
            next.disabled = currentPage === totalPages;
            next.onclick = () => { currentPage++; applyFilter(); };
            container.appendChild(next);
        }

        tableSearch.addEventListener('input', () => { currentPage = 1; applyFilter(); });
        globalSearch.addEventListener('input', () => { currentPage = 1; applyFilter(); });
        filterRole.addEventListener('change', () => { currentPage = 1; applyFilter(); });
        filterStatus.addEventListener('change', () => { currentPage = 1; applyFilter(); });
        applyFilter();

        // ── Swal Confirm Helpers ──
        const swalTheme = {
            background: '#3A345B',
            color: '#F3C8DD',
        };

        function confirmDeactivate(form, name) {
            Swal.fire({
                icon: 'warning',
                title: 'Nonaktifkan Akun?',
                html: `Akun <strong>${name}</strong> akan dinonaktifkan dan tidak bisa login.`,
                showCancelButton: true,
                confirmButtonText: 'Ya, Nonaktifkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#ecc94b',
                cancelButtonColor: '#71557A',
                ...swalTheme,
            }).then(result => { if (result.isConfirmed) form.submit(); });
        }

        function confirmActivate(form, name) {
            Swal.fire({
                icon: 'question',
                title: 'Aktifkan Akun?',
                html: `Akun <strong>${name}</strong> akan diaktifkan kembali.`,
                showCancelButton: true,
                confirmButtonText: 'Ya, Aktifkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#84d8a5',
                cancelButtonColor: '#71557A',
                ...swalTheme,
            }).then(result => { if (result.isConfirmed) form.submit(); });
        }

        function confirmDelete(form, name) {
            Swal.fire({
                icon: 'error',
                title: 'Hapus Akun Permanen?',
                html: `Akun <strong>${name}</strong> akan dihapus secara permanen dan tidak dapat dipulihkan.`,
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#ef5350',
                cancelButtonColor: '#71557A',
                ...swalTheme,
            }).then(result => { if (result.isConfirmed) form.submit(); });
        }

        // ── Detail Modal ──
        function openDetail(user) {
            document.getElementById('modalUserName').textContent = user.name;
            document.getElementById('modalAvatar').textContent = user.name ? user.name[0].toUpperCase() : 'U';
            document.getElementById('modalNameBig').textContent = user.name;
            document.getElementById('modalEmailBig').textContent = user.email;
            document.getElementById('modalEmail').textContent = user.email;
            document.getElementById('modalRole').textContent = user.role.charAt(0).toUpperCase() + user.role.slice(1);
            document.getElementById('modalStatus').textContent = user.status === 'active' ? 'Aktif ✓' : 'Nonaktif ✗';
            document.getElementById('modalPhone').textContent = user.phone || '-';
            document.getElementById('modalJoined').textContent = user.joined;

            document.getElementById('detailModal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeDetail() {
            document.getElementById('detailModal').classList.remove('open');
            document.body.style.overflow = '';
        }

        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) closeDetail();
        });
    </script>
</body>
</html>
