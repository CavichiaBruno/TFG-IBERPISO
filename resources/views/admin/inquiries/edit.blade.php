@extends('layouts.admin')
@section('title', 'Editar Consulta')
@section('page-title', 'Editar Consulta')

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
    <form action="{{ route('admin.inquiries.update', $inquiry->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-grid-2">
            <div class="form-group form-col-2">
                <label class="form-label">ID Propiedad (Opcional)</label>
                <input type="number" name="propiedad_id" class="form-input" value="{{ old('propiedad_id', $inquiry->propiedad_id) }}">
            </div>
            <div class="form-group form-col-2">
                <label class="form-label">ID Usuario (Opcional si se proporciona email y nombre)</label>
                <input type="number" name="usuario_id" class="form-input" value="{{ old('usuario_id', $inquiry->usuario_id) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Nombre {{ $inquiry->usuario_id ? '(Sincronizado con Perfil)' : 'Visitante' }}</label>
                <input type="text" name="nombre_visitante" class="form-input" 
                       value="{{ old('nombre_visitante', $inquiry->sender_name) }}" 
                       {{ $inquiry->usuario_id ? 'readonly style=background:#f5f5f7;' : '' }}>
            </div>
            <div class="form-group">
                <label class="form-label">Correo {{ $inquiry->usuario_id ? '(Sincronizado con Perfil)' : 'Visitante' }}</label>
                <input type="email" name="correo_visitante" class="form-input" 
                       value="{{ old('correo_visitante', $inquiry->sender_email) }}"
                       {{ $inquiry->usuario_id ? 'readonly style=background:#f5f5f7;' : '' }}>
            </div>
            <div class="form-group">
                <label class="form-label">Teléfono Visitante</label>
                <input type="text" name="telefono_visitante" class="form-input" value="{{ old('telefono_visitante', $inquiry->telefono_visitante) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="pendiente" {{ old('estado', $inquiry->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="leida" {{ old('estado', $inquiry->estado) == 'leida' ? 'selected' : '' }}>Leída</option>
                    <option value="respondida" {{ old('estado', $inquiry->estado) == 'respondida' ? 'selected' : '' }}>Respondida</option>
                </select>
            </div>
            <div class="form-group form-col-2">
                <label class="form-label">Mensaje</label>
                <textarea name="mensaje" class="form-textarea" rows="4" required>{{ old('mensaje', $inquiry->mensaje) }}</textarea>
            </div>
        </div>

        <div class="form-actions" style="margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--gray-subtle);">
            <a href="{{ route('admin.inquiries.index') }}" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar Consulta</button>
        </div>
    </form>
</div>
@endsection
