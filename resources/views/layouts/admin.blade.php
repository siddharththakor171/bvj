<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') | B V JEWELLERS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body>

<div class="admin-wrapper">
    <!-- Off-canvas Mobile Backdrop -->
    <div class="mobile-backdrop" id="mobileBackdrop" onclick="toggleMobileSidebar()"></div>

    <!-- Collapsible Sidebar Navigation -->
    <aside class="admin-sidebar" id="adminSidebar">
        <!-- Sidebar Header / Logo -->
        <div class="admin-sidebar-header">
            <div class="brand-logo-container">
                <div class="brand-crest">BV</div>
                <div class="brand-text">
                    <span class="name">B V JEWELLERS</span>
                    <span class="tagline">Fine Jewellery & Bullion</span>
                </div>
            </div>
            <!-- Desktop Collapse Toggle Button -->
            <button type="button" class="sidebar-collapse-btn" id="desktopCollapseBtn" onclick="toggleDesktopSidebar()" title="Toggle Sidebar">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
        </div>

        <!-- Sidebar Navigation Items -->
        <nav class="admin-sidebar-nav">
            <div class="nav-section-title">Overview</div>
            
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" data-tooltip="Dashboard">
                <span class="nav-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                </span>
                <span class="nav-label">Dashboard</span>
            </a>

            <div class="nav-section-title">Jewellery Management</div>

            <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" data-tooltip="Jewellery Catalog">
                <span class="nav-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="6 3 18 3 22 9 12 22 2 9 6 3"></polygon>
                    </svg>
                </span>
                <span class="nav-label">Vault & Catalog</span>
                <span class="nav-badge">{{ \App\Models\JewelryProduct::count() }}</span>
            </a>

            <div class="nav-section-title">Security & Settings</div>

            <!-- Explicit Change Password Page Link -->
            <a href="{{ route('admin.password.change') }}" class="nav-item {{ request()->routeIs('admin.password.*') ? 'active' : '' }}" data-tooltip="Change Password">
                <span class="nav-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </span>
                <span class="nav-label">Change Password</span>
            </a>

            <a href="{{ route('admin.profile.show') }}" class="nav-item {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" data-tooltip="Store Profile">
                <span class="nav-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </span>
                <span class="nav-label">Store Profile</span>
            </a>
        </nav>

        <!-- Sidebar Footer / User Card -->
        <div class="admin-sidebar-footer">
            <div class="sidebar-user-card">
                <div class="sidebar-user-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="sidebar-user-info">
                    <div class="user-name">{{ Auth::user()->name ?? 'Admin' }}</div>
                    <div class="user-role">{{ Auth::user()->role ?? 'Jeweller Master' }}</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Container with dynamic width adjustment -->
    <main class="admin-main" id="adminMain">
        <!-- Sticky Top Navigation Bar -->
        <header class="admin-header">
            <div class="header-left">
                <!-- Mobile Drawer Toggle -->
                <button type="button" class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>

                <!-- Live Bullion Rate Ticker -->
                @php
                    $headerRates = app(\App\Services\LiveMetalRateService::class)->currentRates();
                    $gold24 = $headerRates->firstWhere('metal_code', 'gold_24k');
                    $gold22 = $headerRates->firstWhere('metal_code', 'gold_22k');
                    $silver999 = $headerRates->firstWhere('metal_code', 'silver_999');
                @endphp
                <div class="header-bullion-ticker">
                    <div class="live-indicator">
                        <span class="live-dot"></span> LIVE
                    </div>
                    @if($gold24)
                    <div class="rate-ticker-item">
                        <span class="rate-name">24K Gold:</span>
                        <span class="rate-value">₹{{ number_format($gold24->rate_per_gram, 0) }}/g</span>
                    </div>
                    @endif
                    <span class="rate-ticker-divider">|</span>
                    @if($gold22)
                    <div class="rate-ticker-item">
                        <span class="rate-name">22K Gold (916):</span>
                        <span class="rate-value">₹{{ number_format($gold22->rate_per_gram, 0) }}/g</span>
                    </div>
                    @endif
                    <span class="rate-ticker-divider">|</span>
                    @if($silver999)
                    <div class="rate-ticker-item">
                        <span class="rate-name">Silver:</span>
                        <span class="rate-value">₹{{ number_format($silver999->rate_per_gram, 1) }}/g</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Header Right Section -->
            <div class="header-right">
                <!-- User Profile Dropdown -->
                <div class="header-user-dropdown" id="userDropdownContainer">
                    <button type="button" class="header-user-btn" onclick="toggleUserDropdown()">
                        <div class="header-user-avatar">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <span style="font-size: 0.85rem; font-weight: 600;">{{ Auth::user()->username ?? 'admin' }}</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>

                    <div class="dropdown-menu" id="userDropdownMenu">
                        <div style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--border-subtle); background: #fbf9f4;">
                            <div style="font-weight: 700; color: #1f1c18;">{{ Auth::user()->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--gold-primary); font-weight: 600;">{{ Auth::user()->email }}</div>
                        </div>

                        <a href="{{ route('admin.password.change') }}" class="dropdown-item">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            Change Password
                        </a>

                        <a href="{{ route('admin.profile.show') }}" class="dropdown-item">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                            Store Settings
                        </a>

                        <div class="dropdown-divider"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item" style="color: #f87171;">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content View -->
        <div class="admin-content">
            <!-- Flash Notification Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

<!-- Core Interactivity JavaScript -->
<script>
    // 1. Sidebar Collapse & Expansion Persistence for Desktop
    function initSidebarState() {
        const sidebar = document.getElementById('adminSidebar');
        const main = document.getElementById('adminMain');
        const isCollapsed = localStorage.getItem('bvj_sidebar_collapsed') === 'true';

        if (window.innerWidth > 1024 && isCollapsed) {
            sidebar.classList.add('collapsed');
            main.classList.add('sidebar-collapsed');
        }
    }

    function toggleDesktopSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const main = document.getElementById('adminMain');
        
        sidebar.classList.toggle('collapsed');
        main.classList.toggle('sidebar-collapsed');

        const isCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('bvj_sidebar_collapsed', isCollapsed);
    }

    // 2. Off-canvas Mobile Drawer Toggle
    function toggleMobileSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const backdrop = document.getElementById('mobileBackdrop');

        sidebar.classList.toggle('mobile-open');
        backdrop.classList.toggle('active');
    }

    // 3. User Profile Dropdown
    function toggleUserDropdown() {
        const menu = document.getElementById('userDropdownMenu');
        menu.classList.toggle('show');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const container = document.getElementById('userDropdownContainer');
        const menu = document.getElementById('userDropdownMenu');
        if (container && !container.contains(e.target) && menu.classList.contains('show')) {
            menu.classList.remove('show');
        }
    });

    // 4. Modal Helpers
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    }

    // Initialize on DOM load
    document.addEventListener('DOMContentLoaded', initSidebarState);
</script>

@stack('scripts')
</body>
</html>
