@extends('layouts.admin')
@section('title', 'Nueva Noticia')
@section('page-title', 'Nueva Noticia')

@section('content')
@if($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 24px; padding: 16px; background: #fff0f0; border-radius: 12px; font-family: var(--font-body); font-size: 14px; letter-spacing: -0.224px; color: #ff453a; border: none;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-container" style="max-width: 800px; margin: 0 auto; background: var(--white); padding: 32px; border-radius: 12px; box-shadow: var(--shadow-sm);">
    <form action="{{ route('admin.articles.store') }}" method="POST">
        @csrf
        <div class="form-grid-2">
            <div class="form-group form-col-2">
                <label class="form-label">Título *</label>
                <input type="text" name="titulo" class="form-input" value="{{ old('titulo') }}" required>
            </div>
            <div class="form-group form-col-2">
                <label class="form-label">Slug (URL amigable) *</label>
                <input type="text" name="slug" class="form-input" value="{{ old('slug') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Categoría</label>
                <input type="text" name="categoria" class="form-input" value="{{ old('categoria', 'Noticias') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Autor</label>
                <input type="text" name="autor" class="form-input" value="{{ old('autor', 'IberPiso') }}">
            </div>
            <div class="form-group form-col-2">
                <label class="form-label">URL de la Imagen de Portada</label>
                <input type="url" name="imagen_url" class="form-input" value="{{ old('imagen_url') }}" placeholder="https://...">
            </div>
            <div class="form-group form-col-2">
                <label class="form-label">Contenido *</label>
                <textarea name="contenido" class="form-textarea" rows="12" required style="font-family: var(--font-body); line-height: 1.5; padding: 16px;">{{ old('contenido') }}</textarea>
            </div>
            <div class="form-group form-col-2" style="display:flex; align-items:center; gap: 12px;">
                <input type="checkbox" name="publicado" id="publicado" value="1" {{ old('publicado', true) ? 'checked' : '' }} style="width:20px; height:20px; accent-color:#0071e3;">
                <label for="publicado" class="form-label" style="margin:0; cursor:pointer;">Publicar inmediatamente</label>
            </div>
        </div>

        <div class="form-actions" style="margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--gray-subtle);">
            <a href="{{ route('admin.articles.index') }}" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">Crear Noticia</button>
        </div>
    </form>
</div>
@endsection
