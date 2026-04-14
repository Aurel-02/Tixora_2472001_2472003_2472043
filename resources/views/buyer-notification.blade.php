<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tixora - Notifications</title>
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
            min-height: calc(100vh - var(--topbar-height));
            display: flex;
            flex-direction: column;
            padding-bottom: 50px;
        }

        .section-header {
            margin: calc(var(--topbar-height) - 10px) auto 0;
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
            background: rgba(243, 200, 221, 0.15); 
            border: 2px dashed rgba(243, 200, 221, 0.5); 
            border-radius: 20px; 
            padding: 20px 30px;
            backdrop-filter: blur(8px);
            width: 90%;
            max-width: 1000px;
            font-size: 2.2rem;
            color: #ffffff;
            text-shadow: 0 0 10px rgba(243, 200, 221, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .notifications-container {
            width: 90%;
            max-width: 1000px;
            margin: 30px auto;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .empty-state {
            text-align: center;
            padding: 70px 20px;
            background: rgba(243, 200, 221, 0.08); 
            border-radius: 30px; 
            border: 2px dashed rgba(243, 200, 221, 0.4);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1), inset 0 0 20px rgba(243, 200, 221, 0.1);
            position: relative;
            overflow: hidden;
        }

        .empty-icon {
            font-size: 5rem;
            color: rgba(243, 200, 221, 0.8);
            margin-bottom: 15px;
            display: inline-block;
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
        }
    </style>
</head>
<body>




    <x-buyer-topbar />

    <x-buyer-sidebar />

    <main class="main-wrapper">
        <div class="section-header">
            <span class="section-title"><i class="ph ph-envelope-open" style="color: var(--queen-pink);"></i> Notifications</span>
        </div>

        <div class="notifications-container">
            @if(isset($notifications) && $notifications->count() > 0)
                @foreach($notifications as $notif)
                    @php
                        $isCanceled = strpos(strtolower($notif->pesan), 'batal') !== false;
                        $isFaceScan = !empty($notif->link_url);
                        $borderColor = $isCanceled ? '#E74C3C' : ($isFaceScan ? '#84d8a5' : 'var(--middle-purple)');
                        $iconClass = $isCanceled ? 'ph-x-circle' : ($isFaceScan ? 'ph-scan' : 'ph-check-circle');
                        $iconColor = $isCanceled ? '#E74C3C' : ($isFaceScan ? '#84d8a5' : '#84d8a5');
                    @endphp

                    @if($isFaceScan)
                        <a href="#" data-face-scan-url="{{ $notif->link_url }}" class="face-scan-notif" style="text-decoration: none; display: block;">
                    @endif
                    <div style="background: {{ $isFaceScan ? 'rgba(132, 216, 165, 0.08)' : 'rgba(243, 200, 221, 0.08)' }}; border-radius: 15px; padding: 20px; border-left: 4px solid {{ $borderColor }}; display: flex; align-items: flex-start; gap: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); position: relative; {{ $isFaceScan ? 'cursor: pointer; transition: background 0.2s, transform 0.2s;' : '' }}"
                        @if($isFaceScan) onmouseover="this.style.background='rgba(132,216,165,0.18)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='rgba(132,216,165,0.08)'; this.style.transform='translateY(0)';" @endif>

                        @if(!$notif->is_read)
                            <div style="position: absolute; top: 15px; right: 20px; width: 10px; height: 10px; background: #E74C3C; border-radius: 50%; box-shadow: 0 0 8px rgba(231, 76, 60, 0.8);"></div>
                        @endif

                        <i class="ph {{ $iconClass }}" style="font-size: 2rem; color: {{ $iconColor }}; flex-shrink: 0;"></i>
                        <div style="flex: 1;">
                            <div style="font-size: 1.1rem; color: #fff; font-weight: 500; margin-bottom: 5px;">{{ $notif->pesan }}</div>
                            <div style="font-size: 0.85rem; color: var(--queen-pink); opacity: 0.7;">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</div>
                            @if($isFaceScan)
                                <div style="margin-top: 10px;">
                                    <span style="display: inline-flex; align-items: center; gap: 6px; background: rgba(132,216,165,0.2); color: #84d8a5; border: 1px solid rgba(132,216,165,0.5); border-radius: 50px; padding: 5px 14px; font-size: 0.85rem; font-weight: 600;">
                                        <i class="ph ph-camera"></i> Scan Wajah Sekarang
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if($isFaceScan)
                        </a>
                    @endif
                @endforeach
            @else
                <div class="empty-state">
                    <i class="ph ph-mailbox empty-icon"></i>
                    <div style="font-size: 1.5rem; color: #fff; font-weight: 700; letter-spacing: 1px;">Oops! Your inbox is empty</div>
                    <div style="color: var(--queen-pink); opacity: 0.9; margin-top: 8px; font-weight: 300;">All updates about your upcoming events and tickets will appear right here.</div>
                </div>
            @endif
        </div>

    </main>

    <script>
        document.getElementById('globalSearch')?.addEventListener('keyup', function(e) {
            if(e.key === 'Enter') {
                window.location.href = '/dashboard';
            }
        });

        const statusCheckUrl = "{{ route('face-scan.status') }}";
        const faceScanUrl = "{{ route('face-scan.index', ['total' => 1]) }}";
        const dashboardUrl = "{{ url('/dashboard') }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

        document.querySelectorAll('.face-scan-notif').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                fetch(statusCheckUrl, {
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.done) {
                        // Wajah sudah diverifikasi — tampilkan popup
                        Swal.fire({
                            icon: 'success',
                            title: 'Verifikasi Wajah Telah Dilakukan',
                            text: 'Kamu sudah melakukan scan wajah untuk tiket ini.',
                            confirmButtonText: 'OK',
                            background: '#3A345B',
                            color: '#F3C8DD',
                            confirmButtonColor: '#D183A9',
                            iconColor: '#84d8a5'
                        }).then(() => {
                            window.location.href = dashboardUrl;
                        });
                    } else {
                        // Belum scan, arahkan ke face scan
                        window.location.href = faceScanUrl;
                    }
                })
                .catch(() => {
                    window.location.href = faceScanUrl;
                });
            });
        });
    </script>
</body>
</html>
