@props(['property'])
@php
    $fallbackImage = 'https://images.unsplash.com/photo-1560185127-6ed189bf02f4?auto=format&fit=crop&q=80&w=1200';
    $displayImage = $property->cover_url ?: $fallbackImage;
@endphp
<article class="property-card" itemscope itemtype="https://schema.org/Residence">
    @if($property->destacada)
        <span class="badge badge-featured">DESTACADO</span>
    @endif
    <span class="badge badge-operation">
        {{ strtoupper($property->tipo_operacion) }}
    </span>

    <div class="card-image-container">
        <div class="card-image-slider">
            @php $images = $property->medios->where('tipo_archivo', 'imagen')->values(); @endphp
            @forelse($images as $idx => $img)
                <div class="card-image {{ $idx === 0 ? 'active' : '' }}" 
                     data-index="{{ $idx }}"
                     @if($idx > 0) style="display: none;" @endif>
                    <img src="{{ $img->url }}" 
                         alt="{{ $property->titulo }} - Imagen {{ $idx + 1 }}"
                         loading="{{ $idx === 0 ? 'eager' : 'lazy' }}"
                         class="property-card-img">
                    <a href="{{ route('properties.show', [$property->id, $property->slug]) }}" class="card-click-area" aria-label="Ver detalles de {{ $property->titulo }}"></a>
                </div>
            @empty
                <div class="card-image active">
                    <img src="{{ $fallbackImage }}" 
                         alt="Sin imagen"
                         loading="eager"
                         class="property-card-img">
                    <a href="{{ route('properties.show', [$property->id, $property->slug]) }}" class="card-click-area" aria-label="Ver detalles de {{ $property->titulo }}"></a>
                </div>
            @endforelse
        </div>

        @if($images->count() > 1)
            <button class="card-arrow arrow-prev" onclick="changeCardImage(event, this, -1)" aria-label="Imagen anterior">
                <svg viewBox="0 0 24 24" width="18" height="18"><polyline points="15 18 9 12 15 6" stroke="currentColor" fill="none" stroke-width="2.5"/></svg>
            </button>
            <button class="card-arrow arrow-next" onclick="changeCardImage(event, this, 1)" aria-label="Siguiente imagen">
                <svg viewBox="0 0 24 24" width="18" height="18"><polyline points="9 18 15 12 9 6" stroke="currentColor" fill="none" stroke-width="2.5"/></svg>
            </button>
            <div class="card-dots">
                @foreach($images as $idx => $img)
                    <span class="dot {{ $idx === 0 ? 'active' : '' }}"></span>
                @endforeach
            </div>
        @endif
    </div>

    <div class="card-body">
        <div class="card-price" itemprop="price">
            €{{ $property->formatted_price }}{{ $property->tipo_operacion === 'alquiler' ? '/mes' : '' }}
        </div>
        <h3 class="card-title" itemprop="name">
            <a href="{{ route('properties.show', [$property->id, $property->slug]) }}">{{ $property->titulo }}</a>
        </h3>
        <p class="card-address" itemprop="address">
            {{ $property->ciudad }}
        </p>
        <div class="card-features">
            <span title="Habitaciones">
                <svg viewBox="0 0 24 24" width="14" height="14"><path d="M3 9l9-7 9 7v11H3V9z" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                {{ $property->habitaciones }} hab
            </span>
            <span title="Baños">
                <svg viewBox="0 0 24 24" width="14" height="14"><path d="M4 12h16M4 12V8a4 4 0 0 1 8 0" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                {{ $property->banos }} baños
            </span>
            <span title="Superficie">
                <svg viewBox="0 0 24 24" width="14" height="14"><rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                {{ $property->superficie_m2 }} m²
            </span>
        </div>
        <div class="card-amenities">
            @if($property->tiene_ascensor) <span class="amenity-tag">Ascensor</span> @endif
            @if($property->tiene_terraza) <span class="amenity-tag">Terraza</span> @endif
            @if($property->tiene_piscina) <span class="amenity-tag">Piscina</span> @endif
            @if($property->tiene_parking) <span class="amenity-tag">Parking</span> @endif
        </div>
        @if(isset($footer))
            <div class="card-footer" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(0,0,0,0.05)">
                {{ $footer }}
            </div>
        @endif
    </div>
</article>
