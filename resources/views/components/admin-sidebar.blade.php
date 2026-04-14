@props(['pageUnreadCount' => 0, 'unreadCount' => 0])

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
            <li>
                <a href="{{ route('admin.statistik') }}" class="sidebar-item {{ Request::is('admin/statistik') ? 'active' : '' }}">
                    <i class="ph ph-chart-bar sidebar-icon"></i>
                    <span class="sidebar-text">Analitik Penjualan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.notifications') }}" class="sidebar-item {{ Request::is('admin/notifikasi') ? 'active' : '' }}" style="position: relative;">
                    <i class="ph ph-bell sidebar-icon"></i>
                    <span class="sidebar-text">Notifikasi</span>
                    @php $badgeCount = $pageUnreadCount > 0 ? $pageUnreadCount : ($unreadCount > 0 ? $unreadCount : 0); @endphp
                    @if($badgeCount > 0)
                    <span style="position: absolute; right: 15px; margin-top: -3px; background: #ef4444; color: white; font-size: 0.65rem; font-weight: bold; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border-radius: 50%; box-shadow: 0 0 10px rgba(239, 68, 68, 0.6);">{{ $badgeCount }}</span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/user-management') }}" class="sidebar-item {{ Request::is('admin/user-management*') || Request::is('admin/users*') ? 'active' : '' }}">
                    <i class="ph ph-users-three sidebar-icon"></i>
                    <span class="sidebar-text">User Management</span>
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
