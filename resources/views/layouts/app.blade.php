<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IberPiso') — Encuentra tu hogar ideal</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    @vite('resources/css/app.css')
    @stack('styles')
</head>
<body>
{{-- El toolbar superior ha sido movido al menú de accesibilidad en el header --}}

<header class="site-header" id="site-header">
    <div class="header-inner">
        <a href="{{ route('home') }}" class="logo">
            <span class="brand-iber">Iber</span><span class="brand-piso">Piso</span>
        </a>

        <nav class="main-nav">
            <a href="{{ route('home') }}" class="nav-link">Inicio</a>
            <a href="{{ route('properties.index') }}" class="nav-link">Propiedades</a>
            <a href="{{ route('articles.index') }}" class="nav-link">Noticias</a>
            <a href="{{ route('scroll') }}" class="nav-link">IberScroll</a>
        </nav>



        <div class="header-actions">
            {{-- Accessibility Dropdown --}}
            <div class="acc-dropdown">
                <button class="acc-toggle-btn" id="accDropdownBtn" aria-label="Opciones de accesibilidad">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><path d="M9 12h6M12 9v6"/>
                    </svg>
                </button>
                <div class="acc-menu" id="accMenu">
                    <div class="acc-menu-header">Accesibilidad</div>
                    <div class="acc-menu-section">
                        <span class="acc-menu-label">Tamaño de fuente</span>
                        <div class="acc-btn-group">
                            <button onclick="window.accTools.changeSize(-1)">A-</button>
                            <button onclick="window.accTools.resetSize()">A</button>
                            <button onclick="window.accTools.changeSize(1)">A+</button>
                        </div>
                    </div>
                    <div class="acc-menu-divider"></div>
                    <button class="acc-menu-item" onclick="window.accTools.toggleContrast()">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" style="margin-right: 10px;"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-18v16a8 8 0 1 0 0-16z"/></svg>
                        Alto Contraste
                    </button>
                </div>
            </div>

            @auth
                {{-- Mailbox Icon / Notifications --}}
                <a href="{{ route('user.inquiries') }}" class="notification-btn" id="notificationsBtn" aria-label="Mis mensajes" style="position: relative; margin-right: 15px; color: #1d1d1f; display: flex; align-items: center; justify-content: center;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                    </svg>
                    @if(Auth::user()->unread_inquiries_count > 0)
                        <span style="position: absolute; top: -2px; right: -4px; background: #ff3b30; color: white; font-size: 10px; min-width: 16px; height: 16px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700; border: 2px solid white; padding: 0 2px;">
                            {{ Auth::user()->unread_inquiries_count }}
                        </span>
                    @endif
                </a>

                <div class="user-profile-dropdown">
                    <button class="user-profile-btn" id="userDropdownBtn" aria-label="Mi perfil">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                    </button>
                    <div class="dropdown-menu">
                        <div class="dropdown-item" style="font-weight: 600; font-size: 13px; color: var(--gray-mid);">Hola, {{ Auth::user()->nombre }}</div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('user.properties.create') }}" class="dropdown-item" style="color: var(--apple-blue, #0071e3); font-weight: 500;">
                            <svg viewBox="0 0 24 24" width="16" height="16" style="display:inline;vertical-align:middle;margin-right:8px;" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Crear publicación
                        </a>
                        <div class="dropdown-divider"></div>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-item">Panel de Control</a>
                        @endif
                        <a href="{{ route('user.properties.index') }}" class="dropdown-item">Mis Publicaciones</a>
                        <a href="{{ route('user.inquiries') }}" class="dropdown-item">Mis Mensajes</a>
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
        <a href="{{ route('articles.index') }}">Noticias</a>
        <a href="{{ route('scroll') }}">IberScroll</a>
        
        @auth
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
                <div style="font-size: 11px; color: var(--gray-mid); margin-bottom: 15px; letter-spacing: 0.1em; font-weight: 600;">MI CUENTA</div>
                <a href="{{ route('user.properties.create') }}" class="nav-link" style="font-size: 17px; margin-bottom: 12px; display: block; color: #0071e3;">Crear publicación</a>
                <a href="{{ route('user.properties.index') }}" class="nav-link" style="font-size: 17px; margin-bottom: 12px; display: block;">Mis Publicaciones</a>
                <a href="{{ route('user.inquiries') }}" class="nav-link" style="font-size: 17px; margin-bottom: 12px; display: block;">Mis Mensajes</a>
                <a href="{{ route('saved') }}" class="nav-link" style="font-size: 17px; margin-bottom: 12px; display: block;">Mis Guardados</a>
                @if(Auth::user()->isAdmin())
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
            <div class="footer-logo" style="text-transform: uppercase; letter-spacing: 0.05em;">
                <span class="brand-iber" style="font-family: var(--font-display); font-size: 28px; font-weight: 500; color: white;">Iber</span><span class="brand-piso" style="font-family: var(--font-display); font-size: 28px; font-weight: 800; color: #003366;">Piso</span>
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

    // Accessibility Dropdown Logic
    const accBtn = document.getElementById('accDropdownBtn');
    const accMenu = document.getElementById('accMenu');
    
    if (accBtn && accMenu) {
        accBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            accMenu.classList.toggle('open');
        });
        
        document.addEventListener('click', (e) => {
            if (!accMenu.contains(e.target) && !accBtn.contains(e.target)) {
                accMenu.classList.remove('open');
            }
        });
    }
</script>
    {{-- Script Global de Lottie (Diferido para mejorar performance) --}}
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.9.10/dist/dotlottie-wc.js" type="module" defer></script>
    @vite('resources/js/app.js')

    <!-- Back to Top Button -->
    <div id="back-to-top" class="back-to-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" aria-label="Subir al inicio">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg>
    </div>

    <!-- Global Submit Loading Overlay -->
    <div id="submit-loading-overlay" style="opacity: 0; pointer-events: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(10px); z-index: 99999; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: all 0.5s; margin: 0; padding: 0;">
        <div id="spinner-container" style="display: flex; flex-direction: column; align-items: center;">
            <div style="width: 80px; height: 80px; border: 4px solid rgba(255,255,255,0.3); border-top-color: white; border-radius: 50%; animation: spin 1s linear infinite;"></div>
            <h3 style="margin-top: 30px; font-weight: 600; color: white; font-size: 20px;" id="submit-loading-text">Publicando tu propiedad...</h3>
            <p style="font-size: 16px; color: rgba(255,255,255,0.7); margin: 12px 0 0; font-weight: 400;">Por favor espera mientras procesamos tu anuncio</p>
        </div>
        <div id="success-container" style="display: none; text-align: center; animation: slideUp 0.5s ease-out forwards;">
            <svg style="width: 80px; height: 80px; color: #10b981; margin-bottom: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <h3 style="font-weight: 600; color: white; font-size: 20px;">¡Publicación creada!</h3>
            <p style="font-size: 16px; color: rgba(255,255,255,0.7); margin-top: 12px;">Te redirigimos a tu anuncio...</p>
        </div>
    </div>

    <style>
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0; 
                transform: translateY(20px);
            }
            to { 
                opacity: 1; 
                transform: translateY(0);
            }
        }
    </style>

    @include('components.chatbot-popup')
    
    @stack('scripts')
</body>
</html>
