@extends('layouts.app')

@section('title', 'IberPiso — Encuentra tu hogar ideal')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/home.css') }}">
@endpush

@section('content')

<section class="hero-parallax" id="hero-parallax">

    <div class="hero-parallax-content container">
        <div class="hero-text-wrap">
            <h1 class="hero-headline">Encuentra tu lugar ideal.</h1>
            <p class="hero-description">Casas excepcionales para personas extraordinarias.</p>
            
            <div class="hero-actions">
                <a href="{{ route('properties.index', ['operacion'=>'venta']) }}" class="hero-link">Comprar</a>
                <span class="dot-separator">•</span>
                <a href="{{ route('properties.index', ['operacion'=>'alquiler']) }}" class="hero-link">Alquilar</a>
            </div>
        </div>

        <div class="hero-search-floating">
            <form action="{{ route('properties.index') }}" method="GET" class="apple-search-box">
                <input type="text" name="q" placeholder="Buscar por ciudad, zona o código postal..." class="apple-search-input">
                <button type="submit" class="apple-search-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </button>
            </form>
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
