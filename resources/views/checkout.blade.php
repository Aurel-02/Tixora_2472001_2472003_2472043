<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tixora - Checkout Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        :root {
            --jacarta: #3A345B;
            --queen-pink: #F3C8DD;
            --middle-purple: #D183A9;
            --old-lavender: #71557A;
            --brown-chocolate: #4B1535;
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
            text-decoration: none;
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
        }

        .main-wrapper {
            padding-top: calc(var(--topbar-height) + 40px);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-bottom: 60px;
        }

        .content-container {
            width: 100%;
            max-width: 900px;
            padding: 0 20px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 30px;
            text-align: center;
        }

        .tickets-container {
            display: flex;
            flex-direction: column;
            gap: 25px;
            margin-bottom: 40px;
        }

        /* Ticket Design */
        .ticket-box {
            display: flex;
            background: #fff;
            color: #000;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            min-height: 200px;
            position: relative;
        }

        .ticket-left {
            flex: 1.2;
            background: linear-gradient(135deg, var(--middle-purple), var(--old-lavender));
            padding: 25px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            position: relative;
            min-width: 280px;
        }

        .ticket-left::after {
            content: '';
            position: absolute;
            right: 0;
            top: 5%;
            height: 90%;
            border-right: 2px dashed rgba(0,0,0,0.1);
        }

        .ticket-badge {
            position: absolute;
            top: 15px;
            left: -30px;
            background: rgba(255, 255, 255, 0.3);
            color: #fff;
            padding: 5px 40px;
            transform: rotate(-45deg);
            font-weight: 800;
            font-size: 0.75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            backdrop-filter: blur(5px);
        }

        .ticket-event-name-large {
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .ticket-event-date-large {
            font-size: 0.9rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            text-transform: uppercase;
        }

        .ticket-right {
            flex: 1;
            background: #fff;
            padding: 25px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .ticket-detail-title {
            font-size: 1.2rem;
            font-weight: 800;
            margin-bottom: 15px;
            text-transform: uppercase;
            color: #333;
        }

        .ticket-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .info-item label {
            display: block;
            font-size: 0.65rem;
            font-weight: 600;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .info-item span {
            font-size: 0.9rem;
            font-weight: 700;
            color: #333;
        }

        /* Order Summary */
        .order-summary {
            background: rgba(113, 85, 122, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(243, 200, 221, 0.15);
            border-radius: 12px;
            padding: 30px;
            margin-top: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 1rem;
        }

        .summary-total {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(243, 200, 221, 0.1);
            font-weight: 700;
            font-size: 1.4rem;
            color: #fff;
        }

        .btn-payment {
            display: block;
            width: 100%;
            max-width: 400px;
            margin: 40px auto 0;
            padding: 15px;
            background: var(--middle-purple);
            color: var(--jacarta);
            border: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            text-align: center;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(209, 131, 169, 0.4);
        }

        .btn-payment:hover {
            background: var(--queen-pink);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(243, 200, 221, 0.5);
        }

        @media (max-width: 700px) {
            .ticket-box {
                flex-direction: column;
            }
            .ticket-left::after {
                display: none;
            }
            .ticket-left {
                border-bottom: 2px dashed rgba(0,0,0,0.1);
                min-width: unset;
            }
        }
    </style>
</head>
<body>

    <header class="topbar">
        <a href="/dashboard" class="logo">TIXORA</a>
        <a href="{{ route('profile.edit') }}" class="profile" title="My Profile">
            @if(auth()->user()->photo_profile)
                <img src="{{ asset(auth()->user()->photo_profile) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            @else
                {{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'U', 0, 1)) }}
            @endif
        </a>
    </header>

    <main class="main-wrapper">
        <div class="content-container">
            <h1 class="page-title">Checkout Details</h1>

            <div class="tickets-container">
                @foreach($selectedTickets as $item)
                    <div class="ticket-box">
                        <div class="ticket-badge">{{ $item['details']->jenis_tiket }} ({{ $item['quantity'] }}x)</div>
                        <div class="ticket-left" style="position: relative; overflow: hidden; background: #1a1a1a;">
                            {{-- Poster Event --}}
                            @if($event->poster)
                                <div class="ticket-poster" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;">
                                    {{-- Blurred Backdrop --}}
                                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('{{ asset($event->poster) }}') center/cover no-repeat; filter: blur(15px) brightness(0.4); transform: scale(1.1);"></div>
                                    {{-- Main Image Focused --}}
                                    <img src="{{ asset($event->poster) }}" alt="Poster" style="position: relative; z-index: 1; width: 100%; height: 100%; object-fit: contain; object-position: center 20%; opacity: 0.9;">
                                    {{-- Elegant Side Fade --}}
                                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to right, rgba(0,0,0,0.6), transparent 40%, transparent 60%, rgba(0,0,0,0.3)); z-index: 2;"></div>
                                </div>
                            @endif
                            
                            <div style="position: relative; z-index: 3;">
                                <div class="ticket-event-name-large" style="text-shadow: 0 4px 10px rgba(0,0,0,0.8);">{{ $event->nama_event }}</div>
                                <div class="ticket-event-date-large" style="text-shadow: 0 2px 5px rgba(0,0,0,0.8);">
                                    {{ \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->format('d F Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="ticket-right">
                            <div>
                                <div class="ticket-detail-title">{{ $event->nama_event }}</div>
                                <div class="ticket-info-grid">
                                    <div class="info-item">
                                        <label>Date</label>
                                        <span>{{ \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->format('d M Y') }}</span>
                                    </div>
                                    <div class="info-item">
                                        <label>Venue</label>
                                        <span>{{ $event->lokasi_event }}</span>
                                    </div>
                                    <div class="info-item">
                                        <label>Category</label>
                                        <span>{{ strtoupper($item['details']->jenis_tiket) }}</span>
                                    </div>
                                    <div class="info-item">
                                        <label>Quantity</label>
                                        <span>{{ $item['quantity'] }} Ticket(s)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="order-summary">
                <h2 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; color: #fff; text-transform: uppercase; letter-spacing: 1px;">Order Summary</h2>
                @foreach($selectedTickets as $item)
                    <div class="summary-row">
                        <span>{{ $item['details']->jenis_tiket }} ({{ $item['quantity'] }}x)</span>
                        <span>Rp {{ number_format($item['details']->harga * $item['quantity'], 0, ',', '.') }}</span>
                    </div>
                @endforeach
                <div class="summary-row summary-total">
                    <span>Total Amount</span>
                    <span>Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                </div>
            </div>

            <a href="#" class="btn-payment">Select Payment Method</a>
        </div>
    </main>

</body>
</html>
