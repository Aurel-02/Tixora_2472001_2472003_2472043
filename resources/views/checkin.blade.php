<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tixora - Check In</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>
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
            --green: #84d8a5;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }

        body {
            background: linear-gradient(135deg, var(--jacarta), var(--brown-chocolate));
            color: var(--queen-pink);
            min-height: 100vh;
            overflow-x: hidden;
            background-attachment: fixed;
        }

        .topbar {
            position: fixed; top: 0; left: var(--sidebar-width-collapsed); right: 0;
            height: var(--topbar-height);
            background: rgba(58,52,91,0.6); backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(243,200,221,0.1);
            display: flex; justify-content: space-between; align-items: center;
            padding: 0 40px; z-index: 900;
        }
        .topbar .logo { font-size: 1.8rem; font-weight: 700; letter-spacing: 2px; color: var(--queen-pink); text-shadow: 0 0 10px rgba(243,200,221,0.5); text-transform: uppercase; }
        .topbar .profile { width: 42px; height: 42px; border-radius: 50%; background: var(--middle-purple); color: var(--jacarta); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem; cursor: pointer; box-shadow: 0 0 10px rgba(209,131,169,0.4); transition: transform 0.3s; text-transform: uppercase; overflow: hidden; }

        .sidebar { position: fixed; top: 0; left: 0; width: var(--sidebar-width-collapsed); height: 100vh; background: rgba(113,85,122,0.4); backdrop-filter: blur(15px); border-right: 1px solid rgba(243,200,221,0.15); z-index: 1000; transition: width 0.3s ease; overflow: hidden; display: flex; flex-direction: column; padding-top: var(--topbar-height); }
        .sidebar:hover { width: var(--sidebar-width-expanded); box-shadow: 5px 0 20px rgba(0,0,0,0.4); }
        .sidebar-menu { list-style: none; margin-top: 20px; display: flex; flex-direction: column; gap: 10px; }
        .sidebar-item { display: flex; align-items: center; padding: 15px 22px; color: var(--queen-pink); text-decoration: none; transition: background 0.2s, border-left 0.2s; border-left: 3px solid transparent; cursor: pointer; white-space: nowrap; }
        .sidebar-item:hover, .sidebar-item.active { background: rgba(209,131,169,0.2); border-left: 3px solid var(--middle-purple); }
        .sidebar-icon { font-size: 1.4rem; min-width: 25px; margin-right: 20px; }
        .sidebar-text { font-size: 1rem; font-weight: 500; opacity: 0; transition: opacity 0.3s ease; }
        .sidebar:hover .sidebar-text { opacity: 1; }

        .main-wrapper {
            margin-left: var(--sidebar-width-collapsed);
            padding-top: var(--topbar-height);
            min-height: 100vh;
            display: flex; flex-direction: column; align-items: center;
            padding-bottom: 60px;
        }
        .section-header {
            margin: 30px auto 0; width: 95%; max-width: 1200px;
            padding: 20px 25px;
            background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.2);
            border-radius: 14px; backdrop-filter: blur(8px);
            font-size: 2rem; font-weight: 700; color: #fff;
            display: flex; align-items: center; gap: 15px;
            text-shadow: 0 0 10px rgba(243,200,221,0.4);
        }

        .checkin-container {
            width: 95%; max-width: 1200px; margin: 25px auto;
            display: grid; grid-template-columns: 230px 1fr; gap: 25px;
        }

        .method-panel {
            background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12);
            border-radius: 16px; padding: 20px; height: fit-content;
            display: flex; flex-direction: column; gap: 15px;
        }
        .method-panel h4 { font-size: 0.82rem; text-transform: uppercase; letter-spacing: 2px; opacity: 0.5; }
        .method-btn {
            background: rgba(255,255,255,0.06); border: 1px solid rgba(243,200,221,0.22);
            border-radius: 12px; padding: 18px 15px;
            display: flex; flex-direction: column; align-items: center; gap: 8px;
            cursor: pointer; transition: all 0.3s; text-align: center; color: var(--queen-pink);
        }
        .method-btn i { font-size: 2.2rem; }
        .method-btn span { font-weight: 600; font-size: 0.95rem; }
        .method-btn:hover { background: rgba(209,131,169,0.15); transform: translateY(-2px); }
        .method-btn.active { background: var(--middle-purple); color: var(--jacarta); border-color: var(--middle-purple); box-shadow: 0 0 20px rgba(209,131,169,0.4); }

        .scanner-panel {
            background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px; padding: 35px;
            display: flex; flex-direction: column; align-items: center;
            min-height: 560px; gap: 20px;
        }
        .panel-label { color: rgba(255,255,255,0.45); font-size: 0.85rem; letter-spacing: 1.5px; text-transform: uppercase; }

        #qr-panel { width: 100%; display: flex; flex-direction: column; align-items: center; gap: 18px; }

        .qr-cam-wrap {
            position: relative;
            width: 320px; height: 320px;
            border-radius: 18px; overflow: hidden;
            border: 2px solid rgba(209,131,169,0.5);
            background: #000;
            box-shadow: 0 0 30px rgba(209,131,169,0.15);
        }
        #qr-video { width: 100%; height: 100%; object-fit: cover; }
        .qr-cam-wrap::before, .qr-cam-wrap::after {
            content: ''; position: absolute; width: 40px; height: 40px;
            border-color: var(--green); border-style: solid; z-index: 3;
        }
        .qr-cam-wrap::before { top: 10px; left: 10px; border-width: 3px 0 0 3px; border-radius: 4px 0 0 0; }
        .qr-cam-wrap::after  { bottom: 10px; right: 10px; border-width: 0 3px 3px 0; border-radius: 0 0 4px 0; }
        .qr-corner-tr, .qr-corner-bl {
            position: absolute; width: 40px; height: 40px; z-index: 3;
            border-color: var(--green); border-style: solid;
        }
        .qr-corner-tr { top: 10px; right: 10px; border-width: 3px 3px 0 0; border-radius: 0 4px 0 0; }
        .qr-corner-bl { bottom: 10px; left: 10px; border-width: 0 0 3px 3px; border-radius: 0 0 0 4px; }

        .qr-scanline {
            position: absolute; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, transparent, var(--green), transparent);
            box-shadow: 0 0 8px var(--green);
            animation: scanline 2.5s ease-in-out infinite;
            z-index: 2;
        }
        @keyframes scanline { 0%,100%{ top: 5%; } 50%{ top: 90%; } }

        #qr-status-overlay {
            position: absolute; inset: 0; display: none;
            align-items: center; justify-content: center;
            background: rgba(0,0,0,0.7); z-index: 10; border-radius: 16px;
        }
        #qr-status-overlay.show { display: flex; }
        #qr-status-icon { font-size: 5rem; }

        .cam-info-bar {
            display: flex; align-items: center; gap: 8px;
            color: rgba(243,200,221,0.6); font-size: 0.85rem;
        }
        #cam-status-dot { width: 8px; height: 8px; border-radius: 50%; background: #ffa726; }
        #cam-status-dot.active { background: var(--green); animation: pulse 1.5s ease-in-out infinite; }
        @keyframes pulse { 0%,100%{ box-shadow: 0 0 0 0 rgba(132,216,165,0.6); } 50%{ box-shadow: 0 0 0 6px rgba(132,216,165,0); } }

        .or-divider { display: flex; align-items: center; gap: 15px; width: 100%; max-width: 420px; }
        .or-divider::before, .or-divider::after { content:''; flex:1; height:1px; background: rgba(243,200,221,0.15); }
        .or-divider span { color: rgba(243,200,221,0.4); font-size: 0.85rem; white-space: nowrap; }

        .qr-input-wrap { width: 100%; max-width: 420px; display: flex; gap: 10px; }
        .qr-input-wrap input {
            flex: 1; padding: 13px 16px; border-radius: 10px;
            background: rgba(58,52,91,0.5); border: 1px solid rgba(243,200,221,0.25);
            color: #fff; font-size: 0.95rem; outline: none; transition: border-color 0.3s;
        }
        .qr-input-wrap input:focus { border-color: var(--middle-purple); }
        .qr-input-wrap input::placeholder { color: rgba(243,200,221,0.35); }
        .btn-scan {
            padding: 13px 22px; border-radius: 10px;
            background: var(--middle-purple); color: var(--jacarta);
            border: none; cursor: pointer; font-size: 0.95rem; font-weight: 700;
            transition: all 0.3s; display: flex; align-items: center; gap: 6px;
        }
        .btn-scan:hover { background: var(--queen-pink); transform: translateY(-2px); }

        #ticket-result { display: none; width: 100%; max-width: 640px; }
        .ticket-card {
            background: rgba(132,216,165,0.07); border: 1px solid rgba(132,216,165,0.35);
            border-radius: 18px; padding: 22px; display: flex; gap: 18px;
            animation: fadeUp 0.4s ease;
        }
        @keyframes fadeUp { from{ opacity:0; transform:translateY(18px); } to{ opacity:1; transform:translateY(0); } }
        .ticket-poster {
            width: 110px; min-width: 110px; height: 110px; border-radius: 12px;
            overflow: hidden; background: rgba(255,255,255,0.05);
            display: flex; align-items: center; justify-content: center;
        }
        .ticket-poster img { width:100%; height:100%; object-fit:cover; }
        .ticket-poster i { font-size: 2.8rem; color: rgba(243,200,221,0.25); }
        .ticket-info .event-name { font-size: 1.3rem; font-weight: 700; color: #fff; margin-bottom: 6px; }
        .info-row { font-size: 0.88rem; color: var(--queen-pink); display: flex; align-items: center; gap: 7px; margin-bottom: 4px; opacity: 0.85; }
        .info-row i { color: var(--middle-purple); }
        .ticket-badge { display: inline-flex; align-items: center; gap: 5px; background: rgba(132,216,165,0.18); color: var(--green); border: 1px solid rgba(132,216,165,0.35); border-radius: 50px; padding: 4px 13px; font-size: 0.82rem; font-weight:600; margin-top: 7px; }
        .ticket-badge-used { background: rgba(255,167,38,0.18); color: #ffa726; border-color: rgba(255,167,38,0.35); }
        .btn-confirm {
            margin-top: 12px; padding: 11px 24px; background: var(--green); color: #1a3020;
            border: none; border-radius: 10px; font-weight: 700; font-size: 0.95rem;
            cursor: pointer; transition: all 0.3s; display: inline-flex; align-items: center; gap: 7px;
        }
        .btn-confirm:hover { background: #6ccf93; transform: translateY(-2px); }
        .btn-confirm:disabled { opacity: 0.45; cursor: not-allowed; transform: none; }

        #face-panel { display: none; width: 100%; flex-direction: column; align-items: center; gap: 20px; }

        #face-loading { display: none; flex-direction: column; align-items: center; gap: 14px; color: var(--queen-pink); }
        .spinner { width: 42px; height: 42px; border: 4px solid rgba(209,131,169,0.2); border-top-color: var(--middle-purple); border-radius: 50%; animation: spin 0.8s linear infinite; }
        @keyframes spin { to{ transform:rotate(360deg); } }

        #face-cam-wrapper { display: flex; flex-direction: column; align-items: center; gap: 16px; }
        .face-cam-wrap {
            width: 270px; height: 270px; border-radius: 50%; overflow: hidden;
            border: 4px solid var(--middle-purple);
            box-shadow: 0 0 30px rgba(209,131,169,0.35);
            position: relative; background: #000;
        }
        #face-video { width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); }
        .face-scanline {
            position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, transparent, rgba(132,216,165,0.9), transparent);
            box-shadow: 0 0 8px var(--green);
            animation: scanline 2.5s ease-in-out infinite; z-index: 2;
        }
        .cam-actions { display: flex; gap: 14px; align-items: center; }
        .btn-capture {
            padding: 13px 36px; background: var(--middle-purple); color: var(--jacarta);
            border: none; border-radius: 50px; font-weight: 700; font-size: 1rem;
            cursor: pointer; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px;
            box-shadow: 0 5px 15px rgba(209,131,169,0.3);
        }
        .btn-capture:hover { background: var(--queen-pink); transform: translateY(-2px); }
        .btn-upload {
            width: 48px; height: 48px; border-radius: 50%; cursor: pointer;
            background: rgba(243,200,221,0.08); border: 1px solid var(--middle-purple);
            color: var(--queen-pink); display: flex; align-items: center; justify-content: center;
            transition: all 0.3s;
        }
        .btn-upload:hover { background: rgba(209,131,169,0.2); }
        canvas#face-canvas { display: none; }

        #face-result { display: none; width: 100%; max-width: 500px; }
        .face-match-card {
            background: rgba(132,216,165,0.07); border: 2px solid rgba(132,216,165,0.4);
            border-radius: 20px; padding: 30px; text-align: center;
            animation: fadeUp 0.4s ease;
        }
        .face-photos-row {
            display: flex; align-items: center; justify-content: center; gap: 22px;
            margin-bottom: 20px;
        }
        .face-photo-wrap {
            width: 100px; height: 100px; border-radius: 50%; overflow: hidden;
            border: 3px solid rgba(132,216,165,0.5);
            background: rgba(255,255,255,0.05);
            display: flex; align-items: center; justify-content: center;
        }
        .face-photo-wrap img { width:100%; height:100%; object-fit:cover; }
        .face-photo-wrap i { font-size: 2.5rem; color: rgba(243,200,221,0.25); }
        .face-center-icon { font-size: 2rem; color: var(--green); }
        .face-match-title { font-size: 1.9rem; font-weight: 800; color: var(--green); margin-bottom: 6px; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .face-match-name  { font-size: 1.05rem; font-weight: 600; color: #fff; }
        .face-match-sub   { font-size: 0.88rem; color: var(--queen-pink); opacity: 0.75; margin-top: 3px; }
        .btn-reset { margin-top: 18px; padding: 10px 24px; background: rgba(243,200,221,0.1); color: var(--queen-pink); border: 1px solid rgba(243,200,221,0.25); border-radius: 50px; cursor: pointer; font-weight: 600; transition: all 0.3s; }
        .btn-reset:hover { background: rgba(209,131,169,0.2); }

        .scan-hint { color: rgba(255,255,255,0.5); font-size: 0.9rem; text-align: center; max-width: 420px; line-height: 1.5; }

        @media (max-width: 900px) {
            .checkin-container { grid-template-columns: 1fr; }
            .method-panel { flex-direction: row; }
            .method-btn { flex: 1; flex-direction: row; padding: 12px; }
        }
    </style>
</head>
<body>

<header class="topbar">
    <div class="logo">TIXORA</div>
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="{{ route('profile.edit') }}" class="profile" title="My Profile" style="text-decoration:none;">
            @if(auth()->check() && auth()->user()->photo_profile)
                <img src="{{ asset(auth()->user()->photo_profile) }}" alt="Profile" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
            @else
                {{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'U', 0, 1)) }}
            @endif
        </a>
    </div>
</header>

<aside class="sidebar">
    <div class="sidebar-content" style="display:flex;flex-direction:column;height:calc(100vh - var(--topbar-height));">
        <ul class="sidebar-menu" style="flex-grow:1;padding-top:20px;">
            <li><a href="{{ url('/organizerdashboard') }}" class="sidebar-item"><i class="ph ph-house sidebar-icon"></i><span class="sidebar-text">Home</span></a></li>
            <li><a href="{{ route('organizer.statistik') }}" class="sidebar-item"><i class="ph ph-chart-bar sidebar-icon"></i><span class="sidebar-text">Analitik Penjualan</span></a></li>
            <li><a href="{{ route('organizer.revenue') }}" class="sidebar-item"><i class="ph ph-currency-dollar sidebar-icon"></i><span class="sidebar-text">Revenue</span></a></li>
            <li><a href="{{ route('organizer.checkin') }}" class="sidebar-item active"><i class="ph ph-qr-code sidebar-icon"></i><span class="sidebar-text">Check In</span></a></li>
            <li><a href="{{ route('organizer.notifications') }}" class="sidebar-item"><i class="ph ph-bell sidebar-icon"></i><span class="sidebar-text">Notifications</span></a></li>
        </ul>
        <div style="padding:10px 0;">
            <form action="{{ route('logout') }}" method="POST" style="margin:0;width:100%;">
                @csrf
                <button type="submit" class="sidebar-item" style="background:transparent;border:none;color:var(--queen-pink);width:100%;text-align:left;padding:15px 22px;cursor:pointer;">
                    <i class="ph ph-sign-out sidebar-icon"></i><span class="sidebar-text">Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<main class="main-wrapper">

    <div class="section-header" style="justify-content: space-between; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <i class="ph ph-scan"></i> Check In Tiket
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <select id="event-selector" style="background: rgba(58,52,91,0.8); border: 1px solid rgba(243,200,221,0.3); color: #fff; padding: 10px 15px; border-radius: 10px; font-size: 0.95rem; outline: none; cursor: pointer;">
                @foreach($events as $ev)
                    <option value="{{ $ev->id_event }}" style="background: var(--jacarta); color: #fff;">{{ $ev->nama_event }} - {{ \Carbon\Carbon::parse($ev->tanggal_pelaksanaan)->format('d M Y') }}</option>
                @endforeach
                @if($events->isEmpty())
                    <option value="">Belum ada event</option>
                @endif
            </select>
            <a href="{{ route('organizer.checkin.report') }}" style="background: var(--middle-purple); color: var(--jacarta); text-decoration: none; padding: 10px 18px; border-radius: 10px; font-size: 0.95rem; font-weight: 600; display: flex; align-items: center; gap: 6px; transition: 0.3s; box-shadow: 0 4px 10px rgba(209,131,169,0.3);">
                <i class="ph ph-chart-line-up"></i> Check-in Report
            </a>
        </div>
    </div>

    <div class="checkin-container">

        <div class="method-panel">
            <h4>Metode Check-In</h4>
            <div class="method-btn active" id="btn-qr" onclick="switchMethod('qr')">
                <i class="ph ph-qr-code"></i>
                <span>Scan QR / Barcode</span>
            </div>
            <div class="method-btn" id="btn-face" onclick="switchMethod('face')">
                <i class="ph ph-scan-smiley"></i>
                <span>Face Sync</span>
            </div>
        </div>

        <div class="scanner-panel">

            <div id="qr-panel">
                <p class="panel-label">Scan QR Code / Barcode Tiket</p>

                <div class="qr-cam-wrap" id="qr-cam-box">
                    <video id="qr-video" autoplay playsinline muted></video>
                    <div class="qr-scanline"></div>
                    <div class="qr-corner-tr"></div>
                    <div class="qr-corner-bl"></div>
                    <div id="qr-status-overlay">
                        <i id="qr-status-icon" class="ph ph-check-circle" style="color:var(--green)"></i>
                    </div>
                </div>

                <div class="cam-info-bar">
                    <div id="cam-status-dot"></div>
                    <span id="cam-status-text">Memulai kamera...</span>
                </div>

                <div class="or-divider"><span>atau masukkan kode secara manual</span></div>

                <div class="qr-input-wrap">
                    <input type="text" id="qr-input" placeholder="Ketik / paste kode tiket di sini..." autocomplete="off">
                    <button class="btn-scan" onclick="searchByCode()">
                        <i class="ph ph-magnifying-glass"></i> Cari
                    </button>
                </div>

                <div id="ticket-result">
                    <div class="ticket-card">
                        <div class="ticket-poster" id="ticket-poster"></div>
                        <div class="ticket-info">
                            <div class="event-name" id="tc-event"></div>
                            <div class="info-row"><i class="ph ph-user"></i><span id="tc-buyer"></span></div>
                            <div class="info-row"><i class="ph ph-ticket"></i><span id="tc-jenis"></span>&nbsp;·&nbsp;<span id="tc-qty"></span> tiket</div>
                            <div class="info-row"><i class="ph ph-calendar"></i><span id="tc-tanggal"></span></div>
                            <div class="info-row"><i class="ph ph-map-pin"></i><span id="tc-lokasi"></span></div>
                            <div class="info-row"><i class="ph ph-hash"></i>Kode: <span id="tc-kode" style="font-family:monospace;letter-spacing:1px;"></span></div>
                            <div id="tc-badge"></div>
                            <button class="btn-confirm" id="btn-confirm" onclick="doConfirm()">
                                <i class="ph ph-check-circle"></i> Konfirmasi Check-In
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="face-panel">
                <p class="panel-label">Verifikasi Wajah (Face Sync)</p>

                <div id="face-loading">
                    <div class="spinner"></div>
                </div>

                <div id="face-cam-wrapper">
                    <div class="face-cam-wrap">
                        <video id="face-video" autoplay playsinline muted></video>
                        <div class="face-scanline"></div>
                    </div>
                    <div class="cam-actions">
                        <button class="btn-capture" id="btn-capture" onclick="captureAndSync()">
                            <i class="ph ph-camera"></i> Pindai Wajah
                        </button>
                    </div>
                </div>

                <canvas id="face-canvas"></canvas>

                <div id="face-result">
                    <div class="face-match-card">
                        <div class="face-photos-row">
                            <div>
                                <div class="face-photo-wrap" id="face-now-wrap">
                                    <img id="face-now-img" src="" alt="Sekarang">
                                </div>
                                <p style="font-size:0.75rem;opacity:0.5;margin-top:6px;text-align:center;">Foto Wajah</p>
                            </div>
                            <div>
                                <i class="ph ph-arrows-left-right face-center-icon"></i>
                            </div>
                            <div>
                                <div class="face-photo-wrap" id="face-db-wrap">
                                    <img id="face-db-img" src="" alt="Data Tiket">
                                    <i class="ph ph-user" id="face-db-icon" style="display:none;"></i>
                                </div>
                                <p style="font-size:0.75rem;opacity:0.5;margin-top:6px;text-align:center;">Data Tiket</p>
                            </div>
                        </div>
                        <div class="face-match-title">
                            <i class="ph ph-check-circle"></i> Wajah Cocok!
                        </div>
                        <div class="face-match-name" id="face-name"></div>
                        <div class="face-match-sub" id="face-event"></div>
                        <div class="face-match-sub" id="face-ticket"></div>
                        <div class="face-match-sub" id="face-kode" style="font-family:monospace;letter-spacing:1px;font-size:0.82rem;margin-top:4px;"></div>
                        <button class="btn-reset" onclick="resetFace()">
                            <i class="ph ph-arrow-counter-clockwise"></i> Kembali ke halaman scan
                        </button>
                    </div>
                </div>

                <p class="scan-hint" id="face-hint">Posisikan wajah penonton di tengah lingkaran kamera, lalu klik <strong>Pindai Wajah</strong>. Sistem akan mencocokkan dengan wajah yang didaftarkan saat pembelian tiket.</p>
            </div>

        </div>
    </div>
</main>

<script>
const CSRF           = document.querySelector('meta[name="csrf-token"]').content;
const URL_SCAN_QR    = "{{ route('checkin.scan-qr') }}";
const URL_CONFIRM    = "{{ route('checkin.confirm') }}";
const URL_FACE_SYNC  = "{{ route('checkin.sync-face') }}";

function switchMethod(m) {
    const qrEl   = document.getElementById('qr-panel');
    const faceEl = document.getElementById('face-panel');
    const bq     = document.getElementById('btn-qr');
    const bf     = document.getElementById('btn-face');

    if (m === 'qr') {
        qrEl.style.display   = 'flex';
        faceEl.style.display = 'none';
        bq.classList.add('active');
        bf.classList.remove('active');
        stopFaceCam();
        startQrCamera();
    } else {
        qrEl.style.display   = 'none';
        faceEl.style.display = 'flex';
        bq.classList.remove('active');
        bf.classList.add('active');
        stopQrCamera();
        startFacePanel();
    }
}

let qrStream        = null;
let qrAnimFrame     = null;
let qrLocked        = false;
let currentDetailId = null;

async function startQrCamera() {
    const statusDot  = document.getElementById('cam-status-dot');
    const statusText = document.getElementById('cam-status-text');
    statusText.textContent = 'Memulai kamera...';

    try {
        qrStream = await navigator.mediaDevices.getUserMedia({ video:{ facingMode:'environment' }, audio:false });
        const video = document.getElementById('qr-video');
        video.srcObject = qrStream;
        video.onloadedmetadata = () => {
            video.play();
            statusDot.classList.add('active');
            statusText.textContent = 'Kamera aktif — arahkan ke QR/barcode tiket';
            requestAnimationFrame(tickQr);
        };
    } catch(err) {
        statusText.textContent = 'Kamera tidak tersedia — gunakan input manual';
        console.warn('QR camera:', err);
    }
}

function stopQrCamera() {
    if (qrStream) { qrStream.getTracks().forEach(t => t.stop()); qrStream = null; }
    cancelAnimationFrame(qrAnimFrame);
}

function tickQr() {
    if (qrLocked) { qrAnimFrame = requestAnimationFrame(tickQr); return; }

    const video  = document.getElementById('qr-video');
    if (!video.videoWidth) { qrAnimFrame = requestAnimationFrame(tickQr); return; }

    const canvas = document.createElement('canvas');
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const code      = jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: 'dontInvert' });

    if (code && code.data) {
        qrLocked = true;
        document.getElementById('qr-input').value = code.data;
        doScanSearch(code.data);
    }

    qrAnimFrame = requestAnimationFrame(tickQr);
}

