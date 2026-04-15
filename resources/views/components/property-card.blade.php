@props(['property'])
<article class="property-card">
    <a href="{{ route('properties.show', [$property->id, $property->slug]) }}" class="card-image-link">
        <div class="card-image" style="background-image: url('{{ $property->cover_url }}')">
            @if($property->is_featured)
                <span class="badge badge-featured">Destacado</span>
            @endif
            <span class="badge badge-operation {{ $property->operation_type === 'venta' ? 'badge-venta' : 'badge-alquiler' }}">
                {{ strtoupper($property->operation_type) }}
            </span>
        </div>
    </a>
    <div class="card-body">
        <div class="card-price">€{{ $property->formatted_price }}{{ $property->operation_type === 'alquiler' ? '/mes' : '' }}</div>
        <h3 class="card-title">
            <a href="{{ route('properties.show', [$property->id, $property->slug]) }}">{{ $property->title }}</a>
        </h3>
        <p class="card-address">
            <svg viewBox="0 0 24 24" width="14" height="14"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" stroke="currentColor" fill="none" stroke-width="2"/><circle cx="12" cy="10" r="3" stroke="currentColor" fill="none" stroke-width="2"/></svg>
            {{ $property->address }}, {{ $property->city }}
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
    </div>
</article>
