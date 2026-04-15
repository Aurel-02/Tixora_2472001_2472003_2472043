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

    <x-organizer-topbar />

    @if(isset($role) && ($role == '1' || $role == 'admin'))
        <x-admin-sidebar />
    @else
        <x-organizer-sidebar />
    @endif

    <main class="main-wrapper">
        <div class="page-header">
            <h1 class="page-title">Revenue Analytics</h1>
        </div>

        <div class="revenue-grid">
            @if($role == '1' || $role == 'admin')
            <div class="revenue-card">
                <div class="card-icon"><i class="ph ph-wallet"></i></div>
                <div class="card-label">Total Uang Masuk</div>
                <div class="card-value">Rp {{ number_format($totalUangMasuk ?? 0, 0, ',', '.') }}</div>
            </div>

            <div class="revenue-card" style="--middle-purple: #F3C8DD;">
                <div class="card-icon" style="background: rgba(243, 200, 221, 0.15); color: #F3C8DD;"><i class="ph ph-shield-check"></i></div>
                <div class="card-label">Total Uang Masuk Admin</div>
                <div class="card-value">Rp {{ number_format($totalJatahAdmin ?? 0, 0, ',', '.') }}</div>
            </div>

            <div class="revenue-card" style="--middle-purple: #84d8a5;">
                <div class="card-icon" style="background: rgba(132, 216, 165, 0.15); color: #84d8a5;"><i class="ph ph-bank"></i></div>
                <div class="card-label">Total Uang Masuk Organizer</div>
                <div class="card-value">Rp {{ number_format($totalJatahOrganizer ?? 0, 0, ',', '.') }}</div>
            </div>
            @else
            <div class="revenue-card" style="--middle-purple: #84d8a5;">
                <div class="card-icon" style="background: rgba(132, 216, 165, 0.15); color: #84d8a5;"><i class="ph ph-bank"></i></div>
                <div class="card-label">Pendapatanku</div>
                <div class="card-value">Rp {{ number_format($totalJatahOrganizer ?? 0, 0, ',', '.') }}</div>
            </div>
            @endif

            <div class="revenue-card" style="--middle-purple: #71557A;">
                <div class="card-icon" style="background: rgba(113, 85, 122, 0.2); color: #D183A9;"><i class="ph ph-ticket"></i></div>
                <div class="card-label">Total Transactions</div>
                <div class="card-value">{{ number_format($totalTransactions ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="section-container">
            <div class="section-header" style="flex-wrap: wrap; gap: 20px;">
                <h2 class="section-title">
                    <i class="ph ph-chart-line"></i> 
                    Event Revenue Breakdown
                </h2>
                <div class="event-selector" style="position: relative; min-width: 250px;">
                    <select id="eventFilter" onchange="filterEvents(this.value)" style="width: 100%; padding: 12px 20px; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(243, 200, 221, 0.2); border-radius: 12px; color: #fff; font-family: 'Outfit'; font-size: 0.9rem; cursor: pointer; outline: none; appearance: none; -webkit-appearance: none;">
                        <option value="all" style="background: var(--jacarta);">Semua Event</option>
                        @foreach($eventEarnings as $event)
                            <option value="{{ $event['id'] }}" style="background: var(--jacarta);">{{ $event['name'] }}</option>
                        @endforeach
                    </select>
                    <i class="ph ph-caret-down" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); pointer-events: none; opacity: 0.6;"></i>
                </div>
            </div>

            <table class="revenue-table" id="revenueTable">
                <thead>
                    <tr>
                        <th>Event Name</th>
                        @if($role == '1' || $role == 'admin')
                        <th>Total Gross Sales</th>
                        @endif
                        <th>Your Share ({{ ($role == '1' || $role == 'admin') ? '90' : '10' }}%)</th>
                        <th>Tickets Sold</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($eventEarnings as $event)
                    <tr class="event-row" data-event-id="{{ $event['id'] }}">
                        <td class="table-event-name">{{ $event['name'] }}</td>
                        @if($role == '1' || $role == 'admin')
                        <td>Rp {{ number_format($event['total_sales'], 0, ',', '.') }}</td>
                        @endif
                        <td style="font-weight: 700; color: #84d8a5;">Rp {{ number_format($event['revenue'], 0, ',', '.') }}</td>
                        <td>
                            <span class="badge badge-success">{{ number_format($event['tickets'], 0, ',', '.') }} Tickets</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 40px; opacity: 0.5;">
                            No event revenue data available.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($role == '1' || $role == 'admin')
        <div class="export-section" style="margin-top: 30px; padding: 25px 30px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(243, 200, 221, 0.15); border-radius: 20px; display: flex; justify-content: space-between; align-items: center; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);">
            <div>
                <h3 style="color: #fff; font-size: 1.2rem; margin-bottom: 5px;">Rekapan Detail Penjualan</h3>
                <p style="color: rgba(243, 200, 221, 0.8); font-size: 0.95rem; margin: 0;">Unduh dokumen PDF berisi rincian penjualan seluruh event.</p>
            </div>
            <a href="{{ route('admin.revenue.export_pdf') }}" target="_blank" style="background: rgba(132, 216, 165, 0.15); border: 1px solid #84d8a5; color: #84d8a5; padding: 12px 28px; border-radius: 12px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; transition: all 0.3s ease; white-space: nowrap;" onmouseover="this.style.background='rgba(132, 216, 165, 0.25)'" onmouseout="this.style.background='rgba(132, 216, 165, 0.15)'">
                <i class="ph ph-file-pdf" style="font-size: 1.3rem;"></i>
                Export PDF
            </a>
        </div>
        @endif
    </main>

    <script>
        function filterEvents(eventId) {
            const rows = document.querySelectorAll('.event-row');
            rows.forEach(row => {
                if (eventId === 'all' || row.getAttribute('data-event-id') === eventId) {
                    row.style.display = 'table-row';
                    row.style.animation = 'fadeIn 0.4s ease forwards';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        #eventFilter:hover {
            border-color: var(--middle-purple);
            background: rgba(255, 255, 255, 0.15);
        }

        option {
            padding: 10px;
        }
    </style>

</body>
</html>
