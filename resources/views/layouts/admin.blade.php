<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel') — IberPiso Admin</title>
    @vite(['resources/css/app.css', 'resources/css/admin/admin.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="admin-body">

    <div class="admin-layout">
        {{-- SIDEBAR --}}
        <aside class="admin-sidebar" id="admin-sidebar">
            <div class="sidebar-logo">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                <span>IberPiso</span>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.properties.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.properties.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Propiedades
                </a>
                <a href="{{ route('admin.inquiries.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    Consultas
                    @if(isset($unreadCount) && $unreadCount > 0)
                        <span style="margin-left: auto; background: var(--admin-accent); color: white; font-size: 10px; padding: 2px 6px; border-radius: 6px; font-weight: 700;">{{ $unreadCount }}</span>
                    @endif
                </a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.users.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Usuarios
                    </a>
                @endif
                <a href="{{ route('admin.interactions.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.interactions.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                    Interacciones
                </a>
                <a href="{{ route('admin.articles.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M4 22h14a2 2 0 0 0 2-2V7.5L14.5 2H6a2 2 0 0 0-2 2v18z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="8" y1="13" x2="16" y2="13"></line>
                        <line x1="8" y1="17" x2="16" y2="17"></line>
                    </svg>
                    Noticias
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}</div>
                    <div class="sidebar-user-info">
                        <span class="sidebar-user-name">{{ auth()->user()->nombre }}</span>
                        <span class="sidebar-user-role">{{ auth()->user()->rol }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="icon-btn" style="color: rgba(255,255,255,0.4);" title="Cerrar sesión">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- MAIN --}}
        <div class="admin-main">
            <header class="admin-topbar">
                <div class="topbar-left">
                    <button class="icon-btn" id="sidebar-toggle" style="margin-left: -8px;">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                    <div class="topbar-breadcrumb">IberPiso Admin</div>
                </div>
                <div class="topbar-right">
                    <a href="{{ url('/') }}" target="_blank" class="btn-admin btn-admin-outline" style="padding: 6px 12px; font-size: 12px;">Ver Portal</a>
                </div>
            </header>

            <main class="admin-content">
                @if(session('success'))
                    <x-alert type="success" :message="session('success')" />
                @endif
                @if(session('error'))
                    <x-alert type="error" :message="session('error')" />
                @endif

                @yield('content')
            </main>
        </div>
        <div class="sidebar-overlay" id="sidebar-overlay"></div>
    </div>

    @vite(['resources/js/app.js', 'resources/js/admin/admin.js'])
    @stack('scripts')
</body>

</html>