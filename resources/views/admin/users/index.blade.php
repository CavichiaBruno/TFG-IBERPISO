@extends('layouts.admin')
@section('title', 'Usuarios')

@section('content')
<div class="page-header">
    <h1>Gestión de Usuarios</h1>
    <div class="header-actions">
        <a href="{{ route('admin.users.create') }}" class="btn-admin btn-admin-primary">+ Nuevo Usuario</a>
    </div>
</div>

<div class="admin-toolbar">
    <div class="toolbar-primary">
        <div class="search-box">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" id="user-search" class="admin-input" placeholder="Buscar por nombre o email…">
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th style="text-align: center;">Estado</th>
                    <th>Registro</th>
                    <th style="text-align: right; padding-right: 24px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr data-id="{{ $user->id }}">
                    <td>
                        <div class="prop-cell">
                            <div class="sidebar-avatar" style="width: 32px; height: 32px; font-size: 12px;">{{ strtoupper(substr($user->nombre, 0, 1)) }}</div>
                            <div class="prop-info">
                                <span class="prop-title">{{ $user->nombre }}</span>
                                <span class="prop-meta">{{ $user->telefono ?? 'Sin teléfono' }}</span>
                            </div>
                        </div>
                    </td>
                    <td><span class="prop-title" style="font-size: 13px;">{{ $user->correo }}</span></td>
                    <td>
                        <span class="badge {{ $user->rol === 'admin' ? 'badge-info' : 'badge-gray' }}">
                            {{ ucfirst($user->rol) }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <button class="badge {{ $user->activo ? 'badge-success' : 'badge-gray' }} toggle-user-btn"
                            data-id="{{ $user->id }}"
                            style="border:none; cursor:{{ $user->id === auth()->id() ? 'default' : 'pointer' }};"
                            {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            {{ $user->activo ? 'Activo' : 'Inactivo' }}
                        </button>
                    </td>
                    <td><span class="prop-meta">{{ $user->created_at->format('d/m/Y') }}</span></td>
                    <td style="text-align: right; padding-right: 24px;">
                        @if(auth()->id() != $user->id)
                        <div style="display: flex; gap: 4px; justify-content: flex-end;">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="icon-btn" title="Editar">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </a>
                            <button class="icon-btn delete-user-btn" data-id="{{ $user->id }}" style="color: #ff453a;" title="Eliminar">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>
                            </button>
                        </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="padding: 64px; text-align: center; color: var(--admin-text-secondary);">No hay usuarios registrados</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top: 24px;">
    {{ $users->links('components.pagination') }}
</div>
@endsection
