<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tixora - Edit Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />
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

        .sidebar-item:hover {
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
            padding-top: calc(var(--topbar-height) + 40px);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-bottom: 40px;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 30px;
            width: 100%;
            max-width: 900px;
            padding: 0 20px;
        }

        .form-container {
            width: 100%;
            background: rgba(113, 85, 122, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(243, 200, 221, 0.15);
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .photo-container {
            width: 100%;
            background: rgba(113, 85, 122, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(243, 200, 221, 0.15);
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        .form-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 30px;
            text-align: center;
        }

        .photo-preview {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: var(--middle-purple);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5rem;
            color: var(--jacarta);
            font-weight: 700;
            box-shadow: 0 0 15px rgba(209, 131, 169, 0.4);
            overflow: hidden;
        }

        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .file-upload-btn {
            display: inline-block;
            padding: 10px 20px;
            background: transparent;
            color: var(--queen-pink);
            border: 1px solid var(--middle-purple);
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .file-upload-btn:hover {
            background: var(--middle-purple);
            color: var(--jacarta);
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-label {
            display: block;
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--queen-pink);
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid rgba(243, 200, 221, 0.2);
            background: rgba(58, 52, 91, 0.4);
            color: #fff;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--middle-purple);
        }

        .btn-submit {
            display: block;
            width: 100%;
            padding: 12px;
            background: var(--middle-purple);
            color: var(--jacarta);
            border: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            margin-top: 20px;
        }

        .btn-submit:hover {
            background: var(--queen-pink);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(243, 200, 221, 0.4);
        }

        .btn-logout {
            display: block;
            width: 100%;
            padding: 12px;
            background: transparent;
            color: #ff6b6b;
            border: 1px solid #ff6b6b;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            margin-top: 15px;
            text-align: center;
            text-decoration: none;
        }

        .btn-logout:hover {
            background: #ff6b6b;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
        }

        .alert-success {
            background: rgba(132, 216, 165, 0.2);
            border: 1px solid #84d8a5;
            color: #84d8a5;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .alert-error {
            background: rgba(255, 107, 107, 0.2);
            border: 1px solid #ff6b6b;
            color: #ff6b6b;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
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
                padding: 100px 20px 40px 20px;
            }

            .profile-grid {
                grid-template-columns: 1fr;
            }

            .form-container,
            .photo-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>

@php
    $userRole = null;
    $userName = 'User';
    $userPhoto = null;
    $unreadNotifCount = 0;

    if(auth()->check()) {
        $userRole = auth()->user()->role;
        $userName = auth()->user()->nama_lengkap;
        $userPhoto = auth()->user()->photo_profile;
        $unreadNotifCount = \Illuminate\Support\Facades\DB::table('notifikasi')
            ->where('id_user', auth()->id())
            ->where('is_read', 0)
            ->count();
    } elseif(session()->has('login_admin')) {
        $userRole = session('login_admin.role');
        $userName = session('login_admin.name');
    }
@endphp


    <header class="topbar">
        <a href="{{ $userRole == '2' || $userRole == 'Organizer' ? route('organizerdashboard') : ($userRole == '1' || $userRole == 'Admin' ? '/admin/dashboard' : '/dashboard') }}" class="logo">TIXORA</a>
        <div style="display: flex; align-items: center; gap: 15px;">
            <a href="{{ route('profile.edit') }}" class="profile" title="My Profile" style="text-decoration:none;">
                @if($userPhoto)
                    <img src="{{ asset($userPhoto) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                @else
                    {{ strtoupper(substr($userName ?? 'U', 0, 1)) }}
                @endif
            </a>
        </div>
    </header>

    <aside class="sidebar">
        <div class="sidebar-content" style="display: flex; flex-direction: column; height: calc(100vh - var(--topbar-height));">
            <ul class="sidebar-menu" style="flex-grow: 1; padding-top: 20px;">
                @if($userRole == '2' || $userRole == 'Organizer')
                    <li>
                        <a href="{{ route('organizerdashboard') }}" class="sidebar-item">
                            <i class="ph ph-house sidebar-icon"></i>
                            <span class="sidebar-text">Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('organizer.statistik') }}" class="sidebar-item">
                            <i class="ph ph-chart-bar sidebar-icon"></i>
                            <span class="sidebar-text">Analitik Penjualan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('organizer.revenue') }}" class="sidebar-item">
                            <i class="ph ph-currency-dollar sidebar-icon"></i>
                            <span class="sidebar-text">Revenue</span>
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
                @elseif($userRole == '1' || $userRole == 'Admin')
                    <li>
                        <a href="/admin/dashboard" class="sidebar-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                            <i class="ph ph-house sidebar-icon"></i>
                            <span class="sidebar-text">Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.revenue') }}" class="sidebar-item {{ Request::is('admin/revenue') ? 'active' : '' }}">
                            <i class="ph ph-currency-dollar sidebar-icon"></i>
                            <span class="sidebar-text">Revenue</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.events.create') }}" class="sidebar-item {{ Request::is('admin/events/create') ? 'active' : '' }}">
                            <i class="ph ph-plus-circle sidebar-icon"></i>
                            <span class="sidebar-text">Tambah Event</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.statistik') }}" class="sidebar-item {{ Request::is('admin/statistik') ? 'active' : '' }}">
                            <i class="ph ph-chart-bar sidebar-icon"></i>
                            <span class="sidebar-text">Analitik Penjualan</span>
                        </a>
                    </li>
                @else
                    <li>
                        <a href="/dashboard" class="sidebar-item">
                            <i class="ph ph-house sidebar-icon"></i>
                            <span class="sidebar-text">Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('my-tickets') }}" class="sidebar-item">
                            <i class="ph ph-ticket sidebar-icon"></i>
                            <span class="sidebar-text">My Tickets</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('buyer.notification') }}" class="sidebar-item" style="position: relative;">
                            <i class="ph ph-bell sidebar-icon"></i>
                            <span class="sidebar-text">Notifications</span>
                            @if(isset($unreadNotifCount) && $unreadNotifCount > 0)
                                <span style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); background: #E74C3C; color: white; border-radius: 50%; width: 22px; height: 22px; display:flex; align-items:center; justify-content:center; font-size: 0.75rem; font-weight: bold; box-shadow: 0 0 5px rgba(231, 76, 60, 0.5);">{{ $unreadNotifCount }}</span>
                            @endif
                        </a>
                    </li>
                @endif
            </ul>

            <div style="padding: 10px 0;">
                <form action="{{ route('logout') }}" method="POST" style="margin: 0; width: 100%;">
                    @csrf
                    <button type="submit" class="sidebar-item" style="background: var(--old-lavender) ; border: none; width: 100%; text-align: left; padding: 15px 22px; cursor: pointer;">
                        <i class="ph ph-sign-out sidebar-icon"></i>
                        <span class="sidebar-text">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <main class="main-wrapper">
        <div class="profile-grid">
            <div class="form-container">
                <h1 class="form-title">Edit Profile</h1>

                @if($errors->any())
                    <div class="alert-error">
                        <ul style="list-style-position: inside;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" id="profileForm" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-input"
                            value="{{ old('nama_lengkap', $user->nama_lengkap ?? '') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input"
                            value="{{ old('email', $user->email ?? '') }}" required>
                    </div>

                    @if(!$isAdmin)
                    <div class="form-group">
                        <label class="form-label">No Telp</label>
                        <input type="text" name="no_telp" class="form-input"
                            value="{{ old('no_telp', $user->no_telp ?? '') }}">
                    </div>
                    @endif

                    <div class="form-group" style="display: flex; align-items: flex-end; gap: 15px;">
                        <div style="flex-grow: 1;">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-input" value="{{ $user->password }}" disabled style="background-color: rgba(58, 52, 91, 0.2); cursor: not-allowed;">
                        </div>
                        <a href="{{ route('profile.password.edit') }}" style="display: inline-block; padding: 12px 20px; background: var(--middle-purple); color: var(--jacarta); border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 0.95rem; white-space: nowrap; height: 45px; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">Change Password</a>
                    </div>

                    <button type="submit" class="btn-submit">Save Changes</button>
                </form>
            </div>

            @if(!$isAdmin)
            <div class="photo-container">
                <h1 class="form-title" style="margin-bottom: 20px;">Profile Photo</h1>

                <div class="photo-preview">
                    @if($user->photo_profile)
                        <span id="photoInitial" style="display: none;">{{ substr($user->nama_lengkap ?? 'U', 0, 1) }}</span>
                        <img id="photoImg" src="{{ asset($user->photo_profile) }}" alt="Profile">
                    @else
                        <span id="photoInitial">{{ substr($user->nama_lengkap ?? 'U', 0, 1) }}</span>
                        <img id="photoImg" src="" alt="Profile" style="display: none;">
                    @endif
                </div>

                <p style="font-size: 0.85rem; color: var(--queen-pink); opacity: 0.8; text-align: center;">Upload a new
                    avatar. Larger images will be resized automatically.</p>
                <p style="font-size: 0.8rem; color: var(--queen-pink); opacity: 0.6; text-align: center;">Maximum upload
                    size is 2 MB.</p>

                <label for="photoUpload" class="file-upload-btn">
                    Select New Photo
                    <input type="file" id="photoUpload" name="photo_profile" form="profileForm" accept="image/*" style="display: none;"
                        onchange="previewPhoto(event)">
                </label>
            </div>
            @endif
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if(session('success'))
                Swal.fire({
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    background: 'var(--jacarta)',
                    color: 'var(--queen-pink)',
                    confirmButtonColor: 'var(--middle-purple)'
                });
            @endif
        });

        function previewPhoto(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('photoImg').src = e.target.result;
                    document.getElementById('photoImg').style.display = 'block';
                    document.getElementById('photoInitial').style.display = 'none';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>