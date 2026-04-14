@php
    $unreadCount = \Illuminate\Support\Facades\DB::table('notifikasi')
        ->where('id_user', auth()->id())
        ->where('is_read', 0)
        ->count();
@endphp
<aside class="sidebar">
    <div class="sidebar-content" style="display: flex; flex-direction: column; height: calc(100vh - var(--topbar-height));">
        <ul class="sidebar-menu" style="flex-grow: 1; padding-top: 20px;">
            <li>
                <a href="{{ url('/organizerdashboard') }}" class="sidebar-item {{ request()->is('organizerdashboard*') ? 'active' : '' }}">
                    <i class="ph ph-house sidebar-icon"></i>
                    <span class="sidebar-text">Home</span>
                </a>
            </li>
            <li>
                <a href="{{ route('organizer.statistik') }}" class="sidebar-item {{ request()->routeIs('organizer.statistik*') ? 'active' : '' }}">
                    <i class="ph ph-chart-bar sidebar-icon"></i>
                    <span class="sidebar-text">Analitik Penjualan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('organizer.revenue') }}" class="sidebar-item {{ request()->routeIs('organizer.revenue*') ? 'active' : '' }}">
                    <i class="ph ph-currency-dollar sidebar-icon"></i>
                    <span class="sidebar-text">Revenue</span>
                </a>
            </li>
            <li>
                <a href="{{ route('organizer.checkin') }}" class="sidebar-item {{ request()->is('organizer/checkin*') ? 'active' : '' }}">
                    <i class="ph ph-qr-code sidebar-icon"></i>
                    <span class="sidebar-text">Check In</span>
                </a>
            </li>
            <li>
                <a href="{{ route('organizer.notifications') }}" class="sidebar-item {{ request()->routeIs('organizer.notifications*') || request()->routeIs('organizer.detailevent') ? 'active' : '' }}" style="position: relative;">
                    <i class="ph ph-bell sidebar-icon"></i>
                    <span class="sidebar-text">Notifications</span>
                    @if($unreadCount > 0)
                    <span style="position: absolute; right: 15px; margin-top: -3px; background: #ef4444; color: white; font-size: 0.65rem; font-weight: bold; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border-radius: 50%; box-shadow: 0 0 10px rgba(239, 68, 68, 0.6);">{{ $unreadCount }}</span>
                    @endif
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