@props(['showSearch' => false])

<header class="topbar">
    <div class="logo">TIXORA</div>
    <div style="display: flex; align-items: center; gap: 12px;">
        @if($showSearch)
        <div class="search-box" style="display: flex; align-items: center; border: 1px solid rgba(243, 200, 221, 0.5); border-radius: 999px; background: rgba(255, 255, 255, 0.08); padding: 6px 12px; min-width: 220px;">
            <i class="ph ph-magnifying-glass" style="color: var(--queen-pink); font-size: 1rem; margin-right: 8px;"></i>
            <input type="text" placeholder="Search events..." style="width: 100%; border: none; outline: none; background: transparent; color: var(--queen-pink); font-size: 0.95rem;" />
        </div>
        @endif
        <a href="{{ route('profile.edit') }}" class="profile" title="My Profile" style="text-decoration:none;">
            @if(auth()->check() && auth()->user()->photo_profile)
                <img src="{{ asset(auth()->user()->photo_profile) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            @else
                {{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'O', 0, 1)) }}
            @endif
        </a>
    </div>
</header>