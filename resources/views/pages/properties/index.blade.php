@extends('layouts.app')
@section('title', 'Propiedades en España')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/listing.css') }}">
@endpush

@section('content')
<div class="listing-layout">

    {{-- SIDEBAR FILTERS --}}
    <aside class="filters-sidebar" id="filters-sidebar">
        <div class="filters-header">
            <h2>Filtros</h2>
            <a href="{{ route('properties.index') }}" class="clear-filters">Limpiar</a>
        </div>

        <form id="filters-form" method="GET" action="{{ route('properties.index') }}">
            <div class="filter-group">
                <label class="filter-label">Operación</label>
                <div class="radio-group">
                    <label class="radio-option"><input type="radio" name="operacion" value="venta" {{ request('operacion')=='venta'?'checked':'' }}> Venta</label>
                    <label class="radio-option"><input type="radio" name="operacion" value="alquiler" {{ request('operacion')=='alquiler'?'checked':'' }}> Alquiler</label>
                </div>
            </div>

            <div class="filter-group">
                <label class="filter-label">Tipo de propiedad</label>
                @foreach(['piso','casa','chalet','local','garaje','oficina'] as $t)
                    <label class="checkbox-option">
                        <input type="checkbox" name="tipo[]" value="{{ $t }}" {{ in_array($t, (array)request('tipo')) ? 'checked' : '' }}>
                        {{ ucfirst($t) }}
                    </label>
                @endforeach
            </div>

            <div class="filter-group">
                <label class="filter-label">Precio (€)</label>
                <div class="range-inputs">
                    <input type="number" name="precio_min" class="range-input" placeholder="Mín." value="{{ request('precio_min') }}">
                    <span>—</span>
                    <input type="number" name="precio_max" class="range-input" placeholder="Máx." value="{{ request('precio_max') }}">
                </div>
            </div>

            <div class="filter-group">
                <label class="filter-label">Superficie (m²)</label>
                <div class="range-inputs">
                    <input type="number" name="superficie_min" class="range-input" placeholder="Mín." value="{{ request('superficie_min') }}">
                    <span>—</span>
                    <input type="number" name="superficie_max" class="range-input" placeholder="Máx." value="{{ request('superficie_max') }}">
                </div>
            </div>

            <div class="filter-group">
                <label class="filter-label">Habitaciones</label>
                <div class="pill-group">
                    @foreach([1,2,3,4,'5+'] as $r)
                        <button type="button" class="pill {{ request('habitaciones')==(string)$r?'active':'' }}" data-name="habitaciones" data-value="{{ $r }}">{{ $r }}</button>
                    @endforeach
                </div>
                <input type="hidden" name="habitaciones" value="{{ request('habitaciones') }}" id="habitaciones-val">
            </div>

            <div class="filter-group">
                <label class="filter-label">Baños</label>
                <div class="pill-group">
                    @foreach([1,2,'3+'] as $b)
                        <button type="button" class="pill {{ request('banos')==(string)$b?'active':'' }}" data-name="banos" data-value="{{ $b }}">{{ $b }}</button>
                    @endforeach
                </div>
                <input type="hidden" name="banos" value="{{ request('banos') }}" id="banos-val">
            </div>

            <div class="filter-group">
                <label class="filter-label">Características</label>
                @foreach([
                    ['has_elevator','Ascensor'],['has_parking','Parking'],['has_terrace','Terraza'],
                    ['has_garden','Jardín'],['has_pool','Piscina'],['air_conditioning','Aire Acond.']
                ] as [$key,$label])
                    <label class="checkbox-option">
                        <input type="checkbox" name="{{ $key }}" value="1" {{ request($key)?'checked':'' }}>
                        {{ $label }}
                    </label>
                @endforeach
            </div>

            <div class="filter-group">
                <label class="filter-label">Provincia</label>
                <select name="provincia" class="filter-select">
                    <option value="">Todas las provincias</option>
                    @foreach($provinces as $p)
                        <option value="{{ $p }}" {{ request('provincia')==$p?'selected':'' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Aplicar filtros</button>
        </form>
    </aside>

    {{-- RESULTS --}}
    <div class="listing-results">
        <div class="results-topbar">
            <span class="results-count"><strong>{{ $properties->total() }}</strong> propiedades encontradas</span>
            <div class="results-controls">
                <select name="orden" id="sort-select" class="sort-select">
                    <option value="reciente" {{ request('orden','reciente')=='reciente'?'selected':'' }}>Más reciente</option>
                    <option value="precio_asc" {{ request('orden')=='precio_asc'?'selected':'' }}>Precio: menor a mayor</option>
                    <option value="precio_desc" {{ request('orden')=='precio_desc'?'selected':'' }}>Precio: mayor a menor</option>
                    <option value="superficie" {{ request('orden')=='superficie'?'selected':'' }}>Mayor superficie</option>
                </select>
                <div class="view-toggle">
                    <button id="view-grid" class="view-btn active" aria-label="Vista cuadrícula">
                        <svg viewBox="0 0 24 24" width="16" height="16"><rect x="3" y="3" width="7" height="7" stroke="currentColor" fill="none" stroke-width="2"/><rect x="14" y="3" width="7" height="7" stroke="currentColor" fill="none" stroke-width="2"/><rect x="3" y="14" width="7" height="7" stroke="currentColor" fill="none" stroke-width="2"/><rect x="14" y="14" width="7" height="7" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                    </button>
                    <button id="view-list" class="view-btn" aria-label="Vista lista">
                        <svg viewBox="0 0 24 24" width="16" height="16"><line x1="8" y1="6" x2="21" y2="6" stroke="currentColor" stroke-width="2"/><line x1="8" y1="12" x2="21" y2="12" stroke="currentColor" stroke-width="2"/><line x1="8" y1="18" x2="21" y2="18" stroke="currentColor" stroke-width="2"/><line x1="3" y1="6" x2="3.01" y2="6" stroke="currentColor" stroke-width="2"/><line x1="3" y1="12" x2="3.01" y2="12" stroke="currentColor" stroke-width="2"/><line x1="3" y1="18" x2="3.01" y2="18" stroke="currentColor" stroke-width="2"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <div id="results-container" class="properties-grid">
            @include('pages.properties._results', ['properties' => $properties])
        </div>

        <div id="pagination-container" class="pagination-wrapper">
            {{ $properties->links('components.pagination') }}
        </div>
    </div>
</div>

{{-- MOBILE FILTER BUTTON --}}
<button class="mobile-filter-btn" id="mobile-filter-btn">
    <svg viewBox="0 0 24 24" width="18" height="18"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" stroke="currentColor" fill="none" stroke-width="2"/></svg>
    Filtros
</button>
@endsection

@push('scripts')
<script src="{{ asset('js/listing.js') }}"></script>
@endpush