document.getElementById('qr-input').addEventListener('keydown', e => { if(e.key==='Enter') searchByCode(); });

function searchByCode() {
    const v = document.getElementById('qr-input').value.trim();
    if (!v) {
        Swal.fire({ icon:'warning', title:'Kosong', text:'Masukkan kode QR terlebih dahulu.', background:'#3A345B', color:'#F3C8DD', confirmButtonColor:'#D183A9' });
        return;
    }
    doScanSearch(v);
}

async function doScanSearch(kode) {
    showQrOverlay(null);
    Swal.fire({ title:'Mencari tiket...', allowOutsideClick:false, background:'#3A345B', color:'#F3C8DD', didOpen:()=>Swal.showLoading() });

    try {
        const eventId = document.getElementById('event-selector') ? document.getElementById('event-selector').value : '';
        const res  = await fetch(URL_SCAN_QR, {
            method:'POST',
            headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' },
            body: JSON.stringify({ kode_qr: kode, id_event: eventId })
        });
        const data = await res.json();
        Swal.close();

        if (data.status === 'not_found') {
            showQrOverlay('error');
            setTimeout(() => { hideQrOverlay(); qrLocked = false; }, 2500);
            Swal.fire({
                icon:'error', title:'QR Tidak Ditemukan',
                text:'Kode QR tidak valid atau tiket tidak ditemukan.',
                background:'#3A345B', color:'#F3C8DD', confirmButtonColor:'#D183A9'
            }).then(() => { hideTicketResult(); document.getElementById('qr-input').value=''; qrLocked=false; });
            return;
        }

        if (data.status === 'unauthorized') {
            showQrOverlay('error');
            setTimeout(() => { hideQrOverlay(); qrLocked = false; }, 2500);
            Swal.fire({
                icon:'warning', title:'Anda Bukan Bagian Dari Event Ini',
                html:`<p style="color:#F3C8DD;">Anda bukan organizer dari event <strong>"${data.event_name || ''}"</strong>.</p><p style="color:#F3C8DD;opacity:0.7;margin-top:8px;">Anda hanya dapat melakukan check-in untuk tiket event yang Anda kelola.</p>`,
                background:'#3A345B', color:'#F3C8DD', confirmButtonColor:'#D183A9'
            }).then(() => { hideTicketResult(); document.getElementById('qr-input').value=''; qrLocked=false; });
            return;
        }

        if (data.status === 'found') {
            showQrOverlay('success');
            setTimeout(() => { hideQrOverlay(); }, 1500);
            renderTicketResult(data.ticket);
            setTimeout(() => { qrLocked = false; }, 5000);
        }
    } catch(err) {
        Swal.close();
        Swal.fire({ icon:'error', title:'Gagal', text:'Terjadi kesalahan jaringan.', background:'#3A345B', color:'#F3C8DD', confirmButtonColor:'#D183A9' });
        qrLocked = false;
    }
}

