<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tixora - Checkout Details</title>
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

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
            z-index: 1200;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .payment-modal {
            background: rgba(58, 52, 91, 0.95);
            border: 1px solid rgba(243, 200, 221, 0.2);
            border-radius: 24px;
            width: 100%;
            max-width: 500px;
            padding: 40px;
            transform: translateY(30px);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        }

        .modal-overlay.active .payment-modal {
            transform: translateY(0);
        }

        .modal-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .modal-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 5px;
        }

        .modal-subtitle {
            font-size: 0.9rem;
            color: var(--queen-pink);
            opacity: 0.8;
        }

        .payment-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }

        .payment-option-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(243, 200, 221, 0.1);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .payment-option-card:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--middle-purple);
            transform: translateY(-5px);
        }

        .payment-option-card.selected {
            background: rgba(209, 131, 169, 0.15);
            border-color: var(--middle-purple);
            box-shadow: 0 0 15px rgba(209, 131, 169, 0.3);
        }

        .payment-icon {
            font-size: 2.2rem;
            margin-bottom: 12px;
            display: block;
        }

        .payment-name {
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
        }

        .icon-dana { color: #0087FF; }
        .icon-gopay { color: #00AED6; }
        .icon-bank { color: #8A5CF5; }
        .icon-qris { color: #FFB800; }

        .btn-confirm-payment {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--middle-purple), var(--old-lavender));
            color: #fff;
            border: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 1px;
        }

        .btn-confirm-payment:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            filter: grayscale(1);
        }

        .btn-confirm-payment:not(:disabled):hover {
            transform: scale(1.02);
            box-shadow: 0 10px 20px rgba(209, 131, 169, 0.3);
        }

        .close-modal {
            position: absolute;
            top: 20px;
            right: 20px;
            color: var(--queen-pink);
            font-size: 1.5rem;
            cursor: pointer;
            transition: color 0.3s;
        }

        .close-modal:hover {
            color: #fff;
        }

        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
        }

        .payment-details-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(243, 200, 221, 0.1);
        }

        .payment-detail-value {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--middle-purple);
            margin: 15px 0;
            letter-spacing: 2px;
            font-family: monospace;
        }

        .payment-timer {
            font-size: 1.1rem;
            font-weight: 600;
            color: #ff4d4d;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .qr-placeholder {
            width: 200px;
            height: 200px;
            background: #fff;
            border-radius: 12px;
            margin: 0 auto 20px;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
        }

        .qr-placeholder img {
            max-width: 100%;
            height: auto;
        }

        .success-animation {
            text-align: center;
            padding: 20px 0;
        }

        .success-icon {
            font-size: 5rem;
            color: #4BB543;
            margin-bottom: 20px;
            animation: scaleIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes scaleIn {
            from { transform: scale(0); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
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
            .payment-modal {
                padding: 30px 20px;
                border-radius: 24px 24px 0 0;
                position: absolute;
                bottom: 0;
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
                        <div class="ticket-left" style="position: relative; overflow: hidden; background: #000;">
                            @if($event && $event->poster)
                                <img src="{{ asset($event->poster) }}" alt="Poster" 
                                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.6;">
                            @endif
                            <div style="position: relative; z-index: 2;">
                                <div class="ticket-event-name-large" style="text-shadow: 0 2px 10px rgba(0,0,0,0.8);">{{ $event->nama_event ?? 'Event Name' }}</div>
                                <div class="ticket-event-date-large" style="text-shadow: 0 2px 5px rgba(0,0,0,0.8);">
                                    {{ isset($event->tanggal_pelaksanaan) ? \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->format('d F Y') : 'Date TBD' }}
                                </div>
                            </div>
                        </div>
                        <div class="ticket-right">
                            <div>
                                <div class="ticket-detail-title">{{ $event->nama_event ?? 'Event Name' }}</div>
                                <div class="ticket-info-grid">
                                    <div class="info-item">
                                        <label>Date</label>
                                        <span>{{ isset($event->tanggal_pelaksanaan) ? \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->format('d M Y') : 'TBD' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <label>Venue</label>
                                        <span>{{ $event->lokasi_event ?? 'Venue TBD' }}</span>
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

            <button id="openPaymentModal" class="btn-payment">Select Payment Method</button>
        </div>
    </main>

    <div class="modal-overlay" id="paymentOverlay">
        <div class="payment-modal">
            <i class="ph ph-x close-modal" id="closePaymentModal"></i>
            
            <div id="stepSelection" class="step-content active">
                <div class="modal-header">
                    <h2 class="modal-title">Payment Method</h2>
                    <p class="modal-subtitle">Choose your preferred payment way</p>
                </div>
                
                <div class="payment-options">
                    <div class="payment-option-card" data-method="dana">
                        <i class="ph ph-wallet payment-icon icon-dana"></i>
                        <span class="payment-name">DANA</span>
                    </div>
                    <div class="payment-option-card" data-method="gopay">
                        <i class="ph ph-wallet payment-icon icon-gopay"></i>
                        <span class="payment-name">GoPay</span>
                    </div>
                    <div class="payment-option-card" data-method="bank">
                        <i class="ph ph-bank payment-icon icon-bank"></i>
                        <span class="payment-name">Bank Transfer</span>
                    </div>
                    <div class="payment-option-card" data-method="qris">
                        <i class="ph ph-qr-code payment-icon icon-qris"></i>
                        <span class="payment-name">QRIS</span>
                    </div>
                </div>

                <button type="button" class="btn-confirm-payment" id="goToDetailsBtn" disabled>
                    Proceed to Payment
                </button>
            </div>

            <div id="stepDetails" class="step-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="detailTitle">Payment Details</h2>
                    <p class="modal-subtitle">Complete your transaction before timeout</p>
                </div>

                <div class="payment-details-container">
                    <div class="payment-timer">
                        <i class="ph ph-timer"></i>
                        <span id="paymentTimerDisplay">23 : 59 : 59</span>
                    </div>
                    
                    <div id="phonePaymentInfo" style="display: none;">
                        <p style="font-size: 0.9rem; color: var(--queen-pink); opacity: 0.8;">Transfer to Phone Number:</p>
                        <div class="payment-detail-value">0812 - 3456 - 7890</div>
                    </div>

                    <div id="bankPaymentInfo" style="display: none;">
                        <p style="font-size: 0.9rem; color: var(--queen-pink); opacity: 0.8;">Virtual Account Number (BCA):</p>
                        <div class="payment-detail-value">8077 0812 3456 7890</div>
                    </div>

                    <div id="qrisPaymentInfo" style="display: none;">
                        <div class="qr-placeholder" style="background: #fff; padding: 15px; border-radius: 8px;">
                            <img src="{{ asset('payment/qris_simple.png') }}" alt="QRIS Dummy" style="width: 100%; height: auto;">
                        </div>
                        <p style="font-size: 0.8rem; color: var(--queen-pink); opacity: 0.7;">Scan this barcode to pay</p>
                    </div>
                </div>

                <button type="button" class="btn-confirm-payment" id="donePaymentBtn">
                    DONE
                </button>
            </div>

            <div id="stepSuccess" class="step-content">
                <div class="success-animation">
                    <i class="ph ph-check-circle success-icon"></i>
                    <h2 class="modal-title" style="margin-bottom: 10px;">Payment Successful!</h2>
                    <p class="modal-subtitle">Your tickets will be sent to your email shortly.</p>
                </div>
                
                <form action="{{ route('checkout.process') }}" method="POST" id="finalForm">
                    @csrf
                    <input type="hidden" name="payment_method" id="finalPaymentMethod">
                    @foreach($selectedTickets as $item)
                        <input type="hidden" name="tickets[{{ $item['details']->id_tiket }}]" value="{{ $item['quantity'] }}">
                    @endforeach
                    <button type="submit" class="btn-confirm-payment" style="margin-top: 30px;">
                        RETURN TO DASHBOARD
                    </button>
                </form>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openBtn = document.getElementById('openPaymentModal');
            const closeBtn = document.getElementById('closePaymentModal');
            const overlay = document.getElementById('paymentOverlay');
            const cards = document.querySelectorAll('.payment-option-card');
            const goToDetailsBtn = document.getElementById('goToDetailsBtn');
            const donePaymentBtn = document.getElementById('donePaymentBtn');
            const detailTitle = document.getElementById('detailTitle');
            
            let selectedMethod = '';
            let timerInterval;
            let isExpired = false;

            // Modal Controls
            openBtn.addEventListener('click', () => {
                overlay.classList.add('active');
            });

            const closeModal = () => {
                overlay.classList.remove('active');
                if(timerInterval) clearInterval(timerInterval);
                // Reset to step 1
                switchStep('stepSelection');
            };

            closeBtn.addEventListener('click', closeModal);
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) closeModal();
            });

            const switchStep = (stepId) => {
                document.querySelectorAll('.step-content').forEach(s => s.classList.remove('active'));
                document.getElementById(stepId).classList.add('active');
            };

            cards.forEach(card => {
                card.addEventListener('click', () => {
                    cards.forEach(c => c.classList.remove('selected'));
                    card.classList.add('selected');
                    selectedMethod = card.getAttribute('data-method');
                    goToDetailsBtn.disabled = false;
                });
            });

            goToDetailsBtn.addEventListener('click', () => {
                switchStep('stepDetails');
                
                document.getElementById('phonePaymentInfo').style.display = 'none';
                document.getElementById('bankPaymentInfo').style.display = 'none';
                document.getElementById('qrisPaymentInfo').style.display = 'none';
                
                detailTitle.innerHTML = `Pay with ${selectedMethod.toUpperCase()}`;
                
                if (selectedMethod === 'dana' || selectedMethod === 'gopay') {
                    document.getElementById('phonePaymentInfo').style.display = 'block';
                } else if (selectedMethod === 'bank') {
                    document.getElementById('bankPaymentInfo').style.display = 'block';
                } else if (selectedMethod === 'qris') {
                    document.getElementById('qrisPaymentInfo').style.display = 'block';
                }
                
                startPaymentTimer();
            });

            donePaymentBtn.addEventListener('click', () => {
                if (isExpired) return;
                switchStep('stepSuccess');
                if(timerInterval) clearInterval(timerInterval);
                document.getElementById('finalPaymentMethod').value = selectedMethod;
            });

            function startPaymentTimer() {
                isExpired = false;
                donePaymentBtn.disabled = false;
                if(timerInterval) clearInterval(timerInterval);
                let timeLeft = 5; 
                const display = document.getElementById('paymentTimerDisplay');
                
                timerInterval = setInterval(() => {
                    const m = Math.floor(timeLeft / 60);
                    const s = timeLeft % 60;
                    
                    const pad = (num) => String(num).padStart(2, '0');
                    display.innerText = `${pad(m)} : ${pad(s)}`;
                    
                    if (timeLeft <= 0) {
                        isExpired = true;
                        donePaymentBtn.disabled = true;
                        clearInterval(timerInterval);
                        display.innerText = "EXPIRED";
                        Swal.fire({
                            icon: 'error',
                            title: 'Expired',
                            text: 'Waktu expired dan payment gagal',
                            confirmButtonColor: '#D183A9',
                            background: '#3A345B',
                            color: '#F3C8DD'
                        }).then(() => {
                            window.location.href = '/dashboard';
                        });
                    }
                    timeLeft--;
                }, 1000);
            }
        });
    </script>

</body>
</html>
