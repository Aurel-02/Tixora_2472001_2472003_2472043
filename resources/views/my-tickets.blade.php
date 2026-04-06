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

        .tickets-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .ticket-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(243, 200, 221, 0.2);
            border-radius: 20px;
            padding: 20px;
            display: flex;
            gap: 25px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .ticket-card:hover {
            transform: translateY(-3px);
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(243, 200, 221, 0.4);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .ticket-poster {
            width: 160px;
            height: 160px;
            flex-shrink: 0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .ticket-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .ticket-details {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .event-name {
            font-size: 1.5rem;
            color: #fff;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .ticket-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        .ticket-status.lunas { background: rgba(168, 230, 207, 0.2); color: #a8e6cf; }
        .ticket-status.pending { background: rgba(255, 211, 182, 0.2); color: #ffd3b6; }
        .ticket-status.batal { background: rgba(255, 139, 148, 0.2); color: #ff8b94; }

        .ticket-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 15px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--queen-pink);
            font-size: 1rem;
            opacity: 0.9;
        }

        .ticket-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px dashed rgba(243, 200, 221, 0.2);
        }

        .ticket-type {
            font-size: 1.1rem;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .type-label {
            color: var(--queen-pink);
            opacity: 0.8;
            font-size: 0.9rem;
            margin-right: 5px;
        }

        .ticket-qty {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .ticket-card { flex-direction: column; align-items: center; text-align: center; }
            .ticket-header { flex-direction: column; align-items: center; gap: 10px; }
            .ticket-poster { width: 100%; height: 200px; }
            .ticket-bottom { flex-direction: column; gap: 15px; }
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

        .btn-cancel {
            background: rgba(255, 139, 148, 0.1);
            border: 1px solid #ff8b94;
            color: #ff8b94;
            padding: 8px 20px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-cancel:hover {
            background: #ff8b94;
            color: #fff;
            box-shadow: 0 4px 12px rgba(255, 139, 148, 0.4);
        }

        .alert {
            padding: 15px 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(168, 230, 207, 0.2);
            color: #a8e6cf;
            border: 1px solid rgba(168, 230, 207, 0.4);
        }

        .alert-error {
            background: rgba(255, 139, 148, 0.2);
            color: #ff8b94;
            border: 1px solid rgba(255, 139, 148, 0.4);
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .modal-content {
            background: linear-gradient(135deg, #4B1535, #3A345B);
            border: 1px solid rgba(243, 200, 221, 0.2);
            width: 90%;
            max-width: 450px;
            padding: 40px;
            border-radius: 25px;
            text-align: center;
            transform: scale(0.9);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        .modal-icon {
            font-size: 4rem;
            color: #ff8b94;
            margin-bottom: 20px;
            display: block;
        }

        .modal-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #fff;
        }

        .modal-desc {
            font-size: 1.1rem;
            color: var(--queen-pink);
            margin-bottom: 30px;
            line-height: 1.6;
            opacity: 0.9;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn-modal {
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 1rem;
        }

        .btn-keep {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(243, 200, 221, 0.4);
            color: #fff;
        }

        .btn-keep:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .btn-confirm {
            background: #ff8b94;
            border: none;
            color: #fff;
            box-shadow: 0 4px 15px rgba(255, 139, 148, 0.3);
        }

        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 139, 148, 0.5);
        }
    </style>
</head>
<body>

@php
    $unreadNotifCount = 0;
    if(auth()->check()) {
        $unreadNotifCount = \Illuminate\Support\Facades\DB::table('notifikasi')->where('id_user', auth()->id())->where('is_read', 0)->count();
    }
@endphp


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
                <a href="{{ route('buyer.notification') }}" class="sidebar-item" style="position: relative;"">
                    <i class="ph ph-bell sidebar-icon"></i>
                    <span class="sidebar-text">Notifications</span>
                
        @if(isset($unreadNotifCount) && $unreadNotifCount > 0)
            <span style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); background: #E74C3C; color: white; border-radius: 50%; width: 22px; height: 22px; display:flex; align-items:center; justify-content:center; font-size: 0.75rem; font-weight: bold; box-shadow: 0 0 5px rgba(231, 76, 60, 0.5);">{{ $unreadNotifCount }}</span>
        @endif
</a>
            </li>
        </ul>
    </aside>

    <main class="main-wrapper">
        <div class="page-header">
            <h1 class="page-title">My Tickets</h1>
            <p class="page-desc">Manage your purchased tickets and waiting list status</p>
        </div>

        <div style="padding: 0 40px;">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="ph ph-check-circle" style="font-size: 1.4rem;"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="ph ph-warning-circle" style="font-size: 1.4rem;"></i>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <div class="ticket-tabs">
            <button class="ticket-tab active" onclick="switchTab('history')">History</button>
            <button class="ticket-tab" onclick="switchTab('waiting-list')">Waiting List</button>
            <button class="ticket-tab" onclick="switchTab('canceled')">Canceled</button>
        </div>

        <div id="history" class="ticket-content active">


            @if($historyTickets->count() > 0)
                <div class="tickets-list">
                    @foreach($historyTickets as $ticket)
                        <div class="ticket-card">
                            <div class="ticket-poster">
                                <img src="{{ asset($ticket->poster) }}" alt="{{ $ticket->nama_event }}" onerror="this.src='{{ asset('images/event_placeholder.jpg') }}'">
                            </div>
                            <div class="ticket-details">
                                <div class="ticket-header">
                                    <h3 class="event-name">{{ $ticket->nama_event }}</h3>
                                </div>
                                <div class="ticket-info">
                                    <div class="info-item"><i class="ph ph-calendar-blank"></i> {{ \Illuminate\Support\Carbon::parse($ticket->tanggal_pelaksanaan)->format('d M Y') }}</div>
                                    <div class="info-item"><i class="ph ph-map-pin"></i> {{ $ticket->lokasi_event }}</div>
                                </div>
                                <div class="ticket-bottom">
                                    <div class="ticket-type">
                                        <span><span class="type-label">Type:</span> <strong>{{ $ticket->jenis_tiket }}</strong></span>
                                        <span class="ticket-qty">x{{ $ticket->jumlah_beli }}</span>
                                    </div>
                                    <button type="button" class="btn-cancel" onclick="showCancelModal('{{ $ticket->id_detail }}')">Cancel Ticket</button>
                                    <form id="cancel-form-{{ $ticket->id_detail }}" action="{{ route('my-tickets.cancel', $ticket->id_detail) }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="ph ph-ticket empty-icon"></i>
                    <div class="empty-title">No ticket history yet</div>
                    <div class="empty-desc">You haven't purchased any tickets yet. Explore our upcoming events and grab yours now!</div>
                    <a href="{{ url('/dashboard') }}" class="btn-browse">Browse Events</a>
                </div>
            @endif
        </div>

        <div id="waiting-list" class="ticket-content">


            @if($waitingTickets->count() > 0)
                <div class="tickets-list">
                    @foreach($waitingTickets as $ticket)
                        <div class="ticket-card">
                            <div class="ticket-poster">
                                <img src="{{ asset($ticket->poster) }}" alt="{{ $ticket->nama_event }}" onerror="this.src='{{ asset('images/event_placeholder.jpg') }}'">
                            </div>
                            <div class="ticket-details">
                                <div class="ticket-header">
                                    <h3 class="event-name">{{ $ticket->nama_event }}</h3>
                                </div>
                                <div class="ticket-info">
                                    <div class="info-item"><i class="ph ph-calendar-blank"></i> {{ \Illuminate\Support\Carbon::parse($ticket->tanggal_pelaksanaan)->format('d M Y') }}</div>
                                    <div class="info-item"><i class="ph ph-map-pin"></i> {{ $ticket->lokasi_event }}</div>
                                </div>
                                <div class="ticket-bottom">
                                    <div class="ticket-type">
                                        <span><span class="type-label">Type:</span> <strong>{{ $ticket->jenis_tiket }}</strong></span>
                                        <span class="ticket-qty">x{{ $ticket->jumlah_beli }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="ph ph-hourglass-medium empty-icon"></i>
                    <div class="empty-title">Your waiting list is empty</div>
                    <div class="empty-desc">You are not currently in any waiting list. If tickets for an event open up, they will appear here.</div>
                    <a href="{{ url('/dashboard') }}" class="btn-browse">Discover More</a>
                </div>
            @endif
        </div>

        <div id="canceled" class="ticket-content">


            @if($canceledTickets->count() > 0)
                <div class="tickets-list">
                    @foreach($canceledTickets as $ticket)
                        <div class="ticket-card">
                            <div class="ticket-poster">
                                <img src="{{ asset($ticket->poster) }}" alt="{{ $ticket->nama_event }}" onerror="this.src='{{ asset('images/event_placeholder.jpg') }}'">
                            </div>
                            <div class="ticket-details">
                                <div class="ticket-header">
                                    <h3 class="event-name">{{ $ticket->nama_event }}</h3>
                                </div>
                                <div class="ticket-info">
                                    <div class="info-item"><i class="ph ph-calendar-blank"></i> {{ \Illuminate\Support\Carbon::parse($ticket->tanggal_pelaksanaan)->format('d M Y') }}</div>
                                    <div class="info-item"><i class="ph ph-map-pin"></i> {{ $ticket->lokasi_event }}</div>
                                </div>
                                <div class="ticket-bottom">
                                    <div class="ticket-type">
                                        <span><span class="type-label">Type:</span> <strong>{{ $ticket->jenis_tiket }}</strong></span>
                                        <span class="ticket-qty">x{{ $ticket->jumlah_beli }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="ph ph-prohibit empty-icon"></i>
                    <div class="empty-title">No canceled tickets</div>
                    <div class="empty-desc">You don't have any canceled tickets in your history.</div>
                </div>
            @endif
        </div>
    </main>

    <!-- Cancellation Modal -->
    <div id="cancelModal" class="modal-overlay">
        <div class="modal-content">
            <i class="ph ph-warning-circle modal-icon"></i>
            <h2 class="modal-title">Cancel Ticket?</h2>
            <p class="modal-desc">Are you sure you want to cancel this ticket? This action will process waiting lists and move the ticket to your canceled history.</p>
            <div class="modal-actions">
                <button type="button" class="btn-modal btn-keep" onclick="closeCancelModal()">No, Keep it</button>
                <button type="button" class="btn-modal btn-confirm" id="confirmCancelBtn">Yes, Cancel Ticket</button>
            </div>
        </div>
    </div>

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

        let currentTicketId = null;

        function showCancelModal(id) {
            currentTicketId = id;
            const modal = document.getElementById('cancelModal');
            modal.classList.add('active');
        }

        function closeCancelModal() {
            const modal = document.getElementById('cancelModal');
            modal.classList.remove('active');
            currentTicketId = null;
        }

        document.getElementById('confirmCancelBtn').addEventListener('click', function() {
            if (currentTicketId) {
                document.getElementById('cancel-form-' + currentTicketId).submit();
            }
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('cancelModal');
            if (event.target == modal) {
                closeCancelModal();
            }
        }
    </script>
</body>
</html>