function showQrOverlay(type) {
    const wrap  = document.getElementById('qr-status-overlay');
    const icon  = document.getElementById('qr-status-icon');
    wrap.classList.add('show');
    if (type === 'success') { icon.className = 'ph ph-check-circle'; icon.style.color = 'var(--green)'; }
    else if (type === 'error') { icon.className = 'ph ph-x-circle'; icon.style.color = '#ef5350'; }
    else { icon.className = 'ph ph-circle-notch ph-spin'; icon.style.color = 'var(--queen-pink)'; }
}
function hideQrOverlay() {
    document.getElementById('qr-status-overlay').classList.remove('show');
}

function renderTicketResult(ticket) {
    currentDetailId = ticket.id_detail;

    const poster = document.getElementById('ticket-poster');
    poster.innerHTML = ticket.poster
        ? `<img src="${ticket.poster}" alt="poster">`
        : `<i class="ph ph-image"></i>`;

    document.getElementById('tc-event').textContent   = ticket.nama_event;
    document.getElementById('tc-buyer').textContent   = ticket.nama_penonton;
    document.getElementById('tc-jenis').textContent   = ticket.jenis_tiket;
    document.getElementById('tc-qty').textContent     = ticket.jumlah_beli;
    document.getElementById('tc-lokasi').textContent  = ticket.lokasi_event;
    document.getElementById('tc-kode').textContent    = ticket.kode_qr;

    const tgl = ticket.tanggal_pelaksanaan
        ? new Date(ticket.tanggal_pelaksanaan).toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'})
        : '-';
    document.getElementById('tc-tanggal').textContent = tgl;

    const badge   = document.getElementById('tc-badge');
    const confirm = document.getElementById('btn-confirm');
    if (ticket.already_checked_in) {
        badge.innerHTML = `<span class="ticket-badge ticket-badge-used"><i class="ph ph-warning"></i> Sudah Check-In</span>`;
        confirm.disabled = true;
        confirm.innerHTML = '<i class="ph ph-check"></i> Sudah digunakan';
    } else {
        badge.innerHTML = `<span class="ticket-badge"><i class="ph ph-check"></i> Tiket Valid</span>`;
        confirm.disabled = false;
        confirm.innerHTML = '<i class="ph ph-check-circle"></i> Konfirmasi Check-In';
    }

    document.getElementById('ticket-result').style.display = 'block';
    document.getElementById('ticket-result').scrollIntoView({ behavior:'smooth', block:'nearest' });
}

