<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tixora - Select Seat</title>
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

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
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

        .topbar .profile:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(209, 131, 169, 0.6);
        }

        .main-wrapper {
            padding-top: calc(var(--topbar-height) + 20px);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-bottom: 20px;
        }

        .content-container {
            width: 100%;
            max-width: 1000px;
            padding: 0 20px;
        }

        .page-header {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-back {
            color: var(--queen-pink);
            font-size: 1.5rem;
            text-decoration: none;
            transition: color 0.3s;
            display: flex;
            align-items: center;
        }
        
        .btn-back:hover {
            color: #fff;
        }

        .page-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #fff;
        }

        .grid-layout {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 20px;
        }

        .glass-box {
            background: rgba(113, 85, 122, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(243, 200, 221, 0.15);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
        }

        .seat-map {
            flex: 1;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            border: 1px solid rgba(243, 200, 221, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 300px;
            color: rgba(243, 200, 221, 0.5);
            font-size: 1.2rem;
            flex-direction: column;
            gap: 15px;
        }

        .seat-map i {
            font-size: 4rem;
            color: var(--middle-purple);
        }

        .seat-map-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .stage-box {
            background: #dcdcdc;
            color: #000;
            font-weight: 800;
            padding: 8px 40px;
            margin-bottom: 10px;
            text-align: center;
            letter-spacing: 2px;
            border-bottom: 4px solid #b0b0b0;
            border-radius: 4px;
            font-size: 0.8rem;
        }

        .arena {
            display: flex;
            gap: 10px;
            align-items: stretch;
            justify-content: center;
        }

        .wing {
            width: 35px;
            background: #e5b3c9; /* Pinkish */
            color: #000;
            font-weight: 700;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            writing-mode: vertical-rl;
            text-orientation: mixed;
            transform: rotate(180deg);
            border-left: 4px solid #c895ab; /* acts as bottom border due to rotation */
            text-align: center;
            border-radius: 4px;
            letter-spacing: 1px;
        }

        .center-blocks {
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: center;
        }

        .row-blocks {
            display: flex;
            gap: 8px;
        }

        .seat-block {
            width: 100px;
            height: 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.7rem;
            color: #000;
            text-align: center;
            border-radius: 4px;
        }

        .vvip-block {
            background: #f9e596; 
            border-bottom: 4px solid #d6b656;
        }

        .vip-block {
            background: #94c4e0; 
            border-bottom: 4px solid #7ba5bf;
        }

        .regular-block {
            background: #84d8a5; 
            border-bottom: 4px solid #6cb288;
        }

        .tribune-box {
            width: 100%;
            max-width: 320px;
            height: 35px;
            background: #84d8a5; 
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.8rem;
            color: #000;
            margin-top: 8px;
            border-bottom: 4px solid #6cb288;
            border-radius: 4px;
            letter-spacing: 1px;
        }

        .ticket-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .ticket-item {
            background: rgba(58, 52, 91, 0.4);
            border: 1px solid rgba(243, 200, 221, 0.1);
            border-radius: 10px;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.2s, background 0.2s;
        }

        .ticket-item:hover {
            background: rgba(58, 52, 91, 0.6);
            transform: translateY(-2px);
        }

        .ticket-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .ticket-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
        }

        .ticket-price {
            font-size: 1rem;
            color: var(--middle-purple);
            font-weight: 500;
        }

        .ticket-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-qty {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 1px solid var(--middle-purple);
            background: transparent;
            color: var(--queen-pink);
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-qty:hover {
            background: var(--middle-purple);
            color: var(--jacarta);
        }

        .btn-qty:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: transparent;
            color: var(--queen-pink);
        }

        .ticket-qty {
            font-size: 1.2rem;
            font-weight: 600;
            color: #fff;
            min-width: 20px;
            text-align: center;
        }

        .checkout-box {
            margin-top: 15px;
            border-top: 1px solid rgba(243, 200, 221, 0.1);
            padding-top: 15px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .total-label {
            font-size: 1.2rem;
            color: var(--queen-pink);
        }

        .total-amount {
            font-size: 1.6rem;
            font-weight: 700;
            color: #fff;
        }

        .btn-checkout {
            display: block;
            width: 100%;
            padding: 12px;
            background: var(--brown-chocolate);
            color: var(--queen-pink);
            border: 1px solid var(--middle-purple);
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-checkout:hover {
            background: var(--middle-purple);
            color: var(--jacarta);
            box-shadow: 0 0 15px rgba(209, 131, 169, 0.6);
            transform: translateY(-2px);
        }

        .btn-checkout.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .grid-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <header class="topbar">
        <div class="topbar-left">
            <a href="/dashboard" class="logo">TIXORA</a>
        </div>
        <a href="{{ route('profile.edit') }}" class="profile" title="My Profile" style="text-decoration:none;">
            @if(auth()->user()->photo_profile)
                <img src="{{ asset(auth()->user()->photo_profile) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            @else
                {{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'U', 0, 1)) }}
            @endif
        </a>
    </header>

    <main class="main-wrapper">
        <div class="content-container">
            <div class="page-header">
                <a href="{{ route('event.detail', $event->id ?? $event->id_event) }}" class="btn-back">
                    <i class="ph ph-arrow-left"></i>
                </a>
                <h1 class="page-title">Select Seats</h1>
            </div>

            <div class="grid-layout">
                <div class="glass-box">
                    <h2 style="font-size: 1.2rem; margin-bottom: 20px; color: #fff;">Event Stage Area</h2>
                    <div class="seat-map-container">
                        <div class="stage-box">STAGE</div>
                        
                        <div class="arena">
                            @if(in_array('FESTIVAL', $availableTypes))
                            <div class="wing">FESTIVAL</div>
                            @endif

                            <div class="center-blocks">
                                @if(in_array('VVIP', $availableTypes))
                                <div class="row-blocks">
                                    <div class="seat-block vvip-block">VVIP</div>
                                    <div class="seat-block vvip-block">VVIP</div>
                                </div>
                                @endif
                                @if(in_array('VIP', $availableTypes))
                                <div class="row-blocks">
                                    <div class="seat-block vip-block">VIP</div>
                                    <div class="seat-block vip-block">VIP</div>
                                </div>
                                @endif
                                @if(in_array('REGULAR', $availableTypes) || in_array('REGULER', $availableTypes))
                                <div class="row-blocks">
                                    <div class="seat-block regular-block">REGULAR</div>
                                    <div class="seat-block regular-block">REGULAR</div>
                                </div>
                                @endif
                            </div>

                            @if(in_array('FESTIVAL', $availableTypes))
                            <div class="wing">FESTIVAL</div>
                            @endif
                        </div>

                        @if(in_array('REGULAR', $availableTypes) || in_array('REGULER', $availableTypes))
                        <div class="tribune-box">REGULAR</div>
                        @endif

                        <div style="display: flex; justify-content: center; gap: 20px; width: 100%; margin-top: 30px; font-size: 0.8rem; flex-wrap: wrap;">
                            @if(in_array('VVIP', $availableTypes))
                            <div style="display:flex; align-items:center; gap:8px;"><span style="display:inline-block; width:15px; height:15px; background:#f9e596; border-radius:2px;"></span> VVIP</div>
                            @endif
                            @if(in_array('VIP', $availableTypes))
                            <div style="display:flex; align-items:center; gap:8px;"><span style="display:inline-block; width:15px; height:15px; background:#94c4e0; border-radius:2px;"></span> VIP</div>
                            @endif
                            @if(in_array('FESTIVAL', $availableTypes))
                            <div style="display:flex; align-items:center; gap:8px;"><span style="display:inline-block; width:15px; height:15px; background:#e5b3c9; border-radius:2px;"></span> FESTIVAL</div>
                            @endif
                            @if(in_array('REGULAR', $availableTypes) || in_array('REGULER', $availableTypes))
                            <div style="display:flex; align-items:center; gap:8px;"><span style="display:inline-block; width:15px; height:15px; background:#84d8a5; border-radius:2px;"></span> REGULAR</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="glass-box">
                    <h2 style="font-size: 1.2rem; margin-bottom: 20px; color: #fff;">Choose Category & Quantity</h2>
                    
                    <form action="{{ route('event.checkout', $event->id_event) }}" method="POST" id="checkoutForm">
                        @csrf
                        <div class="ticket-list" id="ticketList">
                        @foreach($tikets as $tiket)
                         <div class="ticket-item" data-price="{{ $tiket->harga }}" data-id="{{ $tiket->id_tiket }}">
                            <div class="ticket-info">
                                <span class="ticket-name">{{ strtoupper($tiket->jenis_tiket) }}</span>
                                <span class="ticket-price">Rp {{ number_format($tiket->harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="ticket-controls">
                                <button type="button" class="btn-qty btn-minus" disabled><i class="ph ph-minus"></i></button>
                                <span class="ticket-qty">0</span>
                                <input type="hidden" name="quantities[{{ $tiket->id_tiket }}]" value="0" class="qty-input">
                                <button type="button" class="btn-qty btn-plus"><i class="ph ph-plus"></i></button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    </form>

                    <div class="checkout-box">
                        <div class="total-row">
                            <span class="total-label">Subtotal</span>
                            <span class="total-amount" id="totalPrice">Rp 0</span>
                        </div>
                        <button type="submit" form="checkoutForm" class="btn-checkout disabled" id="btnCheckout">Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ticketItems = document.querySelectorAll('.ticket-item');
            const totalPriceEl = document.getElementById('totalPrice');
            const btnCheckout = document.getElementById('btnCheckout');
            let totalAmount = 0;
            let totalTickets = 0;

            const formatRupiah = (number) => {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);
            };

            const updateTotal = () => {
                totalAmount = 0;
                totalTickets = 0;

                ticketItems.forEach(item => {
                    const price = parseInt(item.getAttribute('data-price'));
                    const qty = parseInt(item.querySelector('.ticket-qty').textContent);
                    totalAmount += (price * qty);
                    totalTickets += qty;
                });

                totalPriceEl.textContent = formatRupiah(totalAmount);

                if (totalTickets > 0) {
                    btnCheckout.classList.remove('disabled');
                } else {
                    btnCheckout.classList.add('disabled');
                }
            };

            ticketItems.forEach(item => {
                const btnPlus = item.querySelector('.btn-plus');
                const btnMinus = item.querySelector('.btn-minus');
                const qtyEl = item.querySelector('.ticket-qty');

                btnPlus.addEventListener('click', () => {
                    let currentQty = parseInt(qtyEl.textContent);
                    if (currentQty < 5) {
                        currentQty++;
                        qtyEl.textContent = currentQty;
                        item.querySelector('.qty-input').value = currentQty;
                        btnMinus.disabled = false;
                        updateTotal();
                    }
                });

                btnMinus.addEventListener('click', () => {
                    let currentQty = parseInt(qtyEl.textContent);
                    if (currentQty > 0) {
                        currentQty--;
                        qtyEl.textContent = currentQty;
                        item.querySelector('.qty-input').value = currentQty;
                        if (currentQty === 0) {
                            btnMinus.disabled = true;
                        }
                        updateTotal();
                    }
                });
            });
        });
    </script>
</body>
</html>
