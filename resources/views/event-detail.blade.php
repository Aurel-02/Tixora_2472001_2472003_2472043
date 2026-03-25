<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tixora - Event Detail</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        :root {
            --jacarta: #3A345B;
            --queen-pink: #F3C8DD;
            --middle-purple: #D183A9;
            --old-lavender: #71557A;
            --brown-chocolate: #4B1535;
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
            left: 0;
            right: 0;
            height: var(--topbar-height);
            background: rgba(58, 52, 91, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(243, 200, 221, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 40px 0 20px;
            z-index: 1100;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .menu-trigger {
            font-size: 1.8rem;
            color: var(--queen-pink);
            cursor: pointer;
            padding: 10px;
            transition: color 0.3s;
        }
        
        .menu-trigger:hover {
            color: #fff;
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
            left: calc(var(--sidebar-width-expanded) * -1);
            width: var(--sidebar-width-expanded);
            height: 100vh;
            background: rgba(113, 85, 122, 0.85); /* Slightly more opaque since it overlays content */
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-right: 1px solid rgba(243, 200, 221, 0.15);
            z-index: 1050;
            transition: left 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.3s ease;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            padding-top: calc(var(--topbar-height) + 20px);
        }

        .sidebar.active {
            left: 0;
            box-shadow: 5px 0 20px rgba(0, 0, 0, 0.5);
        }

        .sidebar-menu {
            list-style: none;
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
            color: #fff;
        }

        .sidebar-icon {
            font-size: 1.4rem;
            min-width: 25px;
            margin-right: 20px;
        }

        .sidebar-text {
            font-size: 1rem;
            font-weight: 500;
        }

        .main-wrapper {
            padding-top: calc(var(--topbar-height) + 30px);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-bottom: 40px;
        }

        .content-container {
            width: 100%;
            max-width: 1100px;
            padding: 0 20px;
        }

        .poster-placeholder {
            width: 100%;
            height: 340px;
            background: rgba(220, 220, 220, 0.8);
            border-radius: 12px;
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: rgba(0, 0, 0, 0.3);
            font-size: 1.2rem;
            font-weight: 500;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .grid-layout {
            display: grid;
            grid-template-columns: 1.8fr 1fr;
            gap: 25px;
        }

        .glass-box {
            background: rgba(113, 85, 122, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(243, 200, 221, 0.15);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .left-column {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .event-detail-box {
            text-align: center;
            position: relative;
        }

        .event-detail-title {
            position: absolute;
            top: 25px;
            left: 25px;
            font-size: 1rem;
            font-weight: 500;
            color: var(--queen-pink);
            opacity: 0.9;
        }

        .event-name {
            font-size: 2.2rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
            margin-top: 20px;
        }

        .event-meta {
            font-size: 1.1rem;
            color: var(--queen-pink);
            margin-bottom: 5px;
            opacity: 0.9;
        }

        .guidelines-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .guidelines-content {
            flex: 1;
        }

        .guidelines-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .guidelines-list {
            list-style-type: disc;
            padding-left: 20px;
            color: var(--queen-pink);
            font-size: 1rem;
            line-height: 1.6;
        }

        .guidelines-list li {
            margin-bottom: 5px;
        }

        .map-placeholder {
            width: 120px;
            height: 140px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-left: 20px;
            border: 1px solid rgba(243, 200, 221, 0.1);
        }

        .map-placeholder-text {
            font-size: 0.7rem;
            margin-top: 10px;
            opacity: 0.5;
            text-align: center;
        }

        .right-column {
            display: flex;
            flex-direction: column;
            /* Match heights approximately or just flex */
            height: 100%;
        }

        .ticket-action-box {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 35px 30px;
        }

        .countdown-timer {
            background: rgba(58, 52, 91, 0.4);
            border-radius: 10px;
            padding: 20px 10px;
            text-align: center;
            margin-bottom: 30px;
            border: 1px solid rgba(243, 200, 221, 0.05);
        }

        .countdown-numbers {
            font-size: 2.2rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 2px;
            display: flex;
            justify-content: center;
        }

        .countdown-labels {
            display: flex;
            justify-content: space-between;
            padding: 0 15px;
            margin-top: 5px;
            font-size: 0.65rem;
            color: var(--queen-pink);
            opacity: 0.7;
        }

        .countdown-labels span {
            flex: 1;
            text-align: center;
        }

        .ticket-stats {
            margin-bottom: 40px;
            flex: 1;
        }

        .stat-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 0.9rem;
            color: var(--queen-pink);
        }

        .stat-label {
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-weight: 600;
            color: #fff;
        }

        .btn-buy-wrapper {
            text-align: center;
            margin-top: auto;
        }

        .btn-buy {
            background: var(--brown-chocolate);
            color: var(--queen-pink);
            border: 1px solid var(--middle-purple);
            padding: 12px 40px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            text-decoration: none;
            display: inline-block;
            width: 100%;
            max-width: 200px;
        }

        .btn-buy:hover {
            background: var(--middle-purple);
            color: var(--jacarta);
            box-shadow: 0 0 15px rgba(209, 131, 169, 0.6);
            transform: translateY(-2px);
        }

        @media (max-width: 900px) {
            .grid-layout {
                grid-template-columns: 1fr;
            }
            .poster-placeholder {
                height: 200px;
            }
            .event-name {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>

    <header class="topbar">
        <div class="topbar-left">
            <i class="ph ph-list menu-trigger" id="menuToggle"></i>
            <div class="logo">TIXORA</div>
        </div>
        <div class="profile" title="My Profile">U</div>
    </header>

    <aside class="sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <li>
                <a href="/dashboard" class="sidebar-item">
                    <i class="ph ph-house sidebar-icon"></i>
                    <span class="sidebar-text">Home</span>
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-item">
                    <i class="ph ph-magnifying-glass sidebar-icon"></i>
                    <span class="sidebar-text">Search</span>
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-item">
                    <i class="ph ph-ticket sidebar-icon"></i>
                    <span class="sidebar-text">My Tickets</span>
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-item">
                    <i class="ph ph-bell sidebar-icon"></i>
                    <span class="sidebar-text">Notifications</span>
                </a>
            </li>
        </ul>
    </aside>

    <main class="main-wrapper">
        <div class="content-container">
            <div class="poster-placeholder">
                [ Poster Placeholder ]
            </div>

            <div class="grid-layout">
                <div class="left-column">
                    <div class="glass-box event-detail-box">
                        <div class="event-detail-title">Event Detail</div>
                        
                        @php
                            $tanggal = $event->tanggal_pelaksanaan ? \Carbon\Carbon::parse($event->tanggal_pelaksanaan) : null;
                        @endphp

                        <div class="event-name">{{ $event->nama_event }}</div>
                        <div class="event-meta">{{ $tanggal ? $tanggal->format('d F Y') : 'Date TBD' }}</div>
                        <div class="event-meta">{{ $event->lokasi_event ?? 'Venue TBD' }}</div>
                    </div>

                    <div class="glass-box guidelines-box">
                        <div class="guidelines-content">
                            <div class="guidelines-title">Ticket Guidelines</div>
                            <ul class="guidelines-list">
                                <li>Max 5 tickets per user</li>
                                <li>E-Ticket will be sent via Email</li>
                                <li>Ticket Cancellation & Waiting List</li>
                            </ul>
                        </div>
                        <div class="map-placeholder">
                            <i class="ph ph-map-trifold" style="font-size: 2.5rem; color: var(--middle-purple);"></i>
                            <div class="map-placeholder-text">Seat Map</div>
                        </div>
                    </div>
                </div>

                <div class="right-column">
                    <div class="glass-box ticket-action-box">
                        <div class="countdown-timer">
                            <div class="countdown-numbers" id="countdownTimer">00 : 00 : 00 : 00</div>
                            <div class="countdown-labels">
                                <span>DAYS</span>
                                <span>HOURS</span>
                                <span>MINUTES</span>
                                <span>SECONDS</span>
                            </div>
                        </div>

                        <div class="ticket-stats">
                            <div class="stat-row">
                                <span class="stat-label">Total Tickets</span>
                                <span class="stat-value">13,200</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-label">Tickets Sold</span>
                                <span class="stat-value">13,200</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-label">Tickets Available</span>
                                <span class="stat-value">13,200</span>
                            </div>
                        </div>

                        <div class="btn-buy-wrapper">
                            <button class="btn-buy">BUY TICKET</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const eventDateStr = @json($tanggal ? $tanggal->toIso8601String() : null);

        if (eventDateStr) {
            const eventTime = new Date(eventDateStr).getTime();
            const countdownEl = document.getElementById('countdownTimer');

            const updateTimer = () => {
                const now = new Date().getTime();
                const distance = eventTime - now;

                if (distance < 0) {
                    countdownEl.innerHTML = "00 : 00 : 00 : 00";
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                const pad = (num) => num.toString().padStart(2, '0');

                countdownEl.innerHTML = `${pad(days)} : ${pad(hours)} : ${pad(minutes)} : ${pad(seconds)}`;
            };

            updateTimer();
            setInterval(updateTimer, 1000);
        } else {
            document.getElementById('countdownTimer').innerHTML = "TBD";
        }

        document.addEventListener('DOMContentLoaded', () => {
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');

            let closeTimeout;

            menuToggle.addEventListener('mouseenter', () => {
                clearTimeout(closeTimeout);
                sidebar.classList.add('active');
            });

            menuToggle.addEventListener('mouseleave', () => {
                closeTimeout = setTimeout(() => {
                    sidebar.classList.remove('active');
                }, 300); 
            });

            sidebar.addEventListener('mouseenter', () => {
                clearTimeout(closeTimeout);
            });

            sidebar.addEventListener('mouseleave', () => {
                sidebar.classList.remove('active');
            });
        });
    </script>
</body>
</html>
