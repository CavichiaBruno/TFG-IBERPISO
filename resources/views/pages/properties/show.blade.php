@extends('layouts.app')
@section('title', $property->titulo)
@section('meta_description', Str::limit($property->descripcion, 160))

@push('styles')
    @vite('resources/css/pages/detail.css')
@endpush

@section('content')
<div class="detail-layout">

    {{-- LEFT COLUMN --}}
    <div class="detail-main">

        {{-- GALLERY --}}
        @php $images = $property->medios->where('tipo_archivo', 'imagen'); @endphp
        <div class="gallery-section">
            @if($images->count())
                <div class="gallery-main" id="gallery-main">
                    <img src="{{ $images->first()->url }}" alt="{{ $property->titulo }}" id="main-photo" loading="eager">
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
            <div class="detail-price">€{{ $property->formatted_price }}{{ $property->tipo_operacion === 'alquiler' ? '/mes' : '' }}</div>
            <div class="detail-badges">
                <span class="badge badge-operation">{{ strtoupper($property->tipo_operacion) }}</span>
                <span class="badge badge-type" style="background: var(--gray-light); color: var(--near-black);">{{ strtoupper($property->tipo_propiedad) }}</span>
                @if($property->destacada)<span class="badge badge-featured">DESTACADO</span>@endif
            </div>
        </div>

        <h1 class="detail-title">{{ $property->titulo }}</h1>
        <p class="detail-address">
            <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            {{ $property->direccion }}, {{ $property->ciudad }}, {{ $property->provincia }}
        </p>

        {{-- KEY FEATURES --}}
        <div class="key-features">
            <div class="key-feature"><span class="kf-value">{{ $property->habitaciones }}</span><span class="kf-label">Habitaciones</span></div>
            <div class="key-feature"><span class="kf-value">{{ $property->banos }}</span><span class="kf-label">Baños</span></div>
            <div class="key-feature"><span class="kf-value">{{ $property->superficie_m2 }} m²</span><span class="kf-label">Superficie</span></div>
            @if($property->piso !== null)<div class="key-feature"><span class="kf-value">{{ $property->piso }}º</span><span class="kf-label">Planta</span></div>@endif
            @if($property->certificado_energetico)
                <div class="key-feature">
                    <span class="kf-value energy-cert energy-{{ strtolower($property->certificado_energetico) }}">{{ $property->certificado_energetico }}</span>
                    <span class="kf-label">Certificado</span>
                </div>
            @endif
        </div>

        {{-- DESCRIPTION --}}
        <div class="detail-section">
            <h2 class="detail-section-title">Descripción</h2>
            <div class="detail-description">{{ $property->descripcion }}</div>
        </div>

        {{-- CERTIFICADO ENERGÉTICO - DESCARGA DIRECTA --}}
        @if($property->certificado_energetico_archivo)
        <div class="detail-section">
            <h2 class="detail-section-title">Documentación</h2>
            <a href="{{ Storage::disk('public')->url($property->certificado_energetico_archivo) }}"
               target="_blank"
               download
               class="cert-download-btn"
               style="display: inline-flex; align-items: center; gap: 12px; padding: 16px 24px; background: #f5f5f7; border-radius: 12px; text-decoration: none; color: #1d1d1f; font-size: 15px; font-weight: 500; transition: all 0.2s; border: 1px solid #e5e5ea;"
               onmouseover="this.style.background='#ededf2'; this.style.borderColor='#0071e3';"
               onmouseout="this.style.background='#f5f5f7'; this.style.borderColor='#e5e5ea';">
                <span style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: #0071e3; border-radius: 8px; flex-shrink: 0;">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="white" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                </span>
                <span>
                    <span style="display: block; font-weight: 600; color: #1d1d1f;">Certificado Energético</span>
                    <span style="display: block; font-size: 13px; color: #86868b; margin-top: 2px;">Descargar PDF oficial</span>
                </span>
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#86868b" stroke-width="2" style="margin-left: auto;">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
            </a>
        </div>
        @endif

        {{-- AMENITIES --}}
        <div class="detail-section">
            <h2 class="detail-section-title">Características</h2>
            <div class="amenities-grid">
                @foreach([
                    ['tiene_ascensor','Ascensor'],['tiene_parking','Parking'],['tiene_terraza','Terraza'],
                    ['tiene_jardin','Jardín'],['tiene_piscina','Piscina'],['aire_acondicionado','Aire Acond.']
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
        @php $docs = $property->medios->where('tipo_archivo', 'pdf'); @endphp
        @if($docs->count())
        <div class="detail-section">
            <h2 class="detail-section-title">Documentos</h2>
            <div class="docs-list">
                @foreach($docs as $doc)
                    <a href="{{ $doc->url }}" target="_blank" class="doc-item" download>
                        <svg viewBox="0 0 24 24" width="20" height="20"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" fill="none" stroke-width="2"/><polyline points="14 2 14 8 20 8" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                        {{ $doc->nombre_original }}
                        <small>({{ $doc->tamano_archivo_kb }} KB)</small>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- MAP --}}
        @if($property->latitud && $property->longitud)
        <div class="detail-section">
            <h2 class="detail-section-title">Ubicación</h2>
            <div class="map-placeholder">
                <p>Lat: {{ $property->latitud }}, Lon: {{ $property->longitud }}</p>
                <p class="text-muted">{{ $property->direccion }}, {{ $property->ciudad }}</p>
            </div>
        </div>
        @endif

        {{-- RELATED --}}
        @if($related->count())
        <div class="detail-section">
            <h2 class="detail-section-title">Propiedades similares en {{ $property->ciudad }}</h2>
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
            @auth
                @if(Auth::id() === $property->usuario_id)
                    <h3>Gestionar Anuncio</h3>
                    <p class="contact-subtitle">Este anuncio es tuyo. Puedes gestionarlo desde tu panel.</p>
                    <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 20px;">
                        <a href="{{ route('user.properties.index') }}" class="btn btn-primary btn-block" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                            Ir a Mis Publicaciones
                        </a>
                        <form action="{{ route('user.properties.toggle', $property->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline btn-block" style="display: flex; align-items: center; justify-content: center; width: 100%;">
                                {{ $property->activa ? 'Desactivar Anuncio' : 'Activar Anuncio' }}
                            </button>
                        </form>
                    </div>
                @else
                    <h3>¿Interesado en este inmueble?</h3>
                    <p class="contact-subtitle">Contáctanos sin compromiso</p>

                    <form id="inquiry-form" data-property-id="{{ $property->id }}">
                        @csrf
                        @guest
                            <div class="form-group">
                                <input type="text" name="nombre_visitante" placeholder="Tu nombre *" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <input type="email" name="correo_visitante" placeholder="Tu email *" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <input type="tel" name="telefono_visitante" placeholder="Tu teléfono" class="form-input">
                            </div>
                        @endguest
                        <div class="form-group">
                            <textarea name="mensaje" placeholder="Me interesa esta propiedad. ¿Podría concertar una visita?" class="form-textarea" rows="4" required minlength="10"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" id="inquiry-submit">Enviar consulta</button>
                        <div id="inquiry-response" style="margin-top: 15px;"></div>
                    </form>
                @endif
            @else
                <h3>¿Interesado en este inmueble?</h3>
                <p class="contact-subtitle">Contáctanos sin compromiso</p>

                <form id="inquiry-form" data-property-id="{{ $property->id }}">
                    @csrf
                    @guest
                        <div class="form-group">
                            <input type="text" name="nombre_visitante" placeholder="Tu nombre *" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="correo_visitante" placeholder="Tu email *" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <input type="tel" name="telefono_visitante" placeholder="Tu teléfono" class="form-input">
                        </div>
                    @endguest
                    <div class="form-group">
                        <textarea name="mensaje" placeholder="Me interesa esta propiedad. ¿Podría concertar una visita?" class="form-textarea" rows="4" required minlength="10"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" id="inquiry-submit">Enviar consulta</button>
                    <div id="inquiry-response" style="margin-top: 15px;"></div>
                </form>
            @endauth

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
    @vite('resources/js/pages/detail.js')
@endpush
