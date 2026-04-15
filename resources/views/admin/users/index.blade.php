@extends('layouts.admin')
@section('title', 'Usuarios')
@section('page-title', 'Gestión de Usuarios')

@section('topbar-actions')
    <button class="btn btn-primary" id="open-create-user">+ Nuevo usuario</button>
@endsection

@section('content')
<div class="admin-toolbar">
    <div class="toolbar-search">
        <svg viewBox="0 0 24 24" width="16" height="16"><circle cx="11" cy="11" r="8" stroke="currentColor" fill="none" stroke-width="2"/><line x1="21" y1="21" x2="16.65" y2="16.65" stroke="currentColor" stroke-width="2"/></svg>
        <input type="text" id="user-search" placeholder="Buscar por nombre o email…">
    </div>
</div>

<div class="table-wrapper">
    <table class="admin-table">
        <thead>
            <tr><th>Usuario</th><th>Email</th><th>Rol</th><th>Teléfono</th><th>Activo</th><th>Registro</th><th>Acciones</th></tr>
        </thead>
        <tbody>
        @forelse($users as $user)
            <tr data-id="{{ $user->id }}">
                <td>
                    <div class="user-cell">
                        <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        {{ $user->name }}
                    </div>
                </td>
                <td>{{ $user->email }}</td>
                <td><span class="role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
                <td>{{ $user->phone ?? '—' }}</td>
                <td>
                    <button class="toggle-user-btn {{ $user->is_active ? 'active' : 'inactive' }}"
                        data-id="{{ $user->id }}"
                        {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                    </button>
                </td>
                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn action-edit edit-user-btn"
                            data-id="{{ $user->id }}"
                            data-name="{{ $user->name }}"
                            data-email="{{ $user->email }}"
                            data-phone="{{ $user->phone }}"
                            data-role="{{ $user->role }}">
                            <svg viewBox="0 0 24 24" width="15" height="15"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" fill="none" stroke-width="2"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                        </button>
                        @if($user->id !== auth()->id())
                        <button class="action-btn action-delete delete-user-btn" data-id="{{ $user->id }}">
                            <svg viewBox="0 0 24 24" width="15" height="15"><polyline points="3 6 5 6 21 6" stroke="currentColor" fill="none" stroke-width="2"/><path d="M19 6v14H5V6m3 0V4h8v2" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="empty-row">No hay usuarios</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $users->links('components.pagination') }}

{{-- CREATE/EDIT MODAL --}}
<div class="modal" id="user-modal" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-box modal-box-md">
        <div class="modal-header">
            <h3 id="modal-title">Nuevo usuario</h3>
            <button class="modal-close" id="close-user-modal">✕</button>
        </div>
        <form id="user-form">
            @csrf
            <input type="hidden" id="user-id" name="_user_id" value="">
            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="name" id="u-name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="u-email" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Teléfono</label>
                    <input type="tel" name="phone" id="u-phone" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Rol</label>
                    <select name="role" id="u-role" class="form-select">
                        <option value="user">Usuario</option>
                        <option value="agent">Agente</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group form-col-2">
                    <label class="form-label">Contraseña <span id="pwd-hint">(obligatoria)</span></label>
                    <input type="password" name="password" id="u-password" class="form-input" minlength="8">
                </div>
            </div>
            <div id="user-form-error" class="form-error" style="display:none"></div>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" id="close-user-modal-2">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection
