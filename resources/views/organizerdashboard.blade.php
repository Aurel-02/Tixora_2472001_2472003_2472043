or<!DOCTYPE html>
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .category-tabs {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 40px;
            padding: 30px 20px;
            border-bottom: 1px solid rgba(243, 200, 221, 0.15);
            background: rgba(58, 52, 91, 0.2);
        }

        .tab-btn {
            background: transparent;
            border: none;
            color: var(--queen-pink);
            font-size: 1.2rem;
            font-weight: 500;
            cursor: pointer;
            padding: 10px 5px;
            position: relative;
            opacity: 0.6;
            transition: opacity 0.3s, color 0.3s;
        }

        .tab-btn:hover {
            opacity: 0.9;
        }

        .tab-btn.active {
            opacity: 1;
            font-weight: 700;
            color: #fff;
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--middle-purple);
            border-radius: 3px;
            box-shadow: 0 0 8px var(--middle-purple);
        }

        .artist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 30px;
            padding: 40px;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        .artist-card {
            background: rgba(113, 85, 122, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(243, 200, 221, 0.15);
            border-radius: 15px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.4s ease, box-shadow 0.4s ease, border-color 0.4s ease;
            display: flex;
            flex-direction: column;
        }

        .artist-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            border-color: var(--middle-purple);
        }

        .artist-card-img {
            height: 260px;
            background: rgba(255, 255, 255, 0.05); 
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: rgba(243, 200, 221, 0.4);
            font-size: 0.95rem;
            position: relative;
            border-bottom: 1px solid rgba(243, 200, 221, 0.05);
        }

        .ph-image {
            font-size: 3rem;
            opacity: 0.3;
            margin-bottom: 10px;
        }

        .artist-card-info {
            padding: 20px 15px;
            text-align: center;
            flex-grow: 1;
        }

        .artist-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 5px;
            text-shadow: 0 0 5px rgba(243, 200, 221, 0.3);
        }

        .artist-desc {
            font-size: 0.85rem;
            color: var(--queen-pink);
            opacity: 0.8;
            font-weight: 300;
        }

        .section-header {
            padding: 0 40px;
            margin-top: 10px;
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            max-width: 1400px;
            margin: 10px auto 0;
            width: 100%;
        }

        .section-header::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(243, 200, 221, 0.15);
            margin-left: 20px;
        }

        .event-list {
            display: flex;
            gap: 20px;
            padding: 25px 40px 40px 40px;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -ms-overflow-style: none; 
            scrollbar-width: none; 
            scroll-behavior: smooth;
        }
        
        .event-list::-webkit-scrollbar {
            display: none; 
        }

        .event-card {
            background: rgba(113, 85, 122, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(243, 200, 221, 0.15);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
            flex: 0 0 calc(50% - 10px);
            min-width: 380px;
            scroll-snap-align: center;
        }

        .event-card:hover {
            transform: translateY(-5px);
            border-color: var(--middle-purple);
            background: rgba(113, 85, 122, 0.3);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .event-date {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: rgba(209, 131, 169, 0.15);
            color: var(--queen-pink);
            border-radius: 8px;
            min-width: 80px;
            height: 80px;
            border: 1px solid rgba(209, 131, 169, 0.3);
        }
        
        .event-date .month {
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .event-date .day {
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1;
            margin-top: 2px;
            color: #fff;
        }

        .event-details {
            flex-grow: 1;
            margin: 0 20px;
        }

        .event-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 5px;
        }

        .event-location {
            font-size: 0.9rem;
            color: var(--queen-pink);
            opacity: 0.8;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .event-action .btn-buy {
            background: var(--middle-purple);
            color: var(--jacarta);
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(209, 131, 169, 0.3);
            text-decoration: none;
            display: inline-block;
        }

        .event-action .btn-buy:hover {
            background: var(--queen-pink);
            box-shadow: 0 0 15px rgba(243, 200, 221, 0.5);
            transform: scale(1.05);
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
            .artist-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 15px;
                padding: 20px;
            }
            .artist-card-img {
                height: 200px;
            }
            .section-header { padding: 0 20px; }
            .event-list { padding: 20px 20px 40px 20px; scroll-padding: 0 20px; }
            .event-card { flex-direction: column; text-align: center; gap: 15px; min-width: 280px; width: 100%; flex: 0 0 100%; }
            .event-details { margin: 0; }
        }
    </style>
</head>
<body>

    <header class="topbar">
        <div class="logo">TIXORA</div>
        <div style="display: flex; align-items: center; gap: 12px;">
            <div class="search-box" style="display: flex; align-items: center; border: 1px solid rgba(243, 200, 221, 0.5); border-radius: 999px; background: rgba(255, 255, 255, 0.08); padding: 6px 12px; min-width: 220px;">
                <i class="ph ph-magnifying-glass" style="color: var(--queen-pink); font-size: 1rem; margin-right: 8px;"></i>
                <input type="text" placeholder="Search" style="width: 100%; border: none; outline: none; background: transparent; color: var(--queen-pink); font-size: 0.95rem;" />
            </div>
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
                    <a href="#" class="sidebar-item active">
                        <i class="ph ph-house sidebar-icon"></i>
                        <span class="sidebar-text">Home</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('organizer.events.create') }}" class="sidebar-item">
                        <i class="ph ph-plus-circle sidebar-icon"></i>
                        <span class="sidebar-text">Tambah Event</span>
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
        <div class="section-header" style="margin-top: 10px;">
            Organizer Dashboard
        </div>

        <div class="category-tabs">
            <button class="tab-btn active" onclick="renderCategory('indonesia', this)">Indonesia</button>
            <button class="tab-btn" onclick="renderCategory('western', this)">Western</button>
            <button class="tab-btn" onclick="renderCategory('kpop', this)">K-Pop</button>
        </div>

        <div class="artist-grid" id="artistGrid">

        </div>

        <div class="event-list" id="eventList">
        </div>
    </main>

    <script>
        const data = @json($eventsByCategory ?? ['indonesia' => [], 'western' => [], 'kpop' => []]);

        const gridContainer = document.getElementById('artistGrid');
        const searchInput = document.querySelector('.search-box input');
        let currentCategory = 'indonesia';

        function formatDate(dateString) {
            if (!dateString) return '-';
            const d = new Date(dateString);
            const options = { day: '2-digit', month: 'short', year: 'numeric' };
            return d.toLocaleDateString('id-ID', options);
        }

        function createEventCard(event) {
            const imageUrl = event.poster || event.gambar_event || event.image_url || event.banner || event.image || null;
            const eventDate = event.tanggal_pelaksanaan ? formatDate(event.tanggal_pelaksanaan) : '-';
            const eventId = event.id_event || event.id;

            return `
                <a href="/organizer/event/${eventId}" style="text-decoration: none; display: block; color: inherit;">
                    <div class="artist-card">
                        <div class="artist-card-img">
                            ${imageUrl ? `<img src="${imageUrl}" alt="${event.nama_event}" style="width: 100%; height: 100%; object-fit: cover;" />` : '<i class="ph ph-ticket"></i>'}
                        </div>
                        <div class="artist-card-info">
                            <div class="artist-name">${event.nama_event || 'Nama event tidak tersedia'}</div>
                            <div class="artist-desc"><i class="ph ph-map-pin"></i> ${event.lokasi_event || 'Lokasi belum diset'}</div>
                            <div class="artist-desc"><i class="ph ph-calendar"></i> ${eventDate}</div>
                        </div>
                    </div>
                </a>
            `;
        }

        function renderEvents(events, isSearch = false) {
            if (!events.length) {
                const message = isSearch ? 'Belum ada event yang sesuai dengan pencarian' : 'Belum ada event terdaftar';
                gridContainer.innerHTML = `<p style="color: var(--queen-pink); padding: 20px; width: 100%; grid-column: 1 / -1; text-align: center;">${message}</p>`;
                return;
            }

            const html = events.map(createEventCard).join('');
            gridContainer.innerHTML = html;
        }

        function getCurrentCategoryEvents() {
            return data[currentCategory] || [];
        }

        function renderCategory(category, btnElement) {
            currentCategory = category;
            applySearchFilter();
        }

        function applySearchFilter() {
            const query = (searchInput.value || '').trim().toLowerCase();
            let events = [];

            if (query) {
                const allCategories = ['indonesia', 'western', 'kpop'];
                allCategories.forEach(cat => {
                    events = events.concat(data[cat] || []);
                });
                
                events = Array.from(new Map(events.map(item => [item.id_event || item.id, item])).values());

                events = events.filter(event => {
                    const name = (event.nama_event || '').toLowerCase();
                    const location = (event.lokasi_event || '').toLowerCase();
                    return name.includes(query) || location.includes(query);
                });
                
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('active');
                    btn.style.opacity = '0.5';
                });
                
                renderEvents(events, true);
            } else {
                events = getCurrentCategoryEvents();
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.style.opacity = '';
                    btn.classList.remove('active');
                    const btnCat = btn.getAttribute('onclick')?.match(/'([^']+)'/);
                    if (btnCat && btnCat[1] === currentCategory) {
                        btn.classList.add('active');
                    }
                });
                
                renderEvents(events, false);
            }
        }

        searchInput.addEventListener('input', applySearchFilter);

        window.onload = () => {
            renderCategory(currentCategory, document.querySelector('.tab-btn.active'));
        };
    </script>
</body>
</html>
