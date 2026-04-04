<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tixora - My Tickets</title>
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .page-header {
            padding: 40px;
            padding-bottom: 20px;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
        }
        
        .page-desc {
            font-size: 1rem;
            color: var(--queen-pink);
            opacity: 0.8;
        }

        .ticket-tabs {
            display: flex;
            padding: 0 40px;
            border-bottom: 1px solid rgba(243, 200, 221, 0.15);
            margin-bottom: 30px;
        }

        .ticket-tab {
            padding: 15px 30px;
            color: var(--queen-pink);
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            position: relative;
            opacity: 0.6;
            transition: all 0.3s ease;
            background: transparent;
            border: none;
        }

        .ticket-tab:hover {
            opacity: 0.9;
        }

        .ticket-tab.active {
            opacity: 1;
            color: #fff;
            font-weight: 600;
        }

        .ticket-tab.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--middle-purple);
            border-radius: 3px 3px 0 0;
            box-shadow: 0 -2px 10px rgba(209, 131, 169, 0.5);
        }

        .ticket-content {
            padding: 0 40px 40px;
            display: none;
            animation: fadeIn 0.4s ease forwards;
        }

        .ticket-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .empty-state {
            background: rgba(113, 85, 122, 0.2);
            backdrop-filter: blur(10px);
            border: 1px dashed rgba(243, 200, 221, 0.3);
            border-radius: 15px;
            padding: 60px 20px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .empty-icon {
            font-size: 4rem;
            color: rgba(209, 131, 169, 0.5);
            margin-bottom: 10px;
        }

        .empty-title {
            font-size: 1.3rem;
            color: #fff;
            font-weight: 600;
        }

        .empty-desc {
            color: var(--queen-pink);
            opacity: 0.8;
            max-width: 400px;
        }

        .btn-browse {
            margin-top: 20px;
            background: var(--middle-purple);
            color: var(--jacarta);
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(209, 131, 169, 0.3);
        }

        .btn-browse:hover {
            background: var(--queen-pink);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(243, 200, 221, 0.4);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                border: none;
            }
            .sidebar:hover {
                width: var(--sidebar-width-expanded);
            }
            .topbar {
                left: 0;
            }
            .main-wrapper {
                margin-left: 0;
            }
            .page-header, .ticket-tabs, .ticket-content {
                padding-left: 20px;
                padding-right: 20px;
            }
            .ticket-tab {
                padding: 15px;
                flex: 1;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <header class="topbar">
        <div class="logo">TIXORA</div>
        <div style="display: flex; align-items: center; gap: 15px;">
            <div class="search-box" style="display: flex; align-items: center; border: 1px solid rgba(243, 200, 221, 0.4); border-radius: 50px; background: rgba(58, 52, 91, 0.4); padding: 8px 18px; min-width: 250px; transition: all 0.3s ease;">
                <i class="ph ph-magnifying-glass" style="color: var(--queen-pink); font-size: 1.1rem; margin-right: 10px;"></i>
                <input type="text" id="globalSearch" placeholder="Search events..." style="width: 100%; border: none; outline: none; background: transparent; color: #fff; font-size: 0.95rem; font-family: 'Outfit', sans-serif;" />
            </div>
            <a href="{{ route('profile.edit') }}" class="profile" title="My Profile" style="text-decoration:none;">
                @if(auth()->user()->photo_profile)
                    <img src="{{ asset(auth()->user()->photo_profile) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                @else
                    {{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'U', 0, 1)) }}
                @endif
            </a>
        </div>
    </header>

    <aside class="sidebar">
        <ul class="sidebar-menu">
            <li>
                <a href="{{ url('/dashboard') }}" class="sidebar-item">
                    <i class="ph ph-house sidebar-icon"></i>
                    <span class="sidebar-text">Home</span>
                </a>
            </li>

            <li>
                <a href="{{ route('my-tickets') }}" class="sidebar-item active">
                    <i class="ph ph-ticket sidebar-icon"></i>
                    <span class="sidebar-text">My Tickets</span>
                </a>
            </li>
            <li>
                <a href="{{ route('buyer.notification') }}" class="sidebar-item">
                    <i class="ph ph-bell sidebar-icon"></i>
                    <span class="sidebar-text">Notifications</span>
                </a>
            </li>
        </ul>
    </aside>

    <main class="main-wrapper">
        <div class="page-header">
            <h1 class="page-title">My Tickets</h1>
            <p class="page-desc">Manage your purchased tickets and waiting list status</p>
        </div>

        <div class="ticket-tabs">
            <button class="ticket-tab active" onclick="switchTab('history')">History</button>
            <button class="ticket-tab" onclick="switchTab('waiting-list')">Waiting List</button>
        </div>

        <div id="history" class="ticket-content active">
            <div class="empty-state">
                <i class="ph ph-ticket empty-icon"></i>
                <div class="empty-title">No ticket history yet</div>
                <div class="empty-desc">You haven't purchased any tickets yet. Explore our upcoming events and grab yours now!</div>
                <a href="{{ url('/dashboard') }}" class="btn-browse">Browse Events</a>
            </div>
        </div>

        <div id="waiting-list" class="ticket-content">
            <div class="empty-state">
                <i class="ph ph-hourglass-medium empty-icon"></i>
                <div class="empty-title">Your waiting list is empty</div>
                <div class="empty-desc">You are not currently in any waiting list. If tickets for an event open up, they will appear here.</div>
                <a href="{{ url('/dashboard') }}" class="btn-browse">Discover More</a>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('globalSearch')?.addEventListener('keyup', function(e) {
            if(e.key === 'Enter') {
                window.location.href = '/dashboard';
            }
        });

        function switchTab(tabId) {
            document.querySelectorAll('.ticket-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');
            document.querySelectorAll('.ticket-content').forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(tabId).classList.add('active');
        }
    </script>
</body>
</html>
