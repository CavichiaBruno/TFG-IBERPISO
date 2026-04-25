<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IberPiso') — Encuentra tu hogar ideal</title>
    
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    @stack('styles')
</head>
<body>

<header class="site-header" id="site-header">
    <div class="header-inner">
        <a href="{{ route('home') }}" class="logo">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            <span>Iber<strong>Piso</strong></span>
        </a>

        <nav class="main-nav">
            <a href="{{ route('properties.index', ['operacion'=>'venta']) }}" class="nav-link">Comprar</a>
            <a href="{{ route('properties.index', ['operacion'=>'alquiler']) }}" class="nav-link">Alquilar</a>
            <a href="{{ route('properties.index', ['is_featured'=>1]) }}" class="nav-link">Destacados</a>
        </nav>

        <div class="header-actions">
            @auth
                <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="btn-login-nav" style="border:none; background:none; cursor:pointer;">Salir</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-login-nav">Entrar</a>
                <a href="{{ route('register') }}" class="btn-register-nav">Registro</a>
            @endauth
        </div>

        <button class="menu-toggle" id="menu-toggle" aria-label="Menu">
            <span></span>
            <span></span>
        </button>
    </div>
</header>

<div class="mobile-drawer" id="mobile-drawer">
    <nav>
        <a href="{{ route('properties.index', ['operacion'=>'venta']) }}" class="nav-link">Comprar</a>
        <a href="{{ route('properties.index', ['operacion'=>'alquiler']) }}" class="nav-link">Alquilar</a>
        <a href="{{ route('properties.index', ['is_featured'=>1]) }}" class="nav-link">Destacados</a>
        @auth
            <a href="{{ route('logout') }}" class="nav-link">Salir</a>
        @else
            <a href="{{ route('login') }}" class="nav-link">Entrar</a>
            <a href="{{ route('register') }}" class="nav-link">Registro</a>
        @endauth
    </nav>
</div>

<main>
    @yield('content')
</main>

<footer class="site-footer">
    <div class="container-wide">
        <div class="footer-bottom">
            <div class="footer-legal">
                <p>Copyright © {{ date('Y') }} IberPiso S.A. Todos los derechos reservados.</p>
                <div class="footer-legal-links">
                    <a href="#">Política de privacidad</a>
                    <a href="#">Aviso legal</a>
                    <a href="#">Mapa del sitio</a>
                </div>
            </div>
            <div class="footer-country">España</div>
        </div>
    </div>
</footer>

<script>
    const menuToggle = document.getElementById('menu-toggle');
    const mobileDrawer = document.getElementById('mobile-drawer');

    if (menuToggle && mobileDrawer) {
        menuToggle.addEventListener('click', () => {
            mobileDrawer.classList.toggle('open');
            menuToggle.classList.toggle('active');
            document.body.style.overflow = mobileDrawer.classList.contains('open') ? 'hidden' : '';
        });
    }
</script>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
