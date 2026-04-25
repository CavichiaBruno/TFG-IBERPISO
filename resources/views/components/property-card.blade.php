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
    </div>
</article>
