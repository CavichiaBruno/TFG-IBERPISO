@extends('layouts.admin')
@section('title', 'Propiedades')
@section('page-title', 'Gestión de Propiedades')

@section('topbar-actions')
    <a href="{{ route('admin.properties.create') }}" class="btn btn-primary">+ Nueva propiedad</a>
@endsection

@section('content')
    <div class="admin-toolbar">
        <div class="toolbar-search">
            <svg viewBox="0 0 24 24" width="16" height="16">
                <circle cx="11" cy="11" r="8" stroke="currentColor" fill="none" stroke-width="2" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" stroke="currentColor" stroke-width="2" />
            </svg>
            <input type="text" id="admin-search" placeholder="Buscar por título, ciudad…" value="{{ request('q') }}">
        </div>
        <div class="toolbar-filters">
            @foreach(['all' => 'Todas', 'activas' => 'Activas', 'inactivas' => 'Inactivas', 'destacadas' => 'Destacadas', 'venta' => 'Venta', 'alquiler' => 'Alquiler'] as $val => $label)
                <a href="{{ route('admin.properties.index', ['filtro' => $val]) }}"
                    class="filter-tab {{ request('filtro', 'all') === $val ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    <div class="table-wrapper" id="properties-table">
        @include('admin.properties._table', ['properties' => $properties])
    </div>

    <div id="admin-pagination">
        {{ $properties->links('components.pagination') }}
    </div>

    {{-- DELETE MODAL --}}
    <div class="modal" id="delete-modal" aria-hidden="true">
        <div class="modal-overlay" id="delete-modal-overlay"></div>
        <div class="modal-box">
            <h3>¿Eliminar propiedad?</h3>
            <p>Esta acción no se puede deshacer.</p>
            <div class="modal-actions">
                <button class="btn btn-outline" id="cancel-delete">Cancelar</button>
                <button class="btn btn-danger" id="confirm-delete">Eliminar</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin.js') }}"></script>
@endpush