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
                <h1 class="hero-headline">Encuentra tu lugar ideal.</h1>
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
            <h2 class="h-display text-center" style="margin-bottom: var(--sp-10);">Viviendas destacadas.</h2>
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

@endsection

@push('scripts')
    @vite(['resources/js/pages/hero-canvas.js', 'resources/js/pages/home.js'])
@endpush