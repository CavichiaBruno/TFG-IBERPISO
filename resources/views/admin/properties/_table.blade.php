<table class="admin-table">
    <thead>
        <tr>
            <th><input type="checkbox" id="select-all"></th>
            <th>Propiedad</th>
            <th>Ciudad</th>
            <th>Precio</th>
            <th>Tipo</th>
            <th>Operación</th>
            <th>Destacada</th>
            <th>Activa</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    @forelse($properties as $p)
        <tr data-id="{{ $p->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $p->id }}"></td>
            <td>
                <div class="table-property-cell">
                    <div class="table-thumb" style="background-image:url('{{ $p->cover_url }}')"></div>
                    <span title="{{ $p->titulo }}">{{ Str::limit($p->titulo, 40) }}</span>
                </div>
            </td>
            <td>{{ $p->ciudad }}</td>
            <td class="price-cell">€{{ $p->formatted_price }}</td>
            <td><span class="type-badge">{{ ucfirst($p->tipo_propiedad) }}</span></td>
            <td><span class="op-badge op-{{ $p->tipo_operacion }}">{{ ucfirst($p->tipo_operacion) }}</span></td>
            <td>
                <span class="featured-star {{ $p->destacada ? 'featured-yes' : '' }}" title="{{ $p->destacada ? 'Destacada' : 'No destacada' }}">★</span>
            </td>
            <td>
                <button class="toggle-active-btn {{ $p->activa ? 'active' : 'inactive' }}"
                    data-id="{{ $p->id }}" title="{{ $p->activa ? 'Desactivar' : 'Activar' }}">
                    <svg viewBox="0 0 24 24" width="16" height="16">
                        <path d="{{ $p->is_active ? 'M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z' : 'M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19' }}"
                            stroke="currentColor" fill="none" stroke-width="2"/>
                        @if($p->is_active)<circle cx="12" cy="12" r="3" stroke="currentColor" fill="none" stroke-width="2"/>@endif
                    </svg>
                </button>
            </td>
            <td>
                <div class="action-buttons">
                    <a href="{{ route('properties.show', [$p->id, $p->slug]) }}" target="_blank" class="action-btn" title="Ver en portal">
                        <svg viewBox="0 0 24 24" width="15" height="15"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" fill="none" stroke-width="2"/><circle cx="12" cy="12" r="3" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                    </a>
                    <a href="{{ route('admin.properties.edit', $p->id) }}" class="action-btn action-edit" title="Editar">
                        <svg viewBox="0 0 24 24" width="15" height="15"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" fill="none" stroke-width="2"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                    </a>
                    <button class="action-btn action-delete delete-btn" data-id="{{ $p->id }}" title="Eliminar">
                        <svg viewBox="0 0 24 24" width="15" height="15"><polyline points="3 6 5 6 21 6" stroke="currentColor" fill="none" stroke-width="2"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                    </button>
                </div>
            </td>
        </tr>
    @empty
        <tr><td colspan="9" class="empty-row">No se encontraron propiedades</td></tr>
    @endforelse
    </tbody>
</table>