function hideTicketResult() {
    document.getElementById('ticket-result').style.display = 'none';
    currentDetailId = null;
}

async function doConfirm() {
    if (!currentDetailId) return;
    const btn = document.getElementById('btn-confirm');
    btn.disabled = true;

    try {
        const res  = await fetch(URL_CONFIRM, {
            method:'POST',
            headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' },
            body: JSON.stringify({ id_detail: currentDetailId })
        });
        const data = await res.json();

        if (data.status === 'success') {
            Swal.fire({
                icon:'success', title:'Check-In Berhasil!',
                text:'Penonton berhasil masuk. Selamat menikmati acara!',
                background:'#3A345B', color:'#F3C8DD', confirmButtonColor:'#84d8a5',
                timer:3000
            }).then(() => { hideTicketResult(); document.getElementById('qr-input').value=''; qrLocked=false; });
        } else if (data.status === 'already') {
            Swal.fire({ icon:'warning', title:'Sudah Check-In', text:data.message, background:'#3A345B', color:'#F3C8DD', confirmButtonColor:'#D183A9' });
            btn.disabled = false;
        } else {
            Swal.fire({ icon:'error', title:'Gagal', text:data.message, background:'#3A345B', color:'#F3C8DD', confirmButtonColor:'#D183A9' });
            btn.disabled = false;
        }
    } catch {
        Swal.fire({ icon:'error', title:'Error', text:'Gagal terhubung ke server.', background:'#3A345B', color:'#F3C8DD', confirmButtonColor:'#D183A9' });
        btn.disabled = false;
    }
}

