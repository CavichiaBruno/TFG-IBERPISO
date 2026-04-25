@extends('layouts.app')

@section('title', 'IberScroll — Descubre tu próximo hogar')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/scroll.css') }}">
@endpush

@section('content')
<div class="page-scroll-wrapper" style="overflow-x: hidden;">
<div class="container section-pad">
    <div class="section-header text-center">
        <h1 class="section-title"><span class="text-primary">Iber</span>Scroll</h1>
        <div class="scroll-instructions">
            <div class="instruction-item left">
                <span class="key-badge">←</span>
                <span>Pasar</span>
            </div>
            <div class="instruction-separator"></div>
            <div class="instruction-item right">
                <span>Guardar</span>
                <span class="key-badge">→</span>
            </div>
        </div>
    </div>

    <div class="scroll-container">
        @if($properties->count() > 0)
            <div class="swipe-card-stack">
                @foreach($properties as $index => $p)
                    <div class="swipe-card" data-id="{{ $p['id'] }}" style="z-index: {{ 100 - $index }}">
                        <div class="card-img-wrapper">
                            <img src="{{ $p['image'] }}" alt="{{ $p['title'] }}" class="card-img" draggable="false">
                            <div class="card-badge badge-like">LIKE</div>
                            <div class="card-badge badge-dislike">NOPE</div>
                        </div>
                        <div class="card-info">
                            <div class="card-price">{{ $p['price'] }} €</div>
                            <h3 class="card-title">{{ $p['title'] }}</h3>
                            <p class="text-secondary" style="font-size: 0.9rem; margin-bottom: 0.75rem;">
                                <svg viewBox="0 0 24 24" width="14" height="14" style="display:inline;vertical-align:middle;margin-right:4px" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/></svg>
                                {{ $p['location'] }}
                            </p>
                            <div class="card-meta">
                                <span><strong>{{ (int)$p['surface'] }}</strong> m²</span>
                                <span><strong>{{ $p['rooms'] }}</strong> hab.</span>
                                <span><strong>{{ $p['bathrooms'] }}</strong> baños</span>
                            </div>
                            <a href="{{ $p['url'] }}" class="btn btn-outline btn-sm" style="margin-top: 1rem; width: 100%">Ver detalles</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="swipe-actions">
                <button class="swipe-btn btn-dislike" title="No me interesa (Flecha Izquierda)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
                <button class="swipe-btn btn-like" title="Me interesa (Flecha Derecha)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l8.78-8.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                </button>
            </div>
        @else
            <div class="scroll-empty">
                <svg viewBox="0 0 24 24" width="80" height="80" fill="none" stroke="currentColor" stroke-width="1"><path d="M3 9l9-7 9 7v11H3V9z"/></svg>
                <h2>¡Eso es todo por ahora en IberScroll!</h2>
                <p class="text-secondary">No hay más propiedades disponibles para swipear en este momento.</p>
                <a href="{{ route('saved') }}" class="btn btn-primary" style="margin-top: 1.5rem">Ver mis guardados</a>
            </div>
        @endif
    </div>
</div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/pages/scroll.js') }}"></script>
@endpush
