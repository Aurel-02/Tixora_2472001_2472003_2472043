<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tixora - Check In</title>
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
            padding-top: var(--topbar-height);
            min-height: calc(100vh - var(--topbar-height));
            display: flex;
            flex-direction: column;
            padding-bottom: 50px;
            align-items: center;
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
            max-width: 1200px;
            font-size: 2.2rem;
            color: #ffffff;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .checkin-container {
            width: 95%;
            max-width: 1200px;
            margin: 30px auto;
            display: grid;
            grid-template-columns: 1fr 3fr;
            gap: 25px;
        }

        .checkin-sidebar {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 20px;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            gap: 15px;
            height: fit-content;
        }

        .method-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(243, 200, 221, 0.3);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: var(--queen-pink);
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }

        .method-btn i {
            font-size: 2.5rem;
        }

        .method-btn span {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .method-btn:hover {
            background: rgba(209, 131, 169, 0.2);
            transform: translateY(-2px);
        }

        .method-btn.active {
            background: var(--middle-purple);
            color: var(--jacarta);
            border-color: var(--middle-purple);
            box-shadow: 0 0 20px rgba(209, 131, 169, 0.4);
        }

        .scanner-area {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            padding: 30px;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 500px;
            position: relative;
        }

        .scanner-box {
            width: 350px;
            height: 350px;
            border: 2px dashed rgba(209, 131, 169, 0.8);
            border-radius: 20px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: rgba(255,255,255,0.02);
            box-shadow: inset 0 0 50px rgba(0,0,0,0.5);
        }

        .scanner-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100%;
            background: linear-gradient(to bottom, transparent, rgba(168, 230, 207, 0.4), transparent);
            animation: scan 2s linear infinite;
            z-index: 10;
        }

        @keyframes scan {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100%); }
        }

        .scanner-placeholder {
            color: rgba(243, 200, 221, 0.5);
            font-size: 5rem;
            z-index: 1;
        }

        .scanner-instructions {
            margin-top: 25px;
            text-align: center;
            color: #fff;
        }

        .scanner-instructions h3 {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .scanner-instructions p {
            font-size: 1rem;
            opacity: 0.8;
            max-width: 400px;
        }

        .dummy-result {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(46, 204, 113, 0.9);
            color: #fff;
            padding: 20px 30px;
            border-radius: 12px;
            text-align: center;
            backdrop-filter: blur(10px);
            z-index: 100;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            animation: popIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        .dummy-result i {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        @keyframes popIn {
            0% { opacity: 0; transform: translate(-50%, -40%) scale(0.8); }
            100% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
        }

        @media (max-width: 900px) {
            .checkin-container {
                grid-template-columns: 1fr;
            }
            .checkin-sidebar {
                flex-direction: row;
            }
            .method-btn {
                flex: 1;
                flex-direction: row;
                padding: 15px;
            }
            .method-btn i {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <header class="topbar">
        <div class="logo">TIXORA</div>
        <div style="display: flex; align-items: center; gap: 12px;">
            <a href="{{ route('profile.edit') }}" class="profile" title="My Profile" style="text-decoration:none;">
                @if(auth()->check() && auth()->user()->photo_profile)
                    <img src="{{ asset(auth()->user()->photo_profile) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                @else
                    {{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'U', 0, 1)) }}
                @endif
            </a>
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
                    <a href="{{ route('organizer.statistik') }}" class="sidebar-item">
                        <i class="ph ph-chart-bar sidebar-icon"></i>
                        <span class="sidebar-text">Statistik Penjualan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('organizer.events.create') }}" class="sidebar-item">
                        <i class="ph ph-plus-circle sidebar-icon"></i>
                        <span class="sidebar-text">Tambah Event</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('organizer.checkin') }}" class="sidebar-item active">
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
            <span class="section-title"><i class="ph ph-scan"></i> Check In Tiket Peonton</span>
        </div>

        <div class="checkin-container">
            <div class="checkin-sidebar">
                <div class="method-btn active" id="btn-qr" onclick="switchMethod('qr')">
                    <i class="ph ph-qr-code"></i>
                    <span>Scan Tiket (QR/Barcode)</span>
                </div>
                <div class="method-btn" id="btn-face" onclick="switchMethod('face')">
                    <i class="ph ph-scan-smiley"></i>
                    <span>Scan Wajah (Face Sync)</span>
                </div>
            </div>

            <!-- Scanner Area -->
            <div class="scanner-area">
                
                <div class="scanner-box" id="scanner-box" onclick="simulateScan()">
                    <i class="ph ph-qr-code scanner-placeholder" id="scanner-icon"></i>
                </div>

                <div class="scanner-instructions">
                    <h3 id="inst-title">Arahkan Tiket Konser</h3>
                    <p id="inst-desc">Pusatkan QR Code atau Barcode tiket ke dalam area kotak di atas untuk melakukan check in otomatis.</p>
                </div>

                <!-- Notif success trigger for demonstration -->
                <div class="dummy-result" id="dummy-success">
                    <i class="ph ph-check-circle"></i>
                    <h2>Check In Berhasil</h2>
                    <p style="margin-top: 5px; opacity: 0.9;">Atas nama <strong>Alina (VIP)</strong></p>
                </div>

            </div>
        </div>

    </main>

    <script>
        function switchMethod(method) {
            const btnQr = document.getElementById('btn-qr');
            const btnFace = document.getElementById('btn-face');
            const scannerIcon = document.getElementById('scanner-icon');
            const instTitle = document.getElementById('inst-title');
            const instDesc = document.getElementById('inst-desc');

            if (method === 'qr') {
                btnQr.classList.add('active');
                btnFace.classList.remove('active');
                scannerIcon.className = 'ph ph-qr-code scanner-placeholder';
                instTitle.innerText = 'Arahkan Tiket Konser';
                instDesc.innerText = 'Pusatkan QR Code atau Barcode tiket ke dalam area kotak di atas untuk melakukan check in otomatis.';
            } else {
                btnFace.classList.add('active');
                btnQr.classList.remove('active');
                scannerIcon.className = 'ph ph-user-focus scanner-placeholder';
                instTitle.innerText = 'Posisikan Wajah';
                instDesc.innerText = 'Biarkan kamera mendeteksi wajah penonton yang telah terdaftar pada sistem biometrik Tixora.';
            }
        }

        function simulateScan() {
            const dummy = document.getElementById('dummy-success');
            dummy.style.display = 'block';
            setTimeout(() => {
                dummy.style.display = 'none';
            }, 3000);
        }
    </script>
</body>
</html>
