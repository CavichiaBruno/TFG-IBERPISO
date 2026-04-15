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
                            <input type="text" name="q" placeholder="Ciudad, provincia o código postal…" autocomplete="off">
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

        {{-- RIGHT: testimonials carousel --}}
        <div class="hero-right">
            <div class="hero-testimonials" id="hero-testimonials">
                <p class="t-label">Lo que dicen nuestros clientes</p>
                <div class="t-track">

                    <div class="t-card active">
                        <div class="t-avatar" style="background:#e8f0fb">
                            <svg viewBox="0 0 40 40" width="36" height="36" fill="none">
                                <circle cx="20" cy="15" r="7" fill="#004AAD" opacity=".85"/>
                                <path d="M6 36c0-7.732 6.268-14 14-14s14 6.268 14 14" fill="#004AAD" opacity=".55"/>
                            </svg>
                        </div>
                        <div class="t-body">
                            <div class="t-stars">★★★★★</div>
                            <p class="t-quote">"Encontré mi piso ideal en menos de una semana. ¡Atención inmejorable!"</p>
                            <span class="t-name">María G. · Madrid</span>
                        </div>
                    </div>

                    <div class="t-card">
                        <div class="t-avatar" style="background:#e6f4ed">
                            <svg viewBox="0 0 40 40" width="36" height="36" fill="none">
                                <circle cx="20" cy="15" r="7" fill="#00875a" opacity=".85"/>
                                <path d="M6 36c0-7.732 6.268-14 14-14s14 6.268 14 14" fill="#00875a" opacity=".55"/>
                            </svg>
                        </div>
                        <div class="t-body">
                            <div class="t-stars">★★★★★</div>
                            <p class="t-quote">"Todo el proceso fue transparente y sin sorpresas. Muy recomendable."</p>
                            <span class="t-name">Carlos R. · Barcelona</span>
                        </div>
                    </div>

                    <div class="t-card">
                        <div class="t-avatar" style="background:#fff3e8">
                            <svg viewBox="0 0 40 40" width="36" height="36" fill="none">
                                <circle cx="20" cy="15" r="7" fill="#d4620a" opacity=".85"/>
                                <path d="M6 36c0-7.732 6.268-14 14-14s14 6.268 14 14" fill="#d4620a" opacity=".55"/>
                            </svg>
                        </div>
                        <div class="t-body">
                            <div class="t-stars">★★★★★</div>
                            <p class="t-quote">"Agentes super profesionales. Mi casa nueva superó todas las expectativas."</p>
                            <span class="t-name">Laura M. · Valencia</span>
                        </div>
                    </div>

                    <div class="t-card">
                        <div class="t-avatar" style="background:#f0eaf9">
                            <svg viewBox="0 0 40 40" width="36" height="36" fill="none">
                                <circle cx="20" cy="15" r="7" fill="#6b3fb5" opacity=".85"/>
                                <path d="M6 36c0-7.732 6.268-14 14-14s14 6.268 14 14" fill="#6b3fb5" opacity=".55"/>
                            </svg>
                        </div>
                        <div class="t-body">
                            <div class="t-stars">★★★★★</div>
                            <p class="t-quote">"Plataforma muy intuitiva y equipo siempre disponible. ¡10 de 10!"</p>
                            <span class="t-name">Javier S. · Sevilla</span>
                        </div>
                    </div>

                </div>
                <div class="t-dots" id="t-dots">
                    <button class="t-dot active" aria-label="Testimonio 1"></button>
                    <button class="t-dot" aria-label="Testimonio 2"></button>
                    <button class="t-dot" aria-label="Testimonio 3"></button>
                    <button class="t-dot" aria-label="Testimonio 4"></button>
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
<script>
(function () {
  var cards   = document.querySelectorAll('.t-card');
  var dots    = document.querySelectorAll('.t-dot');
  var current = 0;
  if (!cards.length) return;

  function go(n) {
    cards[current].classList.remove('active');
    dots[current].classList.remove('active');
    current = (n + cards.length) % cards.length;
    cards[current].classList.add('active');
    dots[current].classList.add('active');
  }

  dots.forEach(function (dot, i) {
    dot.addEventListener('click', function () { go(i); });
  });

  setInterval(function () { go(current + 1); }, 4000);
}());
</script>
@endpush
