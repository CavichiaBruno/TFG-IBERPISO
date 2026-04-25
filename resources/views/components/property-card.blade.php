@props(['property'])
@php
    $fallbackImage = asset('brain/4b87a6df-5eeb-4d4e-beb3-de1cab4408bc/property_interior_luxury_1777112515796.png');
    $displayImage = $property->cover_url ?: $fallbackImage;
@endphp
<article class="property-card" itemscope itemtype="https://schema.org/Residence">
    @if($property->is_featured)
        <span class="badge badge-featured">DESTACADO</span>
    @endif
    <span class="badge badge-operation">
        {{ strtoupper($property->operation_type) }}
    </span>

    <a href="{{ route('properties.show', [$property->id, $property->slug]) }}" class="card-image-link" tabindex="-1" aria-hidden="true">
        <div class="card-image" style="background-image: url('{{ $displayImage }}')"></div>
    </a>

    <div class="card-body">
        <div class="card-price" itemprop="price">
            €{{ $property->formatted_price }}{{ $property->operation_type === 'alquiler' ? '/mes' : '' }}
        </div>
        <h3 class="card-title" itemprop="name">
            <a href="{{ route('properties.show', [$property->id, $property->slug]) }}">{{ $property->title }}</a>
        </h3>
        <p class="card-address" itemprop="address">
            {{ $property->city }}
        </p>
        <div class="card-features">
            <span title="Habitaciones">
                <svg viewBox="0 0 24 24" width="14" height="14"><path d="M3 9l9-7 9 7v11H3V9z" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                {{ $property->rooms }} hab
            </span>
            <span title="Baños">
                <svg viewBox="0 0 24 24" width="14" height="14"><path d="M4 12h16M4 12V8a4 4 0 0 1 8 0" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                {{ $property->bathrooms }} baños
            </span>
            <span title="Superficie">
                <svg viewBox="0 0 24 24" width="14" height="14"><rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                {{ $property->surface_m2 }} m²
            </span>
        </div>
        <div class="card-amenities">
            @if($property->has_elevator) <span class="amenity-tag">Ascensor</span> @endif
            @if($property->has_terrace) <span class="amenity-tag">Terraza</span> @endif
            @if($property->has_pool) <span class="amenity-tag">Piscina</span> @endif
            @if($property->has_parking) <span class="amenity-tag">Parking</span> @endif
        </div>
        @if(isset($footer))
            <div class="card-footer" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(0,0,0,0.05)">
                {{ $footer }}
            </div>
        @endif
    </div>
</article>