let faceStream      = null;
let faceModelsOk    = false;
let capturedDataUrl = null;

async function startFacePanel() {
    resetFace();

    if (!faceModelsOk) {
        document.getElementById('face-loading').style.display     = 'flex';
        document.getElementById('face-cam-wrapper').style.display = 'none';
        try {
            const MODEL = 'https://vladmandic.github.io/face-api/model';
            await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL);
            await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL);
            faceModelsOk = true;
        } catch(e) { console.error('Model load error:', e); }
        document.getElementById('face-loading').style.display     = 'none';
        document.getElementById('face-cam-wrapper').style.display = 'flex';
    }

    try {
        faceStream = await navigator.mediaDevices.getUserMedia({ video:{ facingMode:'user' }, audio:false });
        document.getElementById('face-video').srcObject = faceStream;
    } catch(err) {
        Swal.fire({ icon:'warning', title:'Kamera Tidak Tersedia', text:'Gunakan tombol upload foto sebagai alternatif.', background:'#3A345B', color:'#F3C8DD', confirmButtonColor:'#D183A9' });
    }
}

function stopFaceCam() {
    if (faceStream) { faceStream.getTracks().forEach(t=>t.stop()); faceStream=null; }
}

async function captureAndSync() {
    const video  = document.getElementById('face-video');
    if (!video.srcObject) { Swal.fire({ icon:'warning', title:'Kamera tidak aktif', text:'Kamera belum tersedia. Gunakan upload foto.', background:'#3A345B', color:'#F3C8DD', confirmButtonColor:'#D183A9' }); return; }
    const canvas = document.getElementById('face-canvas');
    const ctx    = canvas.getContext('2d');
    canvas.width  = video.videoWidth  || 640;
    canvas.height = video.videoHeight || 480;
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    capturedDataUrl = canvas.toDataURL('image/png');

    const img = new Image();
    img.src   = capturedDataUrl;
    img.onload = () => detectAndSync(img);
}

