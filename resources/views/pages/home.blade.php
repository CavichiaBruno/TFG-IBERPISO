@extends('layouts.app')

@section('title', 'IberPiso — Encuentra tu hogar ideal')
@push('styles')
    @vite('resources/css/pages/home.css')
@endpush

@section('content')

    <section class="hero-parallax" id="hero-parallax">
        <canvas id="hero-canvas"></canvas>

        <div class="hero-parallax-content container">
            <div class="hero-text-wrap fade-in">
                <h1 class="hero-headline">Encuentra tu lugar<br><span class="marker-white dynamic-text" id="dynamic-word">ideal</span>.</h1>
                <p class="hero-description">Casas excepcionales para personas extraordinarias.</p>

                <div class="hero-search-container">
                    <div class="search-tabs">
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

                <div class="hero-trust fade-in" style="animation-delay: 0.2s;">
                    <div class="trust-chip">
                        <svg viewBox="0 0 24 24" width="13" height="13"><path d="M22 11.08V12a10 10 0 11-5.93-9.14" stroke="currentColor" fill="none" stroke-width="2.2" /><polyline points="22 4 12 14.01 9 11.01" stroke="currentColor" fill="none" stroke-width="2.2" /></svg>
                        <span>Propiedades verificadas</span>
                    </div>
                    <div class="trust-chip">
                        <svg viewBox="0 0 24 24" width="13" height="13"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke="currentColor" fill="none" stroke-width="2.2" /><circle cx="9" cy="7" r="4" stroke="currentColor" fill="none" stroke-width="2.2" /></svg>
                        <span>Agentes certificados</span>
                    </div>
                    <div class="trust-chip">
                        <svg viewBox="0 0 24 24" width="13" height="13"><rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="currentColor" fill="none" stroke-width="2.2" /><path d="M7 11V7a5 5 0 0110 0v4" stroke="currentColor" fill="none" stroke-width="2.2" /></svg>
                        <span>Sin comisiones ocultas</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── FEATURED SECTION ── --}}
    <section class="section-pad bg-light">
        <div class="container-wide">
            <h2 class="h-display text-center" style="margin-bottom: var(--sp-10);">Viviendas <span class="marker-white">destacadas.</span></h2>
            @if($featured->count())
                <div class="property-grid">
                    @foreach($featured as $property)
                        <x-property-card :property="$property" />
                    @endforeach
                </div>
            @else
                <div class="text-center">
                    <p class="text-muted">No hay propiedades destacadas ahora mismo.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- ── HOW IT WORKS ── --}}
    <section class="section-pad section-white">
        <div class="container">
            <h2 class="h-display text-center" style="margin-bottom: 12px;">Tan simple como <span class="marker-white">tres pasos.</span></h2>
            <p class="lp-lead text-center">Encontrar tu hogar ideal nunca había sido tan rápido.</p>
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number">01</div>
                    <h3 class="step-title">Busca</h3>
                    <p class="step-desc">Filtra por ciudad, tipo de inmueble, precio y más. Nuestra base de datos se actualiza en tiempo real.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">02</div>
                    <h3 class="step-title">Descubre</h3>
                    <p class="step-desc">Usa IberScroll para deslizar propiedades como nunca antes. Guarda las que te interesan con un gesto.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">03</div>
                    <h3 class="step-title">Conecta</h3>
                    <p class="step-desc">Contacta directamente con agentes certificados. Sin intermediarios, sin comisiones ocultas.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── CITIES ── --}}
    <section class="section-pad bg-light">
        <div class="container">
            <h2 class="h-display text-center" style="margin-bottom: 12px;">Las <span class="marker-white">mejores ciudades.</span></h2>
            <p class="lp-lead text-center">Explora propiedades en las principales ciudades de España.</p>
            <div class="cities-grid">
                <a href="{{ route('properties.index', ['q' => 'Madrid']) }}" class="city-card">
                    <div class="city-img" style="background-image:url('https://images.unsplash.com/photo-1558370781-d6196949e317?auto=format&fit=crop&w=800&q=80')"></div>
                    <div class="city-label">
                        <span class="city-name">Madrid</span>
                        <span class="city-arrow">→</span>
                    </div>
                </a>
                <a href="{{ route('properties.index', ['q' => 'Barcelona']) }}" class="city-card">
                    <div class="city-img" style="background-image:url('https://images.unsplash.com/photo-1539037116277-4db20889f2d4?auto=format&fit=crop&w=800&q=80')"></div>
                    <div class="city-label">
                        <span class="city-name">Barcelona</span>
                        <span class="city-arrow">→</span>
                    </div>
                </a>
                <a href="{{ route('properties.index', ['q' => 'Valencia']) }}" class="city-card">
                    <div class="city-img" style="background-image:url('https://images.unsplash.com/photo-1564571011564-9d0da61b89e6?auto=format&fit=crop&w=800&q=80')"></div>
                    <div class="city-label">
                        <span class="city-name">Valencia</span>
                        <span class="city-arrow">→</span>
                    </div>
                </a>
                <a href="{{ route('properties.index', ['q' => 'Sevilla']) }}" class="city-card">
                    <div class="city-img" style="background-image:url('https://images.unsplash.com/photo-1555952517-2e8e729e0b44?auto=format&fit=crop&w=800&q=80')"></div>
                    <div class="city-label">
                        <span class="city-name">Sevilla</span>
                        <span class="city-arrow">→</span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- ── IBERSCROLL CTA BANNER ── --}}
    <section class="section-pad section-white" style="border-top: 1px solid var(--gray-subtle);">
        <div class="container text-center">
            <p class="lp-eyebrow-light">Una forma nueva de explorar</p>
            <h2 class="h-display" style="margin-bottom: 20px;">Conoce <span class="marker-white">IberScroll.</span></h2>
            <p class="lp-desc">Desliza propiedades como si fuera una app. Guarda las que te gustan, descarta el resto. Es inmobiliario, pero mejor.</p>
            <div class="lp-cta-pair">
                <a href="{{ route('scroll') }}" class="btn btn-primary btn-lg">Probar IberScroll</a>
                <a href="{{ route('properties.index') }}" class="lp-link-light">Ver todas las propiedades →</a>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    @vite(['resources/js/pages/hero-canvas.js', 'resources/js/pages/home.js'])
@endpush