<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IberPiso') — Encuentra tu hogar ideal</title>
    <meta name="description" content="@yield('meta_description', 'IberPiso - Portal inmobiliario líder en España. Compra, alquila o vende propiedades.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    @stack('styles')
</head>
<body>

{{-- HEADER --}}
<header class="site-header" id="site-header">
    <div class="header-inner container">
        <a href="{{ route('home') }}" class="logo">
            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" aria-hidden="true">
                <path d="M14 3L2 12h3v13h7v-8h4v8h7V12h3L14 3z" fill="var(--primary)"/>
            </svg>
            <span>Iber<strong>Piso</strong></span>
        </a>

        <nav class="main-nav" id="main-nav">
            <a href="{{ route('properties.index', ['operacion'=>'venta']) }}" class="nav-link">Comprar</a>
            <a href="{{ route('properties.index', ['operacion'=>'alquiler']) }}" class="nav-link">Alquilar</a>
            <a href="{{ route('properties.index', ['is_featured'=>1]) }}" class="nav-link">Destacados</a>
            <a href="#sobre-nosotros" class="nav-link">Sobre nosotros</a>
        </nav>

        <div class="header-actions">
            @auth
                @if(auth()->user()->hasAdminAccess())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Panel</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="btn btn-outline">Salir</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Registrarse</a>
            @endauth
        </div>

        <button class="hamburger" id="hamburger" aria-label="Menú" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>

{{-- MOBILE DRAWER --}}
<div class="mobile-drawer" id="mobile-drawer" aria-hidden="true">
    <nav>
        <a href="{{ route('properties.index', ['operacion'=>'venta']) }}">Comprar</a>
        <a href="{{ route('properties.index', ['operacion'=>'alquiler']) }}">Alquilar</a>
        <a href="{{ route('properties.index', ['is_featured'=>1]) }}">Destacados</a>
        @auth
            <a href="{{ route('admin.dashboard') }}">Panel Admin</a>
        @else
            <a href="{{ route('login') }}">Iniciar sesión</a>
            <a href="{{ route('register') }}">Registrarse</a>
        @endauth
    </nav>
</div>
<div class="drawer-overlay" id="drawer-overlay"></div>

<main>
    @if(session('success'))
        <div class="container" style="padding-top:1rem">
            <x-alert type="success" :message="session('success')" />
        </div>
    @endif
    @yield('content')
</main>

<footer class="site-footer" id="sobre-nosotros">
    <div class="footer-inner container">
        <div class="footer-col">
            <div class="footer-logo">
                <svg width="24" height="24" viewBox="0 0 28 28" fill="none" aria-hidden="true">
                    <path d="M14 3L2 12h3v13h7v-8h4v8h7V12h3L14 3z" fill="white"/>
                </svg>
                IberPiso
            </div>
            <p class="footer-tagline">Tu portal inmobiliario de confianza en España.</p>
            <div class="social-icons">
                <a href="#" aria-label="Facebook"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" stroke="currentColor" fill="none" stroke-width="2"/></svg></a>
                <a href="#" aria-label="Instagram"><svg viewBox="0 0 24 24" width="20" height="20"><rect x="2" y="2" width="20" height="20" rx="5" stroke="currentColor" fill="none" stroke-width="2"/><circle cx="12" cy="12" r="4" stroke="currentColor" fill="none" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg></a>
                <a href="#" aria-label="Twitter/X"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M4 4l16 16M20 4L4 20" stroke="currentColor" stroke-width="2"/></svg></a>
            </div>
        </div>
        <div class="footer-col">
            <h4>Inmuebles</h4>
            <ul>
                <li><a href="{{ route('properties.index', ['operacion'=>'venta']) }}">Comprar</a></li>
                <li><a href="{{ route('properties.index', ['operacion'=>'alquiler']) }}">Alquilar</a></li>
                <li><a href="{{ route('properties.index', ['tipo'=>'piso']) }}">Pisos</a></li>
                <li><a href="{{ route('properties.index', ['tipo'=>'chalet']) }}">Chalets</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Empresa</h4>
            <ul>
                <li><a href="#">Sobre nosotros</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">Trabaja con nosotros</a></li>
                <li><a href="#">Aviso legal</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Contacto</h4>
            <ul>
                <li>
                    <svg viewBox="0 0 24 24" width="14" height="14" style="display:inline;vertical-align:middle;margin-right:6px;opacity:.7" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 011 1.18 2 2 0 013 1h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L7.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>+34 900 000 000
                </li>
                <li>
                    <svg viewBox="0 0 24 24" width="14" height="14" style="display:inline;vertical-align:middle;margin-right:6px;opacity:.7" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>hola@iberpiso.es
                </li>
                <li>
                    <svg viewBox="0 0 24 24" width="14" height="14" style="display:inline;vertical-align:middle;margin-right:6px;opacity:.7" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Lun–Vie 9:00–19:00
                </li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© {{ date('Y') }} IberPiso. Todos los derechos reservados.</p>
    </div>
</footer>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