function handleUpload(e) {
    const file = e.target.files[0]; if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
        capturedDataUrl = ev.target.result;
        const img = new Image();
        img.src = capturedDataUrl;
        img.onload = () => detectAndSync(img);
    };
    reader.readAsDataURL(file);
    e.target.value = '';
}

async function detectAndSync(imgEl) {
    Swal.fire({ title:'Menganalisis Wajah...', allowOutsideClick:false, background:'#3A345B', color:'#F3C8DD', didOpen:()=>Swal.showLoading() });

    await new Promise(r => setTimeout(r, 80));

    let descriptor;
    try {
        const det = await faceapi
            .detectSingleFace(imgEl, new faceapi.TinyFaceDetectorOptions())
            .withFaceLandmarks()
            .withFaceDescriptor();

        if (!det) {
            Swal.fire({ icon:'error', title:'Wajah Tidak Terdeteksi', text:'Pastikan wajah terlihat jelas dan pencahayaan cukup.', background:'#3A345B', color:'#F3C8DD', confirmButtonColor:'#D183A9' });
            return;
        }
        descriptor = Array.from(det.descriptor);
    } catch(e) {
        Swal.fire({ icon:'error', title:'Error Analisis', text:'Gagal menganalisis wajah.', background:'#3A345B', color:'#F3C8DD', confirmButtonColor:'#D183A9' });
        return;
    }

    try {
        const eventId = document.getElementById('event-selector') ? document.getElementById('event-selector').value : '';
        const res  = await fetch(URL_FACE_SYNC, {
            method:'POST',
            headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' },
            body: JSON.stringify({ descriptor: JSON.stringify(descriptor), id_event: eventId })
        });
        const data = await res.json();
        Swal.close();

        if (data.status === 'match') {
            showFaceMatch(data);
        } else {
            Swal.fire({
                icon:'error', title:'Wajah Tidak Ditemukan',
                html:`<p style="color:#F3C8DD;opacity:0.85;">Wajah tidak cocok dengan data manapun.<br>Pastikan penonton telah mendaftarkan wajah saat membeli tiket.</p>`,
                background:'#3A345B', color:'#F3C8DD', confirmButtonColor:'#D183A9'
            });
        }
    } catch(err) {
        Swal.close();
        Swal.fire({ icon:'error', title:'Gagal', text:'Kesalahan jaringan saat face sync.', background:'#3A345B', color:'#F3C8DD', confirmButtonColor:'#D183A9' });
    }
}

