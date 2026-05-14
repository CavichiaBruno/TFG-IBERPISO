@extends('layouts.admin')
@section('title', 'Noticias')
@section('page-title', 'Noticias y Artículos')

@section('topbar-actions')
<a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
    <svg viewBox="0 0 24 24" width="16" height="16"><path d="M12 5v14M5 12h14" stroke="currentColor" fill="none" stroke-width="2"/></svg>
    Nueva Noticia
</a>
@endsection

@section('content')
<div class="dashboard-card">
    <div class="table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Autor</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                    <tr>
                        <td>#{{ $article->id }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($article->titulo, 40) }}</td>
                        <td>{{ $article->categoria }}</td>
                        <td>{{ $article->autor }}</td>
                        <td>
                            @if($article->publicado)
                                <span class="status-badge status-active">Publicado</span>
                            @else
                                <span class="status-badge status-inactive">Borrador</span>
                            @endif
                        </td>
                        <td>{{ $article->fecha_publicacion->format('d/m/Y') }}</td>
                        <td>
                            <div class="action-buttons" style="display:flex; gap:8px;">
                                <a href="{{ route('articles.show', $article->slug) }}" class="action-btn" target="_blank" title="Ver" style="padding:4px 8px;">
                                    Ver
                                </a>
                                <a href="{{ route('admin.articles.edit', $article->id) }}" class="action-btn action-edit" title="Editar" style="padding:4px 8px;">
                                    Editar
                                </a>
                                <button type="button" class="action-btn action-delete" title="Eliminar" onclick="deleteArticle({{ $article->id }})" style="padding:4px 8px;">
                                    Borrar
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-row">No hay noticias publicadas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($articles->hasPages())
<div style="margin-top: 24px;">
    {{ $articles->links() }}
</div>
@endif

@endsection

@push('scripts')
<script>
function deleteArticle(id) {
    if(confirm('¿Seguro que deseas eliminar esta noticia?')) {
        fetch(`/admin/noticias/${id}`, {
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
