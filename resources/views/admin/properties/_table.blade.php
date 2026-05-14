<table class="admin-table">
    <thead>
        <tr>
            <th style="width: 40px; padding-left: 24px;"><input type="checkbox" id="select-all"></th>
            <th>Propiedad</th>
            <th>Localización</th>
            <th>Precio</th>
            <th>Tipo</th>
            <th style="text-align: center;">Estado</th>
            <th style="text-align: right; padding-right: 24px;">Acciones</th>
        </tr>
    </thead>
    <tbody>
    @forelse($properties as $p)
        <tr data-id="{{ $p->id }}">
            <td style="padding-left: 24px;"><input type="checkbox" class="row-check" value="{{ $p->id }}"></td>
            <td>
                <div class="prop-cell">
                    <div class="prop-thumb">
                        <img src="{{ $p->cover_url }}" alt="" loading="lazy" width="44" height="44">
                    </div>
                    <div class="prop-info">
                        <span class="prop-title" title="{{ $p->titulo }}">{{ Str::limit($p->titulo, 40) }}</span>
                        <span class="prop-meta">ID: #{{ 1000 + $p->id }}</span>
                    </div>
                </div>
            </td>
            <td>
                <div class="prop-info">
                    <span class="prop-title" style="font-size: 13px;">{{ $p->ciudad }}</span>
                    <span class="prop-meta">{{ $p->provincia }}</span>
                </div>
            </td>
            <td>
                <span style="font-weight: 700; color: var(--admin-text-primary);">€{{ $p->formatted_price }}</span>
            </td>
            <td>
                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--admin-text-secondary);">{{ $p->tipo_propiedad }}</span>
                <div style="font-size: 12px; color: {{ $p->tipo_operacion === 'venta' ? 'var(--admin-accent)' : 'inherit' }}; font-weight: 500;">
                    {{ ucfirst($p->tipo_operacion) }}
                </div>
            </td>
            <td style="text-align: center;">
                <button class="badge {{ $p->activa ? 'badge-success' : 'badge-gray' }} toggle-active-btn" 
                        data-id="{{ $p->id }}" 
                        style="border:none; cursor:pointer;" 
                        title="Cambiar estado">
                    {{ $p->activa ? 'Activa' : 'Inactiva' }}
                </button>
            </td>
            <td style="text-align: right; padding-right: 24px;">
                <div style="display: flex; gap: 4px; justify-content: flex-end;">
                    <a href="{{ route('properties.show', [$p->id, $p->slug]) }}" target="_blank" class="icon-btn" title="Ver publicación">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </a>
                    <a href="{{ route('admin.properties.edit', $p->id) }}" class="icon-btn" title="Editar">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </a>
                    <button class="icon-btn delete-btn" data-id="{{ $p->id }}" title="Eliminar" style="color: #ff453a;">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>
                    </button>
                </div>
            </td>
        </tr>
    @empty
        <tr><td colspan="7" style="padding: 64px; text-align: center; color: var(--admin-text-secondary);">No se encontraron propiedades</td></tr>
    @endforelse
    </tbody>
</table>
