@extends('layouts.admin')
@section('title', 'Usuarios')
@section('page-title', 'Gestión de Usuarios')

@section('topbar-actions')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Nuevo usuario</a>
@endsection

@section('content')
@if($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 20px; padding: 12px 16px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 6px; color: #721c24;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

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
                        <div class="user-avatar">{{ strtoupper(substr($user->nombre, 0, 1)) }}</div>
                        {{ $user->nombre }}
                    </div>
                </td>
                <td>{{ $user->correo }}</td>
                <td><span class="role-badge role-{{ $user->rol }}">{{ ucfirst($user->rol) }}</span></td>
                <td>{{ $user->telefono ?? '—' }}</td>
                <td>
                    <button class="toggle-user-btn {{ $user->activo ? 'active' : 'inactive' }}"
                        data-id="{{ $user->id }}"
                        {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        {{ $user->activo ? 'Activo' : 'Inactivo' }}
                    </button>
                </td>
                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                <td>
                    <div class="action-buttons">
                        @if(auth()->user()->id != $user->id)
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="action-btn action-edit">
                            <svg viewBox="0 0 24 24" width="15" height="15"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" fill="none" stroke-width="2"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                        </a>
                        <button type="button" class="action-btn action-delete delete-user-btn" data-id="{{ $user->id }}" title="Eliminar">
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

@endsection
