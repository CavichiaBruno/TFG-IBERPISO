@extends('layouts.admin')
@section('title', 'Propiedades')

@section('content')
<div class="page-header">
    <h1>Catálogo de Propiedades</h1>
    <div class="header-actions">
        <a href="{{ route('admin.properties.create') }}" class="btn-admin btn-admin-primary">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Nueva Propiedad
        </a>
    </div>
</div>

<div class="admin-toolbar">
    <div class="toolbar-primary">
        <div class="search-box">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" id="admin-search" class="admin-input" placeholder="Buscar por título, ciudad o dirección…" value="{{ request('q') }}">
        </div>
    </div>
    
    <div class="toolbar-filters">
        @foreach(['all' => 'Todas las propiedades', 'activas' => 'Activas', 'inactivas' => 'Inactivas', 'destacadas' => 'Destacadas', 'venta' => 'En Venta', 'alquiler' => 'En Alquiler'] as $val => $label)
            <a href="{{ route('admin.properties.index', ['filtro' => $val]) }}"
                class="filter-tab {{ request('filtro', 'all') === $val ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>
</div>

<div class="admin-card">
    <div class="admin-table-container" id="properties-table">
        @include('admin.properties._table', ['properties' => $properties])
    </div>
</div>

<div id="admin-pagination">
    {{ $properties->links('components.pagination') }}
</div>

{{-- DELETE MODAL (Fixed structure) --}}
<div class="modal-overlay" id="delete-modal" aria-hidden="true">
    <div class="modal-content">
        <h3 style="margin-bottom: 8px;">¿Eliminar esta propiedad?</h3>
        <p style="color: var(--admin-text-secondary); font-size: 14px; margin-bottom: 24px;">Esta acción es irreversible y eliminará todas las imágenes asociadas de forma permanente.</p>
        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <button class="btn-admin btn-admin-outline" id="cancel-delete">Cancelar</button>
            <button class="btn-admin" id="confirm-delete" style="background: #ff453a; color: white;">Eliminar permanentemente</button>
        </div>
    </div>
</div>
@endsection