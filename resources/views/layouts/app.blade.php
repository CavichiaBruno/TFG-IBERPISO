<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IberPiso') — Encuentra tu hogar ideal</title>
    
    @vite('resources/css/app.css')
    @stack('styles')
    {{-- Global Lottie Script --}}
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.9.10/dist/dotlottie-wc.js" type="module"></script>
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
            <a href="{{ route('home') }}" class="nav-link">Inicio</a>
            <a href="{{ route('properties.index') }}" class="nav-link">Propiedades</a>
            <a href="{{ route('scroll') }}" class="nav-link">IberScroll</a>
        </nav>



        <div class="header-actions">
            @auth
                <div class="user-profile-dropdown">
                    <button class="user-profile-btn" id="userDropdownBtn" aria-label="Mi perfil">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                    </button>
                    <div class="dropdown-menu">
                        <div class="dropdown-item" style="font-weight: 600; font-size: 13px; color: var(--gray-mid);">Hola, {{ Auth::user()->name }}</div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('user.properties.create') }}" class="dropdown-item" style="color: var(--apple-blue, #0071e3); font-weight: 500;">
                            <svg viewBox="0 0 24 24" width="16" height="16" style="display:inline;vertical-align:middle;margin-right:8px;" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Crear publicación
                        </a>
                        <div class="dropdown-divider"></div>
                        @if(Auth::user()->hasAdminAccess())
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-item">Panel de Control</a>
                        @endif
                        <a href="{{ route('user.properties.index') }}" class="dropdown-item">Mis Publicaciones</a>
                        <a href="{{ route('saved') }}" class="dropdown-item">Mis Guardados</a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item" style="color: var(--danger);">Cerrar sesión</button>
                        </form>
                    </div>
                </div>
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
        <a href="{{ route('home') }}">Inicio</a>
        <a href="{{ route('properties.index') }}">Propiedades</a>
        <a href="{{ route('scroll') }}">IberScroll</a>
        
        @auth
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
                <div style="font-size: 11px; color: var(--gray-mid); margin-bottom: 15px; letter-spacing: 0.1em; font-weight: 600;">MI CUENTA</div>
                <a href="{{ route('user.properties.create') }}" class="nav-link" style="font-size: 17px; margin-bottom: 12px; display: block; color: #0071e3;">Crear publicación</a>
                <a href="{{ route('user.properties.index') }}" class="nav-link" style="font-size: 17px; margin-bottom: 12px; display: block;">Mis Publicaciones</a>
                <a href="{{ route('saved') }}" class="nav-link" style="font-size: 17px; margin-bottom: 12px; display: block;">Mis Guardados</a>
                @if(Auth::user()->hasAdminAccess())
                    <a href="{{ route('admin.dashboard') }}" class="nav-link" style="font-size: 17px; margin-bottom: 12px; display: block;">Panel de Control</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" id="logout-form-mobile">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();" style="color: var(--danger); font-size: 17px; display: block;">Cerrar sesión</a>
                </form>
            </div>
        @else
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
                <a href="{{ route('login') }}" class="nav-link" style="font-size: 17px; margin-bottom: 12px; display: block;">Entrar</a>
                <a href="{{ route('register') }}" class="nav-link" style="font-size: 17px; display: block;">Registro</a>
            </div>
        @endauth
    </nav>


</div>

<main>
    @yield('content')
</main>

<footer class="site-footer" id="sobre-nosotros">
    <div class="footer-inner">
        <div class="footer-col">
            <div class="footer-logo">
                <svg width="24" height="24" viewBox="0 0 28 28" fill="none" aria-hidden="true">
                    <path d="M14 3L2 12h3v13h7v-8h4v8h7V12h3L14 3z" fill="white"/>
                </svg>
                IberPiso
            </div>
            <p class="footer-tagline">Tu portal inmobiliario de confianza en España.</p>
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
    @vite('resources/js/app.js')
@stack('scripts')
</body>
</html>
