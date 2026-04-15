<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel') — IberPiso Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/admin.css') }}">
    @stack('styles')
</head>
<body class="admin-body">

<div class="admin-layout">
    {{-- SIDEBAR --}}
    <aside class="admin-sidebar" id="admin-sidebar">
        <div class="sidebar-logo">
            <svg width="24" height="24" viewBox="0 0 28 28" fill="none" aria-hidden="true">
                <path d="M14 3L2 12h3v13h7v-8h4v8h7V12h3L14 3z" fill="white"/>
            </svg>
            <span>IberPiso <small>Admin</small></span>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" width="18" height="18"><rect x="3" y="3" width="7" height="7" rx="1" stroke="currentColor" fill="none" stroke-width="2"/><rect x="14" y="3" width="7" height="7" rx="1" stroke="currentColor" fill="none" stroke-width="2"/><rect x="3" y="14" width="7" height="7" rx="1" stroke="currentColor" fill="none" stroke-width="2"/><rect x="14" y="14" width="7" height="7" rx="1" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.properties.index') }}" class="sidebar-link {{ request()->routeIs('admin.properties.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" width="18" height="18"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" stroke="currentColor" fill="none" stroke-width="2"/><polyline points="9 22 9 12 15 12 15 22" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                Propiedades
            </a>
            <a href="{{ route('admin.inquiries.index') }}" class="sidebar-link {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" width="18" height="18"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                Consultas
                @if(isset($unreadCount) && $unreadCount > 0)
                    <span class="badge-count">{{ $unreadCount }}</span>
                @endif
            </a>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" width="18" height="18"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" fill="none" stroke-width="2"/><circle cx="9" cy="7" r="4" stroke="currentColor" fill="none" stroke-width="2"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                Usuarios
            </a>
            @endif
        </nav>

        <div class="sidebar-user">
            <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="sidebar-user-info">
                <span class="sidebar-user-name">{{ auth()->user()->name }}</span>
                <span class="sidebar-user-role">{{ ucfirst(auth()->user()->role) }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-logout" title="Cerrar sesión">
                    <svg viewBox="0 0 24 24" width="16" height="16"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="admin-main">
        <header class="admin-topbar">
            <button class="sidebar-toggle" id="sidebar-toggle" aria-label="Toggle sidebar">
                <svg viewBox="0 0 24 24" width="20" height="20"><line x1="3" y1="12" x2="21" y2="12" stroke="currentColor" stroke-width="2"/><line x1="3" y1="6" x2="21" y2="6" stroke="currentColor" stroke-width="2"/><line x1="3" y1="18" x2="21" y2="18" stroke="currentColor" stroke-width="2"/></svg>
            </button>
            <h1 class="topbar-title">@yield('page-title', 'Panel de Administración')</h1>
            <div class="topbar-actions">@yield('topbar-actions')</div>
        </header>

        <div class="admin-content">
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif
            @if(session('error'))
                <x-alert type="error" :message="session('error')" />
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/admin.js') }}"></script>
@stack('scripts')
</body>
</html>
