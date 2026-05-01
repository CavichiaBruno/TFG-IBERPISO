@extends('layouts.app')

@section('title', 'IberScroll — Descubre tu próximo hogar')

@push('styles')
    @vite('resources/css/pages/scroll.css')
@endpush

@section('content')
    <div class="iberscroll-page">

        <div class="iberscroll-header">
            <h1 class="iberscroll-title"><span class="marker-white">IberScroll</span></h1>
            <p class="iberscroll-subtitle">Desliza para descubrir tu próximo hogar.</p>
        </div>

        <div class="iberscroll-stage">
            @if($properties->count() > 0)

                <div class="swipe-row">
                    <button class="swipe-btn btn-dislike" id="btn-dislike" title="Pasar (←)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="24" height="24">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                    </button>

                    <div class="swipe-card-stack">
                        @foreach($properties as $index => $p)
                            <div class="swipe-card" data-id="{{ $p['id'] }}" style="z-index: {{ 100 - $index }}">
                                <div class="card-img-wrapper">
                                    <img src="{{ $p['image'] }}" alt="{{ $p['title'] }}" class="card-img" draggable="false"
                                         onerror="this.style.display='none'; this.parentElement.classList.add('img-error')">
                                    <div class="card-badge badge-like">LIKE</div>
                                    <div class="card-badge badge-nope">NOPE</div>
                                </div>
                                <div class="card-info">
                                    <div class="card-price">{{ number_format($p['price'], 0, ',', '.') }} €</div>
                                    <h3 class="card-title">{{ $p['title'] }}</h3>
                                    <p class="card-location">
                                        <svg viewBox="0 0 24 24" width="13" height="13" fill="none"
                                             stroke="currentColor" stroke-width="2" style="flex-shrink:0">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                            <circle cx="12" cy="10" r="3" />
                                        </svg>
                                        {{ $p['location'] }}
                                    </p>
                                    <div class="card-meta">
                                        <div class="meta-item">
                                            <span class="meta-value">{{ (int) $p['surface'] }}</span>
                                            <span class="meta-label">m²</span>
                                        </div>
                                        <div class="meta-sep"></div>
                                        <div class="meta-item">
                                            <span class="meta-value">{{ $p['rooms'] }}</span>
                                            <span class="meta-label">hab.</span>
                                        </div>
                                        <div class="meta-sep"></div>
                                        <div class="meta-item">
                                            <span class="meta-value">{{ $p['bathrooms'] }}</span>
                                            <span class="meta-label">baños</span>
                                        </div>
                                    </div>
                                    <a href="{{ $p['url'] }}" class="card-cta">Ver detalles →</a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button class="swipe-btn btn-like" id="btn-like" title="Guardar (→)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="24" height="24">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l8.78-8.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                        </svg>
                    </button>
                </div>

                <div class="scroll-instructions">
                    <div class="instruction-item">
                        <kbd class="key-badge">←</kbd>
                        <span>Pasar</span>
                    </div>
                    <div class="instruction-item">
                        <span>Guardar</span>
                        <kbd class="key-badge">→</kbd>
                    </div>
                </div>

            @else
                <div class="scroll-empty">
                    <div class="empty-icon">
                        <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.2">
                            <path d="M3 9l9-7 9 7v11H3V9z" />
                        </svg>
                    </div>
                    <h2>¡Todo visto por ahora!</h2>
                    <p>No hay más propiedades disponibles para swipear.</p>
                    <a href="{{ route('saved') }}" class="btn btn-primary">Ver mis guardados</a>
                </div>
            @endif
        </div>

    </div>
@endsection

@push('scripts')
    @vite('resources/js/pages/scroll.js')
@endpush