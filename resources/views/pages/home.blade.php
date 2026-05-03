@extends('layouts.app')

@section('title', 'IberPiso — Encuentra tu hogar ideal')
@push('styles')
    @vite('resources/css/pages/home.css')
@endpush

@section('content')
    {{-- Global Wrapper for sections --}}
    <div style="background: white; padding: var(--sp-12); display: flex; flex-direction: column; gap: 0;"> 
        
        {{-- ── HERO SECTION ── --}}
        {{-- Wider hero (more pegged to sides) and darker background --}}
        <div style="margin-bottom: var(--sp-4); margin-left: calc(-1 * var(--sp-8)); margin-right: calc(-1 * var(--sp-8));"> 
            <section class="hero-parallax" id="hero-parallax" style="border-radius: 28px; overflow: hidden; max-width: 100%; margin: 0 auto; width: 100%; background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1550684848-86a5d8727436?auto=format&fit=crop&w=1600&q=40'); background-size: cover; background-position: center; background-attachment: fixed; color: white;">
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
                            <form class="apple-search-card" action="{{ route('properties.index') }}" method="GET" id="hero-search-form" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2);">
                                <input type="hidden" name="operacion" id="search-operacion" value="venta">
                                <div class="apple-search-fields">
                                    <div class="apple-search-field field-main" style="background: rgba(255,255,255,0.9);">
                                        <svg viewBox="0 0 24 24" width="18" height="18"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" stroke="currentColor" fill="none" stroke-width="2" /></svg>
                                        <input type="text" name="q" placeholder="Introducir ciudad" autocomplete="off" style="color: black;">
                                    </div>
                                    <div class="apple-search-field field-select" style="background: rgba(255,255,255,0.9);">
                                        <select name="tipo" style="color: black;">
                                            <option value="">Tipo de propiedad</option>
                                            <option value="piso">Piso</option>
                                            <option value="casa">Casa</option>
                                            <option value="chalet">Chalet</option>
                                            <option value="local">Local</option>
                                            <option value="garaje">Garaje</option>
                                            <option value="oficina">Oficina</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="apple-search-submit" style="background: white; color: black;">
                                        <svg viewBox="0 0 24 24" width="18" height="18"><circle cx="11" cy="11" r="8" stroke="currentColor" fill="none" stroke-width="2" /><line x1="21" y1="21" x2="16.65" y2="16.65" stroke="currentColor" stroke-width="2" /></svg>
                                        <span>Buscar</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="hero-trust fade-in" style="animation-delay: 0.2s;">
                            <div class="trust-chip" style="background: rgba(255,255,255,0.1); color: white;">
                                <svg viewBox="0 0 24 24" width="13" height="13"><path d="M22 11.08V12a10 10 0 11-5.93-9.14" stroke="currentColor" fill="none" stroke-width="2.2" /><polyline points="22 4 12 14.01 9 11.01" stroke="currentColor" fill="none" stroke-width="2.2" /></svg>
                                <span>Propiedades verificadas</span>
                            </div>
                            <div class="trust-chip" style="background: rgba(255,255,255,0.1); color: white;">
                                <svg viewBox="0 0 24 24" width="13" height="13"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke="currentColor" fill="none" stroke-width="2.2" /><circle cx="9" cy="7" r="4" stroke="currentColor" fill="none" stroke-width="2.2" /></svg>
                                <span>Agentes certificados</span>
                            </div>
                            <div class="trust-chip" style="background: rgba(255,255,255,0.1); color: white;">
                                <svg viewBox="0 0 24 24" width="13" height="13"><rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="currentColor" fill="none" stroke-width="2.2" /><path d="M7 11V7a5 5 0 0110 0v4" stroke="currentColor" fill="none" stroke-width="2.2" /></svg>
                                <span>Sin comisiones ocultas</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- ── FEATURED SECTION ── --}}
        <section class="section-pad bg-light" style="border-radius: 28px; background: #f5f5f7; max-width: 1400px; margin: 0 auto var(--sp-12); width: 100%; padding-top: var(--sp-12); padding-bottom: var(--sp-12);">
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
        <section class="section-pad section-white" style="border-radius: 28px; background: white; max-width: 1400px; margin: 0 auto var(--sp-12); width: 100%;">
            <div class="container">
                <h2 class="h-display text-center" style="margin-bottom: 12px;">Tan simple como <span class="marker-white">tres pasos.</span></h2>
                <p class="lp-lead text-center">Encontrar tu hogar ideal nunca había sido tan rápido.</p>
                <div class="steps-interactive-layout" style="margin-top: var(--sp-12); display: flex; align-items: center; justify-content: center; gap: var(--sp-15); flex-wrap: wrap;">
                    {{-- Left Side: All 3 Steps in 1 Column --}}
                    <div class="step-column" style="flex: 1; min-width: 320px; display: flex; flex-direction: column; gap: var(--sp-6);">
                        <div class="step-card-clean" style="background: white; padding: var(--sp-7); border-radius: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.05); flex: 1;">
                            <div class="step-number" style="font-size: 13px; color: var(--blue); font-weight: 700; margin-bottom: 6px;">01</div>
                            <h3 class="step-title" style="font-size: 21px; margin-bottom: 8px; font-weight: 600;">Busca</h3>
                            <p class="step-desc" style="font-size: 14px; color: var(--gray-mid); line-height: 1.5;">Filtra por ciudad, tipo de inmueble, precio y más. Nuestra base de datos se actualiza en tiempo real.</p>
                        </div>
                        <div class="step-card-clean" style="background: white; padding: var(--sp-7); border-radius: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.05); flex: 1;">
                            <div class="step-number" style="font-size: 13px; color: var(--blue); font-weight: 700; margin-bottom: 6px;">02</div>
                            <h3 class="step-title" style="font-size: 21px; margin-bottom: 8px; font-weight: 600;">Descubre</h3>
                            <p class="step-desc" style="font-size: 14px; color: var(--gray-mid); line-height: 1.5;">Usa IberScroll para deslizar propiedades como nunca antes. Guarda las que te interesan con un gesto.</p>
                        </div>
                        <div class="step-card-clean" style="background: white; padding: var(--sp-7); border-radius: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.05); flex: 1;">
                            <div class="step-number" style="font-size: 13px; color: var(--blue); font-weight: 700; margin-bottom: 6px;">03</div>
                            <h3 class="step-title" style="font-size: 21px; margin-bottom: 8px; font-weight: 600;">Conecta</h3>
                            <p class="step-desc" style="font-size: 14px; color: var(--gray-mid); line-height: 1.5;">Contacta directamente con agentes certificados. Sin intermediarios, sin comisiones ocultas.</p>
                        </div>
                    </div>

                    {{-- Right Side: MacBook Lottie --}}
                    <div class="step-right-lottie" style="flex: 1.4; display: flex; justify-content: center; min-width: 400px;">
                        <dotlottie-wc src="https://lottie.host/74905008-4c4e-477f-bc47-7b89f8b2bcad/etnH39wO6H.lottie" style="width: 650px; height: 650px; transform: scale(1.2);" autoplay loop worker="true"></dotlottie-wc>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── SEARCH ANIMATION SECTION ── --}}
        <section class="section-pad bg-light organic-bg-section" style="border-radius: 28px; max-width: 1400px; margin: 0 auto 0 !important; width: 100%; position: relative; overflow: hidden; padding-bottom: var(--sp-4) !important;">
            <div class="container-wide">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: var(--sp-12); flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 300px;">
                        <h2 class="h-display" style="margin-bottom: 20px;">Búsqueda <span class="marker-white">inteligente.</span></h2>
                        <p class="lp-desc">Nuestro algoritmo de búsqueda avanzada encuentra exactamente lo que necesitas en segundos. Olvida las búsquedas interminables y encuentra tu hogar ideal de forma eficiente.</p>
                        <a href="{{ route('properties.index') }}" class="btn btn-primary" style="margin-top: var(--sp-6);">Empezar búsqueda</a>
                    </div>
                    <div style="flex: 1.2; display: flex; justify-content: center; min-width: 300px; overflow: visible;">
                        <dotlottie-wc src="https://lottie.host/7e49e451-6832-490a-a195-25e96e9b008f/5FLC5EFmeu.lottie" style="width: 600px; height: 600px; transform: scale(1.2);" autoplay loop worker="true"></dotlottie-wc>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── CITIES ── --}}
        <section class="section-pad section-white" style="padding-top: var(--sp-4) !important; border-radius: 28px; background: white; max-width: 1400px; margin: 0 auto; width: 100%;">
            <div class="container-wide">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 120px; flex-wrap: wrap;">
                    <div style="flex: 1.6; display: flex; justify-content: center; min-width: 300px; background: white; overflow: hidden;"> {{-- Prevent overflow --}}
                        <dotlottie-wc src="https://lottie.host/bf7e2125-b625-42fc-93bf-4bb80e7b76b7/hqOAHleMrn.lottie" style="width: 100%; max-width: 900px; height: auto; aspect-ratio: 1;" autoplay loop worker="true"></dotlottie-wc>
                    </div>
                    <div style="flex: 1; min-width: 300px;">
                        <h2 class="h-display" style="margin-bottom: 20px;">Las <span class="marker-white">mejores ciudades.</span></h2>
                        <p class="lp-desc">Desde el corazón de Madrid hasta las costas de Valencia. Conectamos personas con hogares en toda la geografía española. Encuentra tu comunidad y empieza una nueva etapa.</p>
                        <a href="{{ route('properties.index') }}" class="btn btn-primary btn-lg" style="margin-top: var(--sp-6);">Explorar todas las ciudades</a> {{-- Added CTA --}}
                    </div>
                </div>
            </div>
        </section>

        {{-- ── IBERSCROLL CTA BANNER ── --}}
        <section class="section-pad bg-light" style="border-top: none; border-radius: 28px; background: #f5f5f7; max-width: 1400px; margin: 0 auto; width: 100%;">
            <div class="container">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: var(--sp-12); flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 300px;">
                        <p class="lp-eyebrow-light">Una forma nueva de explorar</p>
                        <h2 class="h-display" style="margin-bottom: 20px;">Conoce <span class="marker-white">IberScroll.</span></h2>
                        <p class="lp-desc">Desliza propiedades como si fuera una app. Guarda las que te gustan, descarta el resto. Es inmobiliario, pero mejor.</p>
                        <div class="lp-cta-pair" style="margin-top: var(--sp-8);">
                            <a href="{{ route('scroll') }}" class="btn btn-primary btn-lg">Probar IberScroll</a>
                            <a href="{{ route('properties.index') }}" class="lp-link-light" style="margin-top: 10px; display: inline-block;">Ver todas las propiedades →</a>
                        </div>
                    </div>
                    <div style="flex: 1; display: flex; justify-content: center; min-width: 300px;">
                        <dotlottie-wc src="https://lottie.host/8c8639b7-eb02-4ca6-92ec-01ea7433a446/0FdRTMPLse.lottie" style="width: 400px; height: 400px" autoplay loop worker="true"></dotlottie-wc>
                    </div>
                </div>
            </div>
        </section>
    </div> {{-- End of canals de aire wrapper --}}
@endsection

@push('scripts')
    @vite(['resources/js/pages/hero-canvas.js', 'resources/js/pages/home.js'])
@endpush