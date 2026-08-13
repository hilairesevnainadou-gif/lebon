{{--
    Sidebar partagé — desktop + mobile
    Inclure avec : @include('partials.sidebar')
--}}

{{-- ===== SIDEBAR DESKTOP ===== --}}
<aside class="sidebar">
    <div class="sidebar-logo">
        <span>le<em>bon</em>coin</span>
    </div>
    <nav class="sidebar-nav">
        <a href="{{ route('ads.index') }}"
           class="nav-item {{ request()->routeIs('ads.*') ? 'active' : '' }}"
           style="text-decoration:none;">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <span class="nav-text">Mes annonces</span>
            @isset($ads)
                <span class="nav-badge">{{ $ads->total() }}</span>
            @endisset
        </a>

        @if(auth()->user()->hasPermission('menu.pc.view'))
        <a href="{{ route('pc.index') }}"
           class="nav-item {{ request()->routeIs('pc.*') ? 'active' : '' }}"
           style="text-decoration:none;">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                <line x1="8" y1="21" x2="16" y2="21"/>
                <line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
            <span class="nav-text">Annonces PC</span>
        </a>
        @endif

        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.ads.index') }}"
           class="nav-item {{ request()->routeIs('admin.ads.*') ? 'active' : '' }}"
           style="text-decoration:none;">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="3" y="3" width="7" height="7" rx="1"/>
                <rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/>
                <rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            <span class="nav-text">Toutes les annonces</span>
        </a>
        @endif

        @if(auth()->user()->hasPermission('menu.users.view'))
        <a href="{{ route('users.index') }}"
           class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}"
           style="text-decoration:none;">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                <path d="M16 3.13a4 4 0 010 7.75"/>
            </svg>
            <span class="nav-text">Utilisateurs</span>
        </a>
        @endif
    </nav>
    <div class="sidebar-footer">
        <button onclick="openLogoutModal()" class="logout-btn"
                style="background:none;border:none;color:rgba(255,255,255,0.4);font-size:13px;font-weight:600;cursor:pointer;width:100%;text-align:left;padding:12px;border-radius:10px;">
            Déconnexion
        </button>
    </div>
</aside>

{{-- ===== MOBILE HEADER ===== --}}
<div class="mobile-header">
    <button class="menu-toggle" onclick="toggleMobileMenu()">
        <svg width="24" height="24" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
            <line x1="3" y1="12" x2="21" y2="12"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
    </button>
    <div class="mobile-logo">le<em>bon</em>coin</div>
    <a href="{{ route('ads.create') }}" style="color:white;text-decoration:none;">
        <svg width="22" height="22" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
            <path d="M12 4v16M4 12h16"/>
        </svg>
    </a>
</div>

{{-- ===== MOBILE SIDEBAR ===== --}}
<div class="mobile-sidebar" id="mobileSidebar">
    <div class="sidebar-logo">
        <span>le<em>bon</em>coin</span>
    </div>
    <nav class="sidebar-nav">
        <a href="{{ route('ads.index') }}"
           class="nav-item {{ request()->routeIs('ads.*') ? 'active' : '' }}"
           style="text-decoration:none;">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <span class="nav-text">Mes annonces</span>
        </a>

        @if(auth()->user()->hasPermission('menu.pc.view'))
        <a href="{{ route('pc.index') }}"
           class="nav-item {{ request()->routeIs('pc.*') ? 'active' : '' }}"
           style="text-decoration:none;">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                <line x1="8" y1="21" x2="16" y2="21"/>
                <line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
            <span class="nav-text">Annonces PC</span>
        </a>
        @endif

        @if(auth()->user()->hasPermission('menu.users.view'))
        <a href="{{ route('users.index') }}"
           class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}"
           style="text-decoration:none;">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
            </svg>
            <span class="nav-text">Utilisateurs</span>
        </a>
        @endif
    </nav>
    <div class="sidebar-footer">
        <button onclick="openLogoutModal()" class="logout-btn"
                style="background:none;border:none;color:rgba(255,255,255,0.4);font-size:13px;font-weight:600;cursor:pointer;width:100%;text-align:left;padding:12px;border-radius:10px;">
            Déconnexion
        </button>
    </div>
</div>
<div class="overlay" id="overlay" onclick="toggleMobileMenu()"></div>
