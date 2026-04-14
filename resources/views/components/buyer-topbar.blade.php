@props(['showSearch' => false])

<header class="topbar">
    <div class="logo">TIXORA</div>
    <div style="display: flex; align-items: center; gap: 15px;">
        @if($showSearch)
        <div class="search-box" style="display: flex; align-items: center; border: 1px solid rgba(243, 200, 221, 0.4); border-radius: 50px; background: rgba(58, 52, 91, 0.4); padding: 8px 18px; min-width: 250px; transition: all 0.3s ease;">
            <i class="ph ph-magnifying-glass" style="color: var(--queen-pink); font-size: 1.1rem; margin-right: 10px;"></i>
            <input type="text" id="globalSearch" placeholder="Search events..." style="width: 100%; border: none; outline: none; background: transparent; color: #fff; font-size: 0.95rem; font-family: 'Outfit', sans-serif;" />
        </div>
        @endif
        <a href="{{ route('profile.edit') }}" class="profile" title="My Profile" style="text-decoration:none;">
            @if(auth()->check() && auth()->user()->photo_profile)
                <img src="{{ asset(auth()->user()->photo_profile) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            @else
                {{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'U', 0, 1)) }}
            @endif
        </a>
    </div>
</header>