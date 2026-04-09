<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tixora - Dashboard</title>
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
            min-height: calc(100vh - var(--topbar-height));
            display: flex;
            flex-direction: column;
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
            font-size: 2.5rem;
            color: #ffffff;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.4);
        }

    </style>
</head>
<body>

    <header class="topbar">
        <div class="logo">TIXORA</div>
        <div style="display: flex; align-items: center; gap: 12px;">
            <a href="{{ route('profile.edit') }}" class="profile-link" style="text-decoration: none;">
                @php
                    $displayName = session('login_admin.name') ?? (auth()->check() ? auth()->user()->nama_lengkap : 'Admin');
                @endphp
                <div class="profile" title="My Profile">{{ strtoupper(substr($displayName, 0, 1)) }}</div>
            </a>
        </div>
    </header>

    <aside class="sidebar">
        <div class="sidebar-content" style="display: flex; flex-direction: column; height: calc(100vh - var(--topbar-height));">
            <ul class="sidebar-menu" style="flex-grow: 1; padding-top: 20px;">
                <li>
                    <a href="{{ url('/admin/dashboard') }}" class="sidebar-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
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
            <span class="section-title">Daftarkan event kamu disini !</span>
        </div>

        @if(session('success'))
            <div style="margin: 16px auto; width: 95%; max-width: 960px; background: rgba(69, 209, 103, 0.2); color: #e6ffe6; border: 1px solid #5fcc72; border-radius: 8px; padding: 10px; text-align: center;">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div style="margin: 16px auto; width: 95%; max-width: 960px; background: rgba(255, 68, 68, 0.15); color: #fff; border: 1px solid #ff8b8b; border-radius: 8px; padding: 10px;">
                <ul style="margin:0; padding-left: 18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" style="margin: 20px auto; width: 95%; max-width: 95%; background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.25); border-radius: 14px; padding: 28px; font-size: 1.1rem; line-height: 1.4;">
            @csrf


            <div style="display: grid; gap: 20px; grid-template-columns: 2fr 1fr; align-items: start;">
                <div>
                    <div style="display: grid; gap: 14px; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
                        <div>
                            <label for="nama_event" style="font-weight: 700; font-size: 1.1rem;">Nama Event</label>
                            <input id="nama_event" name="nama_event" value="{{ old('nama_event') }}" required type="text" style="width:100%; margin-top:8px; height:44px; background: rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.45); color:#fff; padding:12px; border-radius:10px; font-size:1.05rem;" />
                        </div>

                        <div>
                            <label for="id_kategori" style="font-weight: 700; font-size: 1.1rem;">Kategori</label>
                            <select id="id_kategori" name="id_kategori" required style="width:100%; margin-top:8px; height:44px; background: rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.45); color:#fff; padding:11px; border-radius:10px; font-size:1.05rem;">
                                <option value="" style="color: #333;">Pilih kategori</option>
                                <option value="1" style="color: #333;" {{ old('id_kategori')==1 ? 'selected' : '' }}>Indonesia</option>
                                <option value="2" style="color: #333;" {{ old('id_kategori')==2 ? 'selected' : '' }}>Western</option>
                                <option value="3" style="color: #333;" {{ old('id_kategori')==3 ? 'selected' : '' }}>K-Pop</option>
                            </select>
                        </div>

                        <div>
                            <label for="tanggal_pelaksanaan" style="font-weight: 600;">Tanggal Pelaksanaan</label>
                            <input id="tanggal_pelaksanaan" name="tanggal_pelaksanaan" value="{{ old('tanggal_pelaksanaan') }}" required type="date" style="width:100%; margin-top:6px; height:44px; background: rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.3); color:#fff; padding:8px; border-radius:8px;" />
                        </div>

                        <div>
                            <label for="waktu_pelaksanaan" style="font-weight: 600;">Waktu Pelaksanaan</label>
                            <input id="waktu_pelaksanaan" name="waktu_pelaksanaan" value="{{ old('waktu_pelaksanaan') }}" required type="time" style="width:100%; margin-top:6px; height:44px; background: rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.3); color:#fff; padding:8px; border-radius:8px;" />
                        </div>

                        <div style="grid-column: span 2;">
                            <label for="lokasi_event" style="font-weight: 600;">Lokasi Event</label>
                            <input id="lokasi_event" name="lokasi_event" value="{{ old('lokasi_event') }}" required type="text" style="width:100%; margin-top:6px; height:44px; background: rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.3); color:#fff; padding:8px; border-radius:8px;" />
                        </div>

                    </div>

                    <div style="margin-top: 24px; padding: 16px; background: rgba(0,0,0,0.15); border: 1px solid rgba(255,255,255,0.25); border-radius: 12px;">
                        <h3 style="font-size: 1.05rem; margin-bottom: 10px;">Jenis Tiket (opsional)</h3>
                        <p style="margin-top: -8px; margin-bottom: 12px; color: rgba(255,255,255,0.7);">Isi jika ingin menambahkan tiket (boleh kosong).</p>
                        @php $ticketTypes = ['REGULER', 'VIP', 'VVIP']; @endphp
                        <div style="display: grid; gap: 10px;">
                            @foreach($ticketTypes as $idx => $type)
                                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; align-items: flex-end;">
                                    <div style="display: flex; flex-direction: column;">
                                        <label style="font-weight: 600;">{{ strtoupper($type) }}</label>
                                        <input type="hidden" name="tickets[{{ $idx }}][jenis_tiket]" value="{{ $type }}" />
                                    </div>
                                    <div style="display: flex; flex-direction: column;">
                                        <label for="tickets_{{ $type }}_harga" style="font-weight: 600;">Harga</label>
                                        <input id="tickets_{{ $type }}_harga" name="tickets[{{ $idx }}][harga]" value="{{ old('tickets.' . $idx . '.harga') }}" type="number" min="0" style="width:100%; margin-top:6px; background: rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.3); color:#fff; padding:8px; border-radius:8px;" />
                                    </div>
                                    <div style="display: flex; flex-direction: column;">
                                        <label for="tickets_{{ $type }}_kuota" style="font-weight: 600;">Kuota</label>
                                        <input id="tickets_{{ $type }}_kuota" name="tickets[{{ $idx }}][kuota]" value="{{ old('tickets.' . $idx . '.kuota') }}" type="number" min="0" style="width:100%; margin-top:6px; background: rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.3); color:#fff; padding:8px; border-radius:8px;" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                <div style="display: flex; flex-direction: column; gap: 18px; justify-content: flex-start;">
                    <div style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.25); border-radius: 12px; padding: 16px; display: flex; flex-direction: column; gap: 12px;">
                        <h3 style="font-size: 1.1rem; margin-bottom: 0;">Poster Event</h3>
                        <input id="foto_event" name="poster" type="file" accept="image/*" style="width: 100%; color: #fff;" />
                        <div id="foto-preview" style="width: 100%; height: 150px; border: 1px dashed rgba(255,255,255,0.45); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.7);">Preview foto</div>
                    </div>
                    <div style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.25); border-radius: 12px; padding: 16px; display: flex; flex-direction: column; gap: 12px;">
                        <label for="deskripsi" style="font-weight: 600;">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" required rows="4" style="width:100%; height:140px; margin-top:8px; background: rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.3); color:#fff; padding:10px; resize: vertical; border-radius:8px;">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>
                
            </div>

            <div style="margin-top: 24px; text-align: center;">
                <button type="submit" style="background: #f3c8dd; color: #3a345b; border: none; border-radius: 10px; padding: 10px 20px; font-weight: 700; font-size: 1.1rem; cursor: pointer; min-width: 200px;">Daftarkan Event</button>
            </div>
        </form>
    </main>

    <script>
        const fotoInput = document.getElementById('foto_event');
        const fotoPreview = document.getElementById('foto-preview');
        fotoInput.addEventListener('change', () => {
            const file = fotoInput.files[0];
            if (!file) {
                fotoPreview.textContent = 'Preview foto';
                fotoPreview.style.backgroundImage = 'none';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                fotoPreview.style.backgroundImage = `url(${e.target.result})`;
                fotoPreview.style.backgroundSize = 'cover';
                fotoPreview.style.backgroundPosition = 'center';
                fotoPreview.textContent = '';
            };
            reader.readAsDataURL(file);
        });
    </script>                

    
</body>
</html>
