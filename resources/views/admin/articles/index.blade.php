@extends('layouts.admin')
@section('title', 'Noticias')

@section('content')
<div class="page-header">
    <h1>Centro de Noticias</h1>
    <div class="header-actions">
        <a href="{{ route('admin.articles.create') }}" class="btn-admin btn-admin-primary">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Nueva Noticia
        </a>
    </div>
</div>

<div class="admin-card">
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Noticia</th>
                    <th>Categoría</th>
                    <th>Autor</th>
                    <th style="text-align: center;">Estado</th>
                    <th>Fecha</th>
                    <th style="text-align: right; padding-right: 24px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                    <tr>
                        <td>
                            <div class="prop-info">
                                <span class="prop-title">{{ \Illuminate\Support\Str::limit($article->titulo, 50) }}</span>
                                <span class="prop-meta">ID: #{{ $article->id }}</span>
                            </div>
                        </td>
                        <td><span class="badge badge-gray">{{ $article->categoria }}</span></td>
                        <td><span class="prop-title" style="font-size: 13px;">{{ $article->autor }}</span></td>
                        <td style="text-align: center;">
                            <span class="badge {{ $article->publicado ? 'badge-success' : 'badge-warning' }}">
                                {{ $article->publicado ? 'Publicado' : 'Borrador' }}
                            </span>
                        </td>
                        <td><span class="prop-meta">{{ $article->fecha_publicacion->format('d/m/Y') }}</span></td>
                        <td style="text-align: right; padding-right: 24px;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <a href="{{ route('articles.show', $article->slug) }}" class="icon-btn" target="_blank" title="Ver publicación">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </a>
                                <a href="{{ route('admin.articles.edit', $article->id) }}" class="icon-btn" title="Editar">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </a>
                                <button type="button" class="icon-btn" style="color: #ff453a;" onclick="deleteArticle({{ $article->id }})" title="Eliminar">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 64px; text-align: center; color: var(--admin-text-secondary);">No hay noticias publicadas</td>
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
