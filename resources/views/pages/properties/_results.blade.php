@forelse($properties as $property)
    <x-property-card :property="$property" />
@empty
    <div class="no-results">
        <svg viewBox="0 0 24 24" width="48" height="48"><circle cx="11" cy="11" r="8" stroke="currentColor" fill="none" stroke-width="1.5"/><line x1="21" y1="21" x2="16.65" y2="16.65" stroke="currentColor" stroke-width="1.5"/></svg>
        <h3>No se encontraron propiedades</h3>
        <p>Prueba ajustando los filtros de búsqueda.</p>
        <a href="{{ route('properties.index') }}" class="btn btn-primary">Ver todas</a>
    </div>
@endforelse