function showFaceMatch(data) {
    document.getElementById('face-now-img').src = capturedDataUrl;

    if (data.face_url) {
        document.getElementById('face-db-img').src           = data.face_url;
        document.getElementById('face-db-img').style.display = 'block';
        document.getElementById('face-db-icon').style.display= 'none';
    } else {
        document.getElementById('face-db-img').style.display = 'none';
        document.getElementById('face-db-icon').style.display= 'block';
    }

    document.getElementById('face-name').textContent   = '👤 ' + (data.ticket?.nama_penonton || '-');
    document.getElementById('face-event').textContent  = '🎫 ' + (data.ticket?.nama_event   || '-');
    document.getElementById('face-ticket').textContent = '🎟  ' + (data.ticket?.jenis_tiket  || '') + ' · ' + (data.ticket?.jumlah_beli || 1) + ' tiket';
    document.getElementById('face-kode').textContent   = data.ticket?.kode_qr ? ('Kode: ' + data.ticket.kode_qr) : '';

    document.getElementById('face-cam-wrapper').style.display = 'none';
    document.getElementById('face-hint').style.display        = 'none';
    document.getElementById('face-result').style.display      = 'block';
}

function resetFace() {
    document.getElementById('face-result').style.display      = 'none';
    document.getElementById('face-cam-wrapper').style.display = 'flex';
    document.getElementById('face-hint').style.display        = 'block';
    capturedDataUrl = null;
}

startQrCamera();
document.getElementById('qr-input').focus();
</script>
</body>
</html>
