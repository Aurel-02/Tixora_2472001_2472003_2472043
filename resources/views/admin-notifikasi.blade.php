<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tixora - Notifikasi Admin</title>
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

        /* Essential styles copied from admindashboard */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
        body { background: linear-gradient(135deg, var(--jacarta), var(--brown-chocolate)); color: var(--queen-pink); min-height: 100vh; background-attachment: fixed; }
        
        .topbar { position: fixed; top: 0; left: var(--sidebar-width-collapsed); right: 0; height: var(--topbar-height); background: rgba(58, 52, 91, 0.6); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(243, 200, 221, 0.1); display: flex; justify-content: space-between; align-items: center; padding: 0 40px; z-index: 900; }
        .topbar .logo { font-size: 1.8rem; font-weight: 700; letter-spacing: 2px; color: var(--queen-pink); text-shadow: 0 0 10px rgba(243, 200, 221, 0.5); text-transform: uppercase; }
        
        .sidebar { position: fixed; top: 0; left: 0; width: var(--sidebar-width-collapsed); height: 100vh; background: rgba(113, 85, 122, 0.4); backdrop-filter: blur(15px); border-right: 1px solid rgba(243, 200, 221, 0.15); z-index: 1000; transition: width 0.3s ease; overflow: hidden; display: flex; flex-direction: column; padding-top: var(--topbar-height); }
        .sidebar:hover { width: var(--sidebar-width-expanded); }
        .sidebar-menu { list-style: none; margin-top: 20px; display: flex; flex-direction: column; gap: 10px; }
        .sidebar-item { display: flex; align-items: center; padding: 15px 22px; color: var(--queen-pink); text-decoration: none; transition: background 0.2s; border-left: 3px solid transparent; cursor: pointer; white-space: nowrap; }
        .sidebar-item:hover, .sidebar-item.active { background: rgba(209, 131, 169, 0.2); border-left: 3px solid var(--middle-purple); }
        .sidebar-icon { font-size: 1.4rem; min-width: 25px; margin-right: 20px; }
        .sidebar-text { font-size: 1rem; font-weight: 500; opacity: 0; transition: opacity 0.3s ease; }
        .sidebar:hover .sidebar-text { opacity: 1; }

        .main-wrapper { margin-left: var(--sidebar-width-collapsed); padding: calc(var(--topbar-height) + 40px) 40px 40px; min-height: 100vh; transition: margin-left 0.3s; }
        .header-title { font-size: 1.8rem; font-weight: 700; color: #fff; margin-bottom: 30px; display: flex; align-items: center; gap: 15px; }

        .notification-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(243, 200, 221, 0.1);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            backdrop-filter: blur(10px);
            transition: transform 0.3s;
        }
        .notification-card:hover { transform: scale(1.01); background: rgba(255, 255, 255, 0.05); }
        
        .notif-info { flex-grow: 1; }
        .notif-title { font-size: 1.2rem; font-weight: 600; color: #fff; margin-bottom: 5px; }
        .notif-user { font-size: 0.95rem; color: var(--middle-purple); font-weight: 500; }
        .notif-date { font-size: 0.85rem; color: var(--queen-pink); opacity: 0.6; margin-top: 5px; }

        .action-btns { display: flex; gap: 12px; }
        .btn-approve { background: #4caf50; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-reject { background: #f44336; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-approve:hover { background: #43a047; box-shadow: 0 0 15px rgba(76, 175, 80, 0.4); }
        .btn-reject:hover { background: #e53935; box-shadow: 0 0 15px rgba(244, 67, 54, 0.4); }

        .empty-state { text-align: center; padding: 100px 0; opacity: 0.5; }
        .empty-state i { font-size: 4rem; margin-bottom: 20px; }

        .category-tabs {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 50px;
            padding: 10px 20px 30px;
            border-bottom: 1px solid rgba(243, 200, 221, 0.15);
            margin-bottom: 40px;
        }

        .tab-btn {
            background: transparent;
            border: none;
            color: var(--queen-pink);
            font-size: 1.2rem;
            font-weight: 500;
            cursor: pointer;
            padding: 10px 5px;
            position: relative;
            opacity: 0.6;
            transition: all 0.3s ease;
        }

        .tab-btn:hover {
            opacity: 0.9;
        }

        .tab-btn.active {
            opacity: 1;
            font-weight: 700;
            color: #fff;
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--middle-purple);
            border-radius: 3px;
            box-shadow: 0 0 12px var(--middle-purple);
        }

        .tab-content {
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .main-wrapper { margin-left: 0; padding: 20px; }
            .notification-card { flex-direction: column; text-align: center; gap: 20px; }
            .category-tabs { gap: 20px; }
            .tab-btn { font-size: 1rem; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="logo">TIXORA</div>
        <div style="display: flex; align-items: center; gap: 15px;">
            <a href="{{ route('profile.edit') }}" class="profile" title="My Profile" style="text-decoration: none; width: 42px; height: 42px; border-radius: 50%; background: var(--middle-purple); color: var(--jacarta); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem; cursor: pointer; box-shadow: 0 0 10px rgba(209, 131, 169, 0.4); transition: transform 0.3s; text-transform: uppercase; overflow: hidden;">
                @php
                    $displayName = session('login_admin.name') ?? (auth()->check() ? auth()->user()->nama_lengkap : 'Admin');
                @endphp
                @if(auth()->check() && auth()->user()->photo_profile)
                    <img src="{{ asset(auth()->user()->photo_profile) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                @else
                    {{ strtoupper(substr($displayName, 0, 1)) }}
                @endif
            </a>
        </div>
    </header>

    <aside class="sidebar">
        <div class="sidebar-content" style="display: flex; flex-direction: column; height: 100%;">
            <ul class="sidebar-menu" style="flex-grow: 1;">
                <li><a href="{{ url('/admin/dashboard') }}" class="sidebar-item"><i class="ph ph-house sidebar-icon"></i><span class="sidebar-text">Home</span></a></li>
                <li><a href="{{ route('admin.revenue') }}" class="sidebar-item"><i class="ph ph-currency-dollar sidebar-icon"></i><span class="sidebar-text">Revenue</span></a></li>
                <li><a href="{{ route('admin.events.create') }}" class="sidebar-item"><i class="ph ph-plus-circle sidebar-icon"></i><span class="sidebar-text">Tambah Event</span></a></li>
                <li><a href="{{ route('admin.statistik') }}" class="sidebar-item"><i class="ph ph-chart-bar sidebar-icon"></i><span class="sidebar-text">Analitik Penjualan</span></a></li>
                <li>
                    <a href="{{ route('admin.notifications') }}" class="sidebar-item active" style="position: relative;">
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
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-item" style="background: transparent; border: none; color: var(--queen-pink); width: 100%; text-align: left; cursor: pointer;"><i class="ph ph-sign-out sidebar-icon"></i><span class="sidebar-text">Logout</span></button>
                </form>
            </div>
        </div>
    </aside>

    <main class="main-wrapper">
        <div class="header-title" style="margin-bottom: 20px;">
            <i class="ph ph-bell-ringing" style="color: var(--middle-purple);"></i>
            Pusat Notifikasi Admin
            @if(isset($pageUnreadCount) && $pageUnreadCount > 0)
                <span style="background: #ef4444; font-family: 'Outfit'; color: white; font-size: 0.9rem; padding: 4px 12px; border-radius: 20px; box-shadow: 0 0 10px rgba(239, 68, 68, 0.6); margin-left: 10px;">{{ $pageUnreadCount }} Baru</span>
            @endif
        </div>

        <div class="category-tabs">
            <button class="tab-btn active" onclick="switchTab('pembelian', this)">Notifikasi Pembelian</button>
            <button class="tab-btn" onclick="switchTab('permohonan', this)">Permohonan Organizer</button>
        </div>

        @if(session('success'))
            <div style="background: rgba(76, 175, 80, 0.2); border: 1px solid #4caf50; color: #fff; padding: 15px; border-radius: 10px; margin-bottom: 25px;">{{ session('success') }}</div>
        @endif

        <!-- Tab Notifikasi Pembelian -->
        <div id="tab-pembelian" class="tab-content">
            <div class="notification-list">
                @forelse($purchases as $purchase)
                    <div class="notification-card">
                        <div style="display: flex; align-items: center; gap: 20px; width: 100%;">
                            <div style="width: 55px; height: 55px; border-radius: 12px; background: rgba(209, 131, 169, 0.15); border: 1px solid rgba(209, 131, 169, 0.3); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                @if($purchase->poster)
                                    <img src="{{ asset($purchase->poster) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="ph ph-shopping-cart-simple" style="font-size: 1.8rem; color: var(--middle-purple);"></i>
                                @endif
                            </div>
                            <div class="notif-info">
                                <div class="notif-title">
                                    <span style="color: var(--middle-purple); font-weight: 700;">{{ $purchase->buyer_name }}</span> 
                                    berhasil membeli <span style="color: #fff;">{{ $purchase->total_tickets }} tiket</span>
                                </div>
                                <div class="notif-user">Event: {{ $purchase->nama_event }}</div>
                                <div class="notif-date"><i class="ph ph-clock" style="vertical-align: middle;"></i> {{ date('d M Y, H:i', strtotime($purchase->tanggal_transaksi)) }}</div>
                            </div>
                            <div style="font-size: 1.5rem; opacity: 0.3;">
                                <i class="ph ph-check-circle"></i>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="ph ph-shopping-cart"></i>
                        <p>Belum ada aktivitas pembelian tiket terdeteksi.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Tab Permohonan Organizer -->
        <div id="tab-permohonan" class="tab-content" style="display: none;">
            <div class="notification-list">
                @forelse($requests as $req)
                    <div class="notification-card">
                        <div class="notif-info">
                            <div class="notif-title">Permohonan Pengelolaan: {{ $req->nama_event }}</div>
                            <div class="notif-user">Diajukan oleh: <span style="color: var(--middle-purple);">{{ $req->organizer_name }}</span></div>
                            <div class="notif-date"><i class="ph ph-clock" style="vertical-align: middle;"></i> {{ date('d M Y, H:i', strtotime($req->created_at)) }}</div>
                        </div>
                        <div class="action-btns">
                            <form action="{{ route('admin.permohonan.approve', $req->id_permohonan) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-approve">Setujui</button>
                            </form>
                            <form action="{{ route('admin.permohonan.reject', $req->id_permohonan) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-reject">Tolak</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="ph ph-users-three"></i>
                        <p>Tidak ada permohonan pendaftaran organizer saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    <script>
        function switchTab(tabName, element) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.style.display = 'none';
            });
            
            // Show selected content
            document.getElementById('tab-' + tabName).style.display = 'block';
            
            // Update active button
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            element.classList.add('active');
        }
    </script>
</body>
</html>
