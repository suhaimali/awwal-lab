<header id="awlab-header">
    <!-- Toggle button -->
    <button class="header-toggle-btn" onclick="toggleSidebar()" title="Toggle Sidebar">
        <i class="fa fa-bars" style="font-size:15px;"></i>
    </button>

    <!-- Page title (dynamic) -->
    <div class="header-page-title" id="header-page-title">
        @yield('page-title', config('app.name', 'SUHAIM SOFT'))
    </div>

    <!-- Clock -->
    <div class="header-clock d-none d-md-flex">
        <span class="time-val" id="header-live-time"></span>
        <span class="date-val" id="header-live-date"></span>
    </div>

    <!-- User dropdown -->
    <div class="dropdown">
        <button class="header-avatar-btn" data-bs-toggle="dropdown" id="userDropdown" aria-expanded="false">
            <div class="header-avatar">A</div>
            <div class="header-user-info">
                <div class="u-name">SUHAIM SOFT</div>
                <div class="u-role">Administrator</div>
            </div>
            <i class="fa fa-chevron-down" style="font-size:11px; color:var(--text-muted); margin-left:4px;"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2" style="min-width:180px;">
            <li>
                <div class="px-3 py-2 border-bottom">
                    <div style="font-size:13px; font-weight:600; color:var(--text-dark);">SUHAIM SOFT</div>
                    <div style="font-size:11px; color:var(--text-muted);">Administrator</div>
                </div>
            </li>
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('logout') }}"
                   style="font-size:13px; font-weight:500; color:#dc2626;">
                    <i class="fa fa-power-off"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</header>
