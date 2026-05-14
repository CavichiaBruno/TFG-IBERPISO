@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1>Buenos días, {{ explode(' ', auth()->user()->nombre)[0] }}</h1>
</div>

{{-- STATS GRID --}}
<div class="stats-grid">
    <div class="stat-item">
        <span class="stat-label">Total Propiedades</span>
        <span class="stat-value">{{ $stats['total_properties'] }}</span>
    </div>
    <div class="stat-item">
        <span class="stat-label">Activas en Portal</span>
        <span class="stat-value">{{ $stats['active_properties'] }}</span>
    </div>
    <div class="stat-item">
        <span class="stat-label">Nuevas Consultas</span>
        <span class="stat-value" style="color: var(--admin-accent);">{{ $stats['new_inquiries'] }}</span>
    </div>
    <div class="stat-item">
        <span class="stat-label">Usuarios Totales</span>
        <span class="stat-value">{{ $stats['total_users'] }}</span>
    </div>
</div>

<div class="admin-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(480px, 1fr)); gap: 24px;">
    {{-- RECENT PROPERTIES --}}
    <div class="admin-card">
        <div class="card-header">
            <h3>Propiedades recientes</h3>
            <a href="{{ route('admin.properties.index') }}" class="btn-admin btn-admin-outline" style="padding: 4px 12px; font-size: 12px;">Ver todas</a>
        </div>
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Propiedad</th>
                        <th>Precio</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($recentProperties as $p)
                    <tr>
                        <td>
                            <div class="prop-cell">
                                <div class="prop-thumb">
                                    <img src="{{ $p->cover_url }}" alt="" loading="lazy" width="44" height="44">
                                </div>
                                <div class="prop-info">
                                    <span class="prop-title">{{ Str::limit($p->titulo, 30) }}</span>
                                    <span class="prop-meta">{{ $p->ciudad }}</span>
                                </div>
                            </div>
                        </td>
                        <td><span style="font-weight: 600;">€{{ $p->formatted_price }}</span></td>
                        <td>
                            <span class="badge {{ $p->activa ? 'badge-success' : 'badge-gray' }}">
                                {{ $p->activa ? 'Activa' : 'Inactiva' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" style="padding: 48px; text-align: center; color: var(--admin-text-secondary);">No hay datos recientes</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- RECENT INQUIRIES --}}
    <div class="admin-card">
        <div class="card-header">
            <h3>Consultas recientes</h3>
            <a href="{{ route('admin.inquiries.index') }}" class="btn-admin btn-admin-outline" style="padding: 4px 12px; font-size: 12px;">Ver todas</a>
        </div>
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Remitente</th>
                        <th>Propiedad</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($recentInquiries as $inq)
                    <tr>
                        <td>
                            <div class="prop-info">
                                <span class="prop-title">{{ $inq->nombre_visitante }}</span>
                                <span class="prop-meta">{{ $inq->created_at->diffForHumans() }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="prop-title" style="font-size: 13px;">{{ $inq->propiedad ? Str::limit($inq->propiedad->titulo, 25) : '—' }}</span>
                        </td>
                        <td>
                            @php $cls = ['pendiente'=>'badge-warning','leida'=>'badge-info','respondida'=>'badge-success'][$inq->estado] ?? 'badge-gray'; @endphp
                            <span class="badge {{ $cls }}">
                                {{ ucfirst($inq->estado) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" style="padding: 48px; text-align: center; color: var(--admin-text-secondary);">No hay consultas nuevas</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
