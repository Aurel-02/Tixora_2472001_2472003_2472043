<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tixora - Revenue Dashboard</title>
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
            padding: calc(var(--topbar-height) + 40px) 40px 40px;
            min-height: 100vh;
        }

        .page-header {
            margin-bottom: 40px;
        }

        .page-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #fff;
            text-shadow: 0 0 15px rgba(243, 200, 221, 0.3);
            margin-bottom: 8px;
        }

        .page-subtitle {
            font-size: 1rem;
            opacity: 0.7;
            color: var(--queen-pink);
        }

        .revenue-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .revenue-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(243, 200, 221, 0.1);
            border-radius: 24px;
            padding: 30px;
            transition: transform 0.3s ease, border-color 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .revenue-card:hover {
            transform: translateY(-5px);
            border-color: var(--middle-purple);
            background: rgba(255, 255, 255, 0.08);
        }

        .revenue-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--middle-purple);
        }

        .card-icon {
            width: 50px;
            height: 50px;
            background: rgba(209, 131, 169, 0.15);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--middle-purple);
            margin-bottom: 20px;
        }

        .card-label {
            font-size: 0.95rem;
            font-weight: 500;
            opacity: 0.6;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-value {
            font-size: 2.2rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 15px;
        }

        .card-trend {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .trend-up {
            color: #84d8a5;
        }

        .section-container {
            background: rgba(113, 85, 122, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(243, 200, 221, 0.15);
            border-radius: 20px;
            padding: 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title i {
            color: var(--middle-purple);
        }

        .revenue-table {
            width: 100%;
            border-collapse: collapse;
        }

        .revenue-table th {
            text-align: left;
            padding: 15px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--middle-purple);
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid rgba(243, 200, 221, 0.1);
        }

        .revenue-table td {
            padding: 18px 15px;
            color: var(--queen-pink);
            border-bottom: 1px solid rgba(243, 200, 221, 0.05);
        }

        .revenue-table tr:last-child td {
            border-bottom: none;
        }

        .table-event-name {
            font-weight: 600;
            color: #fff;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-success {
            background: rgba(132, 216, 165, 0.15);
            color: #84d8a5;
        }

        @media (max-width: 768px) {
            .main-wrapper {
                padding: calc(var(--topbar-height) + 20px) 20px 20px;
            }
        }
    </style>
</head>
<body>

    <header class="topbar">
        <div class="logo">TIXORA</div>
        <div style="display: flex; align-items: center; gap: 12px;">
            <a href="{{ route('profile.edit') }}" class="profile" title="My Profile" style="text-decoration:none;">
                @php
                    $displayName = session('login_admin.name') ?? (auth()->check() ? auth()->user()->nama_lengkap : 'Admin');
                @endphp
                {{ strtoupper(substr($displayName, 0, 1)) }}
            </a>
        </div>
    </header>

    <aside class="sidebar">
        <div class="sidebar-content" style="display: flex; flex-direction: column; height: calc(100vh - var(--topbar-height));">
            <ul class="sidebar-menu" style="flex-grow: 1; padding-top: 20px;">
                @if(($role ?? 1) == 1)
                    <li>
                        <a href="{{ url('/admin/dashboard') }}" class="sidebar-item">
                            <i class="ph ph-house sidebar-icon"></i>
                            <span class="sidebar-text">Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.revenue') }}" class="sidebar-item active">
                            <i class="ph ph-currency-dollar sidebar-icon"></i>
                            <span class="sidebar-text">Revenue</span>
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ url('/organizerdashboard') }}" class="sidebar-item">
                            <i class="ph ph-house sidebar-icon"></i>
                            <span class="sidebar-text">Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('organizer.statistik') }}" class="sidebar-item">
                            <i class="ph ph-chart-bar sidebar-icon"></i>
                            <span class="sidebar-text">Statistik Penjualan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('organizer.revenue') }}" class="sidebar-item active">
                            <i class="ph ph-currency-dollar sidebar-icon"></i>
                            <span class="sidebar-text">Revenue</span>
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
                @endif
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
        <div class="page-header">
            <h1 class="page-title">Revenue Analytics</h1>
            <p class="page-subtitle">Track and analyze earnings from all sources.</p>
        </div>

        <div class="revenue-grid">
            @if(($role ?? 1) == 1)
                <div class="revenue-card">
                    <div class="card-icon"><i class="ph ph-crown"></i></div>
                    <div class="card-label">Admin Earnings</div>
                    <div class="card-value">Rp {{ number_format($adminRevenue ?? 0, 0, ',', '.') }}</div>
                </div>

                <div class="revenue-card" style="--middle-purple: #F3C8DD;">
                    <div class="card-icon" style="background: rgba(243, 200, 221, 0.15); color: #F3C8DD;"><i class="ph ph-users-three"></i></div>
                    <div class="card-label">Organizer Earnings</div>
                    <div class="card-value">Rp {{ number_format($organizerRevenue ?? 0, 0, ',', '.') }}</div>
                </div>
            @else
                <div class="revenue-card">
                    <div class="card-icon"><i class="ph ph-wallet"></i></div>
                    <div class="card-label">Your Total Revenue</div>
                    <div class="card-value">Rp {{ number_format($organizerRevenue ?? 0, 0, ',', '.') }}</div>
                    <div class="card-trend trend-up">
                        <i class="ph ph-trend-up"></i> +12% from last month
                    </div>
                </div>

                <div class="revenue-card" style="--middle-purple: #F3C8DD;">
                    <div class="card-icon" style="background: rgba(243, 200, 221, 0.15); color: #F3C8DD;"><i class="ph ph-hand-coins"></i></div>
                    <div class="card-label">Pending Payout</div>
                    <div class="card-value">Rp 0</div>
                </div>
            @endif

            <div class="revenue-card" style="--middle-purple: #71557A;">
                <div class="card-icon" style="background: rgba(113, 85, 122, 0.2); color: #D183A9;"><i class="ph ph-ticket"></i></div>
                <div class="card-label">Total Transactions</div>
                <div class="card-value">{{ number_format($totalTransactions ?? 0, 0, ',', '.') }}</div>
                <div class="card-trend">
                    <i class="ph ph-swap"></i> Processed tickets
                </div>
            </div>
        </div>

        <div class="section-container">
            <div class="section-header">
                <h2 class="section-title"><i class="ph ph-clock-counter-clockwise"></i> Recent Revenue Stream</h2>
                <div style="font-size: 0.9rem; opacity: 0.5;">Last updated: Today, 10:15 AM</div>
            </div>

            <table class="revenue-table">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Event Name</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTransactions as $tx)
                    <tr>
                        <td style="font-family: monospace; letter-spacing: 1px;">{{ $tx['id'] }}</td>
                        <td class="table-event-name">{{ $tx['event'] }}</td>
                        <td style="font-weight: 700;">Rp {{ number_format($tx['amount'], 0, ',', '.') }}</td>
                        <td>{{ date('d M Y', strtotime($tx['date'])) }}</td>
                        <td><span class="badge badge-success">Completed</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>
