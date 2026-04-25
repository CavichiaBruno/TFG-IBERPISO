@extends('layouts.app')
@section('title', $property->title)
@section('meta_description', Str::limit($property->description, 160))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/detail.css') }}">
@endpush

@section('content')
<div class="detail-layout">

    {{-- LEFT COLUMN --}}
    <div class="detail-main">

        {{-- GALLERY --}}
        @php $images = $property->media->where('file_type', 'image'); @endphp
        <div class="gallery-section">
            @if($images->count())
                <div class="gallery-main" id="gallery-main">
                    <img src="{{ $images->first()->url }}" alt="{{ $property->title }}" id="main-photo" loading="eager">
                    <button class="gallery-lightbox-btn" id="open-lightbox">
                        <svg viewBox="0 0 24 24" width="20" height="20"><rect x="3" y="3" width="18" height="18" rx="2" stroke="white" fill="none" stroke-width="2"/><circle cx="8.5" cy="8.5" r="1.5" fill="white"/><polyline points="21 15 16 10 5 21" stroke="white" fill="none" stroke-width="2"/></svg>
                        Ver todas las fotos ({{ $images->count() }})
                    </button>
                </div>
                @if($images->count() > 1)
                <div class="gallery-thumbs">
                    @foreach($images as $idx => $img)
                        <img src="{{ $img->url }}" alt="Foto {{ $idx+1 }}" class="gallery-thumb {{ $idx===0?'active':'' }}" data-full="{{ $img->url }}" loading="lazy">
                    @endforeach
                </div>
                @endif
            @else
                <div class="gallery-placeholder">
                    <svg viewBox="0 0 24 24" width="64" height="64"><rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" fill="none" stroke-width="1"/><circle cx="8.5" cy="8.5" r="1.5" stroke="currentColor" fill="none"/><polyline points="21 15 16 10 5 21" stroke="currentColor" fill="none"/></svg>
                    <p>Sin imágenes disponibles</p>
                </div>
            @endif
        </div>

        {{-- PRICE & TYPE BAR --}}
        <div class="detail-header">
            <div class="detail-price">€{{ $property->formatted_price }}{{ $property->operation_type === 'alquiler' ? '/mes' : '' }}</div>
            <div class="detail-badges">
                <span class="badge badge-operation">{{ strtoupper($property->operation_type) }}</span>
                <span class="badge badge-type" style="background: var(--gray-light); color: var(--near-black);">{{ strtoupper($property->property_type) }}</span>
                @if($property->is_featured)<span class="badge badge-featured">DESTACADO</span>@endif
            </div>
        </div>

        <h1 class="detail-title">{{ $property->title }}</h1>
        <p class="detail-address">
            <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            {{ $property->address }}, {{ $property->city }}, {{ $property->province }}
        </p>

        {{-- KEY FEATURES --}}
        <div class="key-features">
            <div class="key-feature"><span class="kf-value">{{ $property->rooms }}</span><span class="kf-label">Habitaciones</span></div>
            <div class="key-feature"><span class="kf-value">{{ $property->bathrooms }}</span><span class="kf-label">Baños</span></div>
            <div class="key-feature"><span class="kf-value">{{ $property->surface_m2 }} m²</span><span class="kf-label">Superficie</span></div>
            @if($property->floor !== null)<div class="key-feature"><span class="kf-value">{{ $property->floor }}º</span><span class="kf-label">Planta</span></div>@endif
            @if($property->energy_certificate)
                <div class="key-feature">
                    <span class="kf-value energy-cert energy-{{ strtolower($property->energy_certificate) }}">{{ $property->energy_certificate }}</span>
                    <span class="kf-label">Certificado</span>
                </div>
            @endif
        </div>

        {{-- DESCRIPTION --}}
        <div class="detail-section">
            <h2 class="detail-section-title">Descripción</h2>
            <div class="detail-description">{{ $property->description }}</div>
        </div>

        {{-- AMENITIES --}}
        <div class="detail-section">
            <h2 class="detail-section-title">Características</h2>
            <div class="amenities-grid">
                @foreach([
                    ['has_elevator','Ascensor'],['has_parking','Parking'],['has_terrace','Terraza'],
                    ['has_garden','Jardín'],['has_pool','Piscina'],['air_conditioning','Aire Acond.']
                ] as [$key,$label])
                    <div class="amenity-item {{ $property->$key ? 'amenity-yes' : 'amenity-no' }}">
                        <svg viewBox="0 0 24 24" width="18" height="18">
                            @if($property->$key)
                                <polyline points="20 6 9 17 4 12" stroke="currentColor" fill="none" stroke-width="2"/>
                            @else
                                <line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2"/>
                                <line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2"/>
                            @endif
                        </svg>
                        {{ $label }}
                    </div>
                @endforeach
            </div>
        </div>

        {{-- DOCUMENTS --}}
        @php $docs = $property->media->where('file_type', 'pdf'); @endphp
        @if($docs->count())
        <div class="detail-section">
            <h2 class="detail-section-title">Documentos</h2>
            <div class="docs-list">
                @foreach($docs as $doc)
                    <a href="{{ $doc->url }}" target="_blank" class="doc-item" download>
                        <svg viewBox="0 0 24 24" width="20" height="20"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" fill="none" stroke-width="2"/><polyline points="14 2 14 8 20 8" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                        {{ $doc->original_name }}
                        <small>({{ $doc->file_size_kb }} KB)</small>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- MAP --}}
        @if($property->latitude && $property->longitude)
        <div class="detail-section">
            <h2 class="detail-section-title">Ubicación</h2>
            <div class="map-placeholder">
                <p>Lat: {{ $property->latitude }}, Lon: {{ $property->longitude }}</p>
                <p class="text-muted">{{ $property->address }}, {{ $property->city }}</p>
            </div>
        </div>
        @endif

        {{-- RELATED --}}
        @if($related->count())
        <div class="detail-section">
            <h2 class="detail-section-title">Propiedades similares en {{ $property->city }}</h2>
            <div class="properties-grid properties-grid-sm">
                @foreach($related as $rel)
                    <x-property-card :property="$rel" />
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- RIGHT STICKY PANEL --}}
    <aside class="detail-contact">
        <div class="contact-card">
            <h3>¿Interesado en este inmueble?</h3>
            <p class="contact-subtitle">Contáctanos sin compromiso</p>

            <form id="inquiry-form" data-property-id="{{ $property->id }}">
                @csrf
                @guest
                    <div class="form-group">
                        <input type="text" name="guest_name" placeholder="Tu nombre *" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="guest_email" placeholder="Tu email *" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="guest_phone" placeholder="Tu teléfono" class="form-input">
                    </div>
                @endguest
                <div class="form-group">
                    <textarea name="message" placeholder="Me interesa esta propiedad. ¿Podría concertar una visita?" class="form-textarea" rows="4" required minlength="10"></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-block" id="inquiry-submit">Enviar consulta</button>
                <div id="inquiry-response"></div>
            </form>

            <div class="contact-info">
                <p>
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 3.07 9.81 19.79 19.79 0 0 1 1 1.18 2 2 0 0 1 3 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    +34 900 000 000
                </p>
                <p>
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Lun–Vie 9:00–19:00
                </p>
            </div>
        </div>
    </aside>
</div>

{{-- LIGHTBOX --}}
<div class="lightbox" id="lightbox" aria-hidden="true" role="dialog">
    <div class="lightbox-overlay" id="close-lightbox"></div>
    <div class="lightbox-content">
        <button class="lightbox-close" id="lightbox-close-btn" aria-label="Cerrar">✕</button>
        <img src="" alt="" id="lightbox-img">
        <button class="lightbox-prev" id="lb-prev" aria-label="Anterior">‹</button>
        <button class="lightbox-next" id="lb-next" aria-label="Siguiente">›</button>
    </div>
</div>

{{-- MOBILE STICKY CONTACT --}}
<div class="mobile-contact-bar">
    <a href="tel:+34900000000" class="btn btn-primary">Llamar</a>
    <button id="mobile-contact-btn" class="btn btn-outline">Contactar</button>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/detail.js') }}"></script>
@endpush
