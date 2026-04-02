<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tixora - Notifications</title>
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
        }
        
        .topbar .profile:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(209, 131, 169, 0.6);
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
            padding-top: var(--topbar-height);
            min-height: calc(100vh - var(--topbar-height));
            display: flex;
            flex-direction: column;
            padding-bottom: 50px;
        }

        .section-header {
            margin: calc(var(--topbar-height) - 50px) auto 0;
            width: 100%;
            max-width: 100%;
            padding: 10px 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            z-index: 800;
        }

        .section-header .section-title {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 10px;
            padding: 25px 18px;
            backdrop-filter: blur(6px);
            width: 95%;
            max-width: 1500px;
            font-size: 2.5rem;
            color: #ffffff;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .notifications-container {
            width: 95%;
            max-width: 1500px;
            margin: 30px auto;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .notif-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 24px;
            display: flex;
            align-items: flex-start;
            gap: 20px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .notif-card:hover {
            transform: translateY(-3px);
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .notif-card.unread::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 5px;
            background: #a8e6cf;
        }

        .notif-icon {
            width: 55px;
            height: 55px;
            border-radius: 14px;
            background: rgba(168, 230, 207, 0.15);
            color: #a8e6cf;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            flex-shrink: 0;
            border: 1px solid rgba(168, 230, 207, 0.3);
        }

        .notif-icon.new-user {
            background: rgba(243, 200, 221, 0.15);
            color: var(--queen-pink);
            border-color: rgba(243, 200, 221, 0.3);
        }

        .notif-content {
            flex-grow: 1;
        }

        .notif-title {
            font-size: 1.15rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .notif-badge {
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 20px;
            background: rgba(168, 230, 207, 0.2);
            color: #a8e6cf;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .notif-desc {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.8;
            line-height: 1.5;
        }

        .notif-desc strong {
            color: #fff;
            font-weight: 600;
        }

        .notif-time {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            border: 1px dashed rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>

    <header class="topbar">
        <div class="logo">TIXORA</div>
        <div style="display: flex; align-items: center; gap: 12px;">
            <div class="profile" title="My Profile">U</div>
        </div>
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
                    <a href="{{ route('organizer.events.create') }}" class="sidebar-item">
                        <i class="ph ph-plus-circle sidebar-icon"></i>
                        <span class="sidebar-text">Tambah Event</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('organizer.notifications') }}" class="sidebar-item active">
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
        <div class="section-header">
            <span class="section-title"><i class="ph ph-bell-ringing"></i> Notifikasi Kamu</span>
        </div>

        <div class="notifications-container">
            
            <!-- Dummy Notification 1 -->
            <div class="notif-card unread">
                <div class="notif-icon">
                    <i class="ph ph-ticket"></i>
                </div>
                <div class="notif-content">
                    <div class="notif-title">
                        Tiket Berhasil Terjual <span class="notif-badge">Baru</span>
                    </div>
                    <div class="notif-desc">
                        User <strong>Budi Santoso</strong> baru saja membeli <strong>2 Tiket VIP</strong> untuk event <strong>Coldplay Live in Jakarta</strong>.
                    </div>
                    <div class="notif-time"><i class="ph ph-clock"></i> 5 menit yang lalu</div>
                </div>
            </div>

            <!-- Dummy Notification 2 -->
            <div class="notif-card unread">
                <div class="notif-icon">
                    <i class="ph ph-ticket"></i>
                </div>
                <div class="notif-content">
                    <div class="notif-title">
                        Tiket Berhasil Terjual <span class="notif-badge">Baru</span>
                    </div>
                    <div class="notif-desc">
                        User <strong>Alina</strong> baru saja membeli <strong>1 Tiket REGULER</strong> untuk event <strong>Pestapora 2026</strong>.
                    </div>
                    <div class="notif-time"><i class="ph ph-clock"></i> 1 jam yang lalu</div>
                </div>
            </div>

            <!-- Dummy Notification 3 -->
            <div class="notif-card">
                <div class="notif-icon new-user">
                    <i class="ph ph-star"></i>
                </div>
                <div class="notif-content">
                    <div class="notif-title">
                        Event Baru Disetujui!
                    </div>
                    <div class="notif-desc">
                        Selamat! Event <strong>Pestapora 2026</strong> kamu telah disetujui oleh sistem dan sekarang terlihat di halaman utama.
                    </div>
                    <div class="notif-time"><i class="ph ph-clock"></i> Kemarin, 14:30</div>
                </div>
            </div>

            <!-- Dummy Notification 4 -->
            <div class="notif-card">
                <div class="notif-icon">
                    <i class="ph ph-ticket"></i>
                </div>
                <div class="notif-content">
                    <div class="notif-title">
                        Tiket Berhasil Terjual
                    </div>
                    <div class="notif-desc">
                        User <strong>Dimas Aryo</strong> baru saja membeli <strong>3 Tiket VVIP</strong> untuk event <strong>Coldplay Live in Jakarta</strong>.
                    </div>
                    <div class="notif-time"><i class="ph ph-clock"></i> 3 hari yang lalu</div>
                </div>
            </div>

        </div>

    </main>

</body>
</html>
