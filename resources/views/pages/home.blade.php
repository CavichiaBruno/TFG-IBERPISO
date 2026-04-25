@extends('layouts.app')

@section('title', 'IberPiso — Encuentra tu hogar ideal')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/home.css') }}">
@endpush

@section('content')
{{-- HERO --}}
<section class="hero">
    <canvas id="hero-canvas" aria-hidden="true"></canvas>
    <div class="hero-overlay"></div>
    <div class="hero-inner container">

        {{-- LEFT: copy + search --}}
        <div class="hero-left">
            <h1 class="hero-title">Encuentra tu<br><span class="hero-title-accent">hogar ideal</span></h1>
            <p class="hero-subtitle">Más de <span id="counter-props">{{ $stats['properties'] }}</span> propiedades verificadas en toda España</p>

            <div class="search-bar-card">
                <div class="search-tabs">
                    <button type="button" class="search-tab active" data-op="venta">Comprar</button>
                    <button type="button" class="search-tab" data-op="alquiler">Alquilar</button>
                </div>
                <form class="search-form" action="{{ route('properties.index') }}" method="GET" id="hero-search-form">
                    <input type="hidden" name="operacion" id="search-operacion" value="venta">
                    <div class="search-fields">
                        <div class="search-field search-field-lg">
                            <svg viewBox="0 0 24 24" width="18" height="18"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                            <input type="text" name="q" placeholder="Introducir ciudad" autocomplete="off">
                        </div>
                        <div class="search-field">
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
                        <button type="submit" class="btn search-btn">
                            <svg viewBox="0 0 24 24" width="18" height="18"><circle cx="11" cy="11" r="8" stroke="currentColor" fill="none" stroke-width="2"/><line x1="21" y1="21" x2="16.65" y2="16.65" stroke="currentColor" stroke-width="2"/></svg>
                            Buscar
                        </button>
                    </div>
                </form>
            </div>

            <div class="hero-trust">
                <div class="trust-chip">
                    <svg viewBox="0 0 24 24" width="13" height="13"><path d="M22 11.08V12a10 10 0 11-5.93-9.14" stroke="currentColor" fill="none" stroke-width="2.2"/><polyline points="22 4 12 14.01 9 11.01" stroke="currentColor" fill="none" stroke-width="2.2"/></svg>
                    <span>Propiedades verificadas</span>
                </div>
                <div class="trust-chip">
                    <svg viewBox="0 0 24 24" width="13" height="13"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke="currentColor" fill="none" stroke-width="2.2"/><circle cx="9" cy="7" r="4" stroke="currentColor" fill="none" stroke-width="2.2"/></svg>
                    <span>Agentes certificados</span>
                </div>
                <div class="trust-chip">
                    <svg viewBox="0 0 24 24" width="13" height="13"><rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="currentColor" fill="none" stroke-width="2.2"/><path d="M7 11V7a5 5 0 0110 0v4" stroke="currentColor" fill="none" stroke-width="2.2"/></svg>
                    <span>Sin comisiones ocultas</span>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- FEATURED PROPERTIES --}}
<section class="featured-section section-pad">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Propiedades Destacadas</h2>
            <a href="{{ route('properties.index') }}" class="btn btn-outline">Ver todas</a>
        </div>
        @if($featured->count())
            <div class="properties-grid">
                @foreach($featured as $property)
                    <x-property-card :property="$property" />
                @endforeach
            </div>
        @else
            <p class="text-muted text-center">No hay propiedades destacadas disponibles.</p>
        @endif
        <div class="text-center" style="margin-top:2rem">
            <a href="{{ route('properties.index') }}" class="btn btn-primary btn-lg">Ver todas las propiedades</a>
        </div>
    </div>
</section>

{{-- PROPERTY TYPES --}}
<section class="property-types-section section-pad bg-surface">
    <div class="container">
        <h2 class="section-title text-center">Busca por tipo de propiedad</h2>
        <div class="property-types-grid">
            @foreach([
                ['tipo'=>'piso',    'label'=>'Pisos',      'icon'=>'M3 9l9-7 9 7v11H3V9z'],
                ['tipo'=>'casa',    'label'=>'Casas',      'icon'=>'M3 9l9-7 9 7v11H3V9z'],
                ['tipo'=>'chalet',  'label'=>'Chalets',    'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6'],
                ['tipo'=>'local',   'label'=>'Locales',    'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2M5 21H3'],
                ['tipo'=>'garaje',  'label'=>'Garajes',    'icon'=>'M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v3m-6 13H5a2 2 0 01-2-2v-5'],
                ['tipo'=>'oficina', 'label'=>'Oficinas',   'icon'=>'M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z M16 3H8a2 2 0 00-2 2v2h12V5a2 2 0 00-2-2z'],
            ] as $pt)
                <a href="{{ route('properties.index', ['tipo' => $pt['tipo']]) }}" class="property-type-card">
                    <div class="pt-icon">
                        <svg viewBox="0 0 24 24" width="32" height="32"><path d="{{ $pt['icon'] }}" stroke="currentColor" fill="none" stroke-width="1.5"/></svg>
                    </div>
                    <span>{{ $pt['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/hero-canvas.js') }}"></script>
<script src="{{ asset('js/home.js') }}"></script>
@endpush
