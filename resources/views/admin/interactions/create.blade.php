@extends('layouts.admin')
@section('title', 'Crear Interacción')
@section('page-title', 'Nueva Interacción')

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

<div class="form-container" style="max-width: 680px; margin: 0 auto; background: var(--white); padding: 32px; border-radius: 12px; box-shadow: var(--shadow-sm);">
    <form action="{{ route('admin.interactions.store') }}" method="POST">
        @csrf
        <div class="form-grid-2">
            <div class="form-group form-col-2">
                <label class="form-label">ID Usuario *</label>
                <input type="number" name="usuario_id" class="form-input" value="{{ old('usuario_id') }}" required>
            </div>
            <div class="form-group form-col-2">
                <label class="form-label">ID Propiedad *</label>
                <input type="number" name="propiedad_id" class="form-input" value="{{ old('propiedad_id') }}" required>
            </div>
            <div class="form-group form-col-2">
                <label class="form-label">Tipo de Interacción *</label>
                <select name="tipo" class="form-select" required>
                    <option value="like" {{ old('tipo') == 'like' ? 'selected' : '' }}>Like</option>
                    <option value="dislike" {{ old('tipo') == 'dislike' ? 'selected' : '' }}>Dislike</option>
                    <option value="view" {{ old('tipo') == 'view' ? 'selected' : '' }}>View</option>
                </select>
            </div>
        </div>

        <div class="form-actions" style="margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--gray-subtle);">
            <a href="{{ route('admin.interactions.index') }}" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">Crear Interacción</button>
        </div>
    </form>
</div>
@endsection
