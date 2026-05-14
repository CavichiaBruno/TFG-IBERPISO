@extends('layouts.admin')
@section('title', 'Interacciones')
@section('page-title', 'Interacciones de Usuarios')

@section('topbar-actions')
<a href="{{ route('admin.interactions.create') }}" class="btn btn-primary">
    <svg viewBox="0 0 24 24" width="16" height="16"><path d="M12 5v14M5 12h14" stroke="currentColor" fill="none" stroke-width="2"/></svg>
    Nueva Interacción
</a>
@endsection

@section('content')
<div class="dashboard-card">
    <div class="table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Propiedad</th>
                    <th>Tipo</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($interactions as $interaction)
                    <tr>
                        <td>#{{ $interaction->id }}</td>
                        <td>{{ $interaction->user ? $interaction->user->nombre : 'Desconocido' }}</td>
                        <td>{{ $interaction->property ? \Illuminate\Support\Str::limit($interaction->property->titulo, 30) : 'Desconocida' }}</td>
                        <td><span class="status-badge status-active">{{ ucfirst($interaction->tipo) }}</span></td>
                        <td>{{ $interaction->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="action-buttons" style="display:flex; gap:8px;">
                                <a href="{{ route('admin.interactions.edit', $interaction->id) }}" class="action-btn action-edit" title="Editar">
                                    <svg viewBox="0 0 24 24" width="16" height="16"><path d="M12 20h9M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                                </a>
                                <button type="button" class="action-btn action-delete" title="Eliminar" onclick="deleteInteraction({{ $interaction->id }})">
                                    <svg viewBox="0 0 24 24" width="16" height="16"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-row">No hay interacciones registradas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($interactions->hasPages())
<div style="margin-top: 24px;">
    {{ $interactions->links() }}
</div>
@endif

@endsection

@push('scripts')
<script>
function deleteInteraction(id) {
    if(confirm('¿Seguro que deseas eliminar esta interacción?')) {
        fetch(`/admin/interacciones/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(res => res.json())
        .then(data => {
            if(data.success) {
                window.location.reload();
            } else {
                alert('Error al eliminar');
            }
        });
    }
}
</script>
@endpush
