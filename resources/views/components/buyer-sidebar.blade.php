@php
    $unreadNotifCount = 0;
    if(auth()->check()) {
        $unreadNotifCount = \Illuminate\Support\Facades\DB::table('notifikasi')->where('id_user', auth()->id())->where('is_read', 0)->count();
    }
@endphp
<aside class="sidebar">
    <div class="sidebar-content" style="display: flex; flex-direction: column; height: calc(100vh - var(--topbar-height));">
        <ul class="sidebar-menu" style="flex-grow: 1; padding-top: 20px;">
            <li>
                <a href="{{ url('/dashboard') }}" class="sidebar-item {{ request()->is('dashboard*') || request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="ph ph-house sidebar-icon"></i>
                    <span class="sidebar-text">Home</span>
                </a>
            </li>
            <li>
                <a href="{{ route('my-tickets') }}" class="sidebar-item {{ request()->routeIs('my-tickets') ? 'active' : '' }}">
                    <i class="ph ph-ticket sidebar-icon"></i>
                    <span class="sidebar-text">My Tickets</span>
                </a>
            </li>
            <li>
                <a href="{{ route('buyer.notification') }}" class="sidebar-item {{ request()->routeIs('buyer.notification') ? 'active' : '' }}" style="position: relative;">
                    <i class="ph ph-bell sidebar-icon"></i>
                    <span class="sidebar-text">Notifications</span>
                    @if(isset($unreadNotifCount) && $unreadNotifCount > 0)
                        <span style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); background: #E74C3C; color: white; border-radius: 50%; width: 22px; height: 22px; display:flex; align-items:center; justify-content:center; font-size: 0.75rem; font-weight: bold; box-shadow: 0 0 5px rgba(231, 76, 60, 0.5);">{{ $unreadNotifCount }}</span>
                    @endif
                </a>
            </li>
        </ul>

        <div style="padding: 10px 0;">
            <form action="{{ route('logout') }}" method="POST" style="margin: 0; width: 100%;">
                @csrf
                <button type="submit" class="sidebar-item" style="background: transparent; border: none; color: var(--queen-pink); width: 100%; text-align: left; padding: 15px 22px; cursor: pointer; transition: all 0.3s ease;">
                    <i class="ph ph-sign-out sidebar-icon"></i>
                    <span class="sidebar-text">Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>