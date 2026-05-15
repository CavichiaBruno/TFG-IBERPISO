@extends('layouts.app')

@section('title', 'IberPiso — Encuentra tu hogar ideal')
@push('styles')
    @vite('resources/css/pages/home.css')
@endpush

@section('content')
    <div class="home-wrapper">

        {{-- ── SECCIÓN HERO ── --}}
        <div class="hero-outer">
            <section class="hero-parallax" id="hero-parallax">
                <canvas id="hero-canvas"></canvas>

                <div class="hero-parallax-content container">
                    <div class="hero-text-wrap fade-in">
                        <h1 class="hero-headline" style="color: white;">Encuentra tu lugar<br><span class="marker-hero dynamic-text" id="dynamic-word">ideal</span>.</h1>
                        <p class="hero-description" style="color: rgba(255,255,255,0.8);">Casas excepcionales para personas extraordinarias.</p>

                        <div class="hero-search-container">
                            <div class="search-tabs search-tabs-hero">
                                <button type="button" class="search-tab active" data-op="venta">Comprar</button>
                                <button type="button" class="search-tab" data-op="alquiler">Alquilar</button>
                            </div>
                            <form class="apple-search-card" action="{{ route('properties.index') }}" method="GET" id="hero-search-form">
                                <input type="hidden" name="operacion" id="search-operacion" value="venta">
                                <div class="apple-search-fields">
                                    <div class="apple-search-field field-main">
                                        <svg viewBox="0 0 24 24" width="18" height="18"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" stroke="currentColor" fill="none" stroke-width="2" /></svg>
                                        <input type="text" name="q" placeholder="Introducir ciudad" autocomplete="off">
                                    </div>
                                    <div class="apple-search-field field-select">
                                        <select name="tipo">
                                            <option value="">Tipo de propiedad</option>
                                            <option value="piso">Piso</option>
                                            <option value="casa">Casa</option>
                                            <option value="chalet">Chalet</option>
                                            <option value="local">Local</option>
                                            <option value="garaje">Garaje</option>
                                            <option value="oficina">Oficina</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="apple-search-submit">
                                        <svg viewBox="0 0 24 24" width="18" height="18"><circle cx="11" cy="11" r="8" stroke="currentColor" fill="none" stroke-width="2" /><line x1="21" y1="21" x2="16.65" y2="16.65" stroke="currentColor" stroke-width="2" /></svg>
                                        <span>Buscar</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- ── SECCIÓN DESTACADOS ── --}}
        <section class="section-pad bg-light home-section">
            <div class="container-wide">
                <h2 class="h-display text-center" style="margin-bottom: var(--sp-10);">Viviendas <span class="marker-white">destacadas.</span></h2>
                <div class="featured-carousel-wrapper">
                    <button class="carousel-nav prev" id="featured-prev" aria-label="Anterior">
                        <svg viewBox="0 0 24 24" width="24" height="24"><polyline points="15 18 9 12 15 6" stroke="currentColor" fill="none" stroke-width="2.5"/></svg>
                    </button>
                    
                    <div class="property-grid carousel-track" id="featured-carousel" data-autoload="true">
                        {{-- Skeletons initial load --}}
                        <x-property-card-skeleton />
                        <x-property-card-skeleton />
                        <x-property-card-skeleton />
                    </div>

                    <button class="carousel-nav next" id="featured-next" aria-label="Siguiente">
                        <svg viewBox="0 0 24 24" width="24" height="24"><polyline points="9 18 15 12 9 6" stroke="currentColor" fill="none" stroke-width="2.5"/></svg>
                    </button>
                </div>
            </div>
        </section>

        {{-- ── CÓMO FUNCIONA ── --}}
        <section class="section-pad section-white home-section">
            <div class="container-wide">
                <h2 class="h-display text-center" style="margin-bottom: 12px;">Tan simple como <span class="marker-white">tres pasos.</span></h2>
                <p class="lp-lead text-center">Encontrar tu hogar ideal nunca había sido tan rápido.</p>
                <div class="steps-interactive-layout">
                    <div class="step-column">
                        <div class="step-card-clean">
                            <div class="step-number">01</div>
                            <h3 class="step-title">Busca</h3>
                            <p class="step-desc">Filtra por ciudad, tipo de inmueble, precio y más. Nuestra base de datos se actualiza en tiempo real.</p>
                        </div>
                        <div class="step-card-clean">
                            <div class="step-number">02</div>
                            <h3 class="step-title">Descubre</h3>
                            <p class="step-desc">Usa IberScroll para deslizar propiedades como nunca antes. Guarda las que te interesan con un gesto.</p>
                        </div>
                        <div class="step-card-clean">
                            <div class="step-number">03</div>
                            <h3 class="step-title">Conecta</h3>
                            <p class="step-desc">Contacta directamente con agentes certificados. Sin intermediarios, sin comisiones ocultas.</p>
                        </div>
                    </div>
                    <div class="step-right-lottie">
                        <dotlottie-wc src="https://lottie.host/74905008-4c4e-477f-bc47-7b89f8b2bcad/etnH39wO6H.lottie" class="lottie-responsive" autoplay loop worker="true"></dotlottie-wc>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── SECCIÓN DE ANIMACIÓN DE BÚSQUEDA ── --}}
        <section class="section-pad bg-light organic-bg-section home-section" style="position: relative; overflow: hidden; padding-bottom: var(--sp-4) !important;">
            <div class="container-wide">
                <div class="lp-two-col">
                    <div class="lp-two-col-text">
                        <h2 class="h-display" style="margin-bottom: 20px;">Búsqueda <span class="marker-white">inteligente.</span></h2>
                        <p class="lp-desc">Nuestro algoritmo de búsqueda avanzada encuentra exactamente lo que necesitas en segundos.</p>
                        <a href="{{ route('properties.index') }}" class="btn btn-primary" style="margin-top: var(--sp-6);">Empezar búsqueda</a>
                    </div>
                    <div class="lp-two-col-lottie">
                        <dotlottie-wc src="https://lottie.host/7e49e451-6832-490a-a195-25e96e9b008f/5FLC5EFmeu.lottie" class="lottie-responsive" autoplay loop worker="true"></dotlottie-wc>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── CIUDADES ── --}}
        <section class="section-pad section-white home-section" style="padding-top: var(--sp-4) !important;">
            <div class="container-wide">
                <div class="lp-two-col lp-two-col-reverse">
                    <div class="lp-two-col-lottie">
                        <dotlottie-wc src="https://lottie.host/bf7e2125-b625-42fc-93bf-4bb80e7b76b7/hqOAHleMrn.lottie" class="lottie-responsive" autoplay loop worker="true"></dotlottie-wc>
                    </div>
                    <div class="lp-two-col-text">
                        <h2 class="h-display" style="margin-bottom: 20px;">Las <span class="marker-white">mejores ciudades.</span></h2>
                        <p class="lp-desc">Desde el corazón de Madrid hasta las costas de Valencia. Conectamos personas con hogares en toda la geografía española.</p>
                        <a href="{{ route('properties.index') }}" class="btn btn-primary btn-lg" style="margin-top: var(--sp-6);">Explorar todas las ciudades</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── BANNER CTA IBERSCROLL ── --}}
        <section class="section-pad bg-light home-section">
            <div class="container">
                <div class="lp-two-col">
                    <div class="lp-two-col-text">
                        <p class="lp-eyebrow-light">Una forma nueva de explorar</p>
                        <h2 class="h-display" style="margin-bottom: 20px;">Conoce <span class="marker-white">IberScroll.</span></h2>
                        <p class="lp-desc">Desliza propiedades como si fuera una app. Guarda las que te gustan, descarta el resto.</p>
                        <div class="lp-cta-pair" style="margin-top: var(--sp-8);">
                            <a href="{{ route('scroll') }}" class="btn btn-primary btn-lg">Probar IberScroll</a>
                            <a href="{{ route('properties.index') }}" class="lp-link-light">Ver todas las propiedades →</a>
                        </div>
                    </div>
                    <div class="lp-two-col-lottie lp-two-col-lottie-sm">
                        <dotlottie-wc src="https://lottie.host/8c8639b7-eb02-4ca6-92ec-01ea7433a446/0FdRTMPLse.lottie" class="lottie-responsive" autoplay loop worker="true"></dotlottie-wc>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/hero-canvas.js', 'resources/js/pages/home.js'])
@endpush