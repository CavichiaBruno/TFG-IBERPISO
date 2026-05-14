@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
{{-- STATS CARDS --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon stat-icon-blue">
            <svg viewBox="0 0 24 24" width="24" height="24"><path d="M3 9l9-7 9 7v11H3V9z" stroke="currentColor" fill="none" stroke-width="2"/></svg>
        </div>
        <div class="stat-info">
            <span class="stat-value">{{ $stats['total_properties'] }}</span>
            <span class="stat-label">Total Propiedades</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-green">
            <svg viewBox="0 0 24 24" width="24" height="24"><polyline points="20 6 9 17 4 12" stroke="currentColor" fill="none" stroke-width="2"/></svg>
        </div>
        <div class="stat-info">
            <span class="stat-value">{{ $stats['active_properties'] }}</span>
            <span class="stat-label">Propiedades Activas</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-yellow">
            <svg viewBox="0 0 24 24" width="24" height="24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke="currentColor" fill="none" stroke-width="2"/></svg>
        </div>
        <div class="stat-info">
            <span class="stat-value">{{ $stats['new_inquiries'] }}</span>
            <span class="stat-label">Consultas Nuevas</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-purple">
            <svg viewBox="0 0 24 24" width="24" height="24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" fill="none" stroke-width="2"/><circle cx="9" cy="7" r="4" stroke="currentColor" fill="none" stroke-width="2"/></svg>
        </div>
        <div class="stat-info">
            <span class="stat-value">{{ $stats['total_users'] }}</span>
            <span class="stat-label">Usuarios</span>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    {{-- RECENT PROPERTIES --}}
    <div class="dashboard-card">
        <div class="dc-header">
            <h3>Propiedades recientes</h3>
            <a href="{{ route('admin.properties.index') }}" class="dc-link">Ver todas</a>
        </div>
        <div class="table-wrapper">
            <table class="admin-table">
                <thead><tr><th>Propiedad</th><th>Ciudad</th><th>Precio</th><th>Estado</th><th></th></tr></thead>
                <tbody>
                @forelse($recentProperties as $p)
                    <tr>
                        <td>
                            <div class="table-property-cell">
                                <div class="table-thumb" style="background-image:url('{{ $p->cover_url }}')"></div>
                                <span>{{ Str::limit($p->titulo, 35) }}</span>
                            </div>
                        </td>
                        <td>{{ $p->ciudad }}</td>
                        <td>€{{ $p->formatted_price }}</td>
                        <td><span class="status-badge {{ $p->activa ? 'status-active' : 'status-inactive' }}">{{ $p->activa ? 'Activa' : 'Inactiva' }}</span></td>
                        <td><a href="{{ route('admin.properties.edit', $p->id) }}" class="action-btn">Editar</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty-row">No hay propiedades aún</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- RECENT INQUIRIES --}}
    <div class="dashboard-card">
        <div class="dc-header">
            <h3>Consultas recientes</h3>
            <a href="{{ route('admin.inquiries.index') }}" class="dc-link">Ver todas</a>
        </div>
        <div class="table-wrapper">
            <table class="admin-table">
                <thead><tr><th>Propiedad</th><th>Remitente</th><th>Fecha</th><th>Estado</th></tr></thead>
                <tbody>
                @forelse($recentInquiries as $inq)
                    <tr>
                        <td>{{ $inq->propiedad ? Str::limit($inq->propiedad->titulo, 25) : '—' }}</td>
                        <td>{{ $inq->nombre_visitante }}</td>
                        <td>{{ $inq->created_at->diffForHumans() }}</td>
                        <td>
                            @php $cls = ['pendiente'=>'status-pending','leida'=>'status-read','respondida'=>'status-answered'][$inq->estado] ?? ''; @endphp
                            <span class="status-badge {{ $cls }}">
                                {{ ['pendiente'=>'Pendiente','leida'=>'Leída','respondida'=>'Respondida'][$inq->estado] ?? $inq->estado }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="empty-row">No hay consultas aún</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
