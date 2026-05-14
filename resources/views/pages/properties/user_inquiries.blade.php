@extends('layouts.app')
@section('title', 'Mis Consultas')

@push('styles')
<style>
    .inquiries-page {
        background-color: #f5f5f7;
        min-height: 100vh;
        padding: 80px 0;
    }
    .container-inq {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 20px;
    }
    .inq-header {
        margin-bottom: 40px;
    }
    .inq-title {
        font-family: var(--font-display);
        font-size: 40px;
        font-weight: 600;
        letter-spacing: -0.02em;
        color: #1d1d1f;
        margin: 0;
    }
    .inq-card {
        background: #fff;
        border-radius: 20px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        transition: all 0.3s var(--ease);
        border: 2px solid transparent;
        position: relative;
    }
    .inq-card.unread {
        border-color: rgba(0, 113, 227, 0.1);
        background: #fbfdff;
    }
    .unread-dot {
        position: absolute;
        top: 24px;
        right: 24px;
        width: 10px;
        height: 10px;
        background: #0071e3;
        border-radius: 50%;
    }
    .inq-meta {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }
    .sender-info h3 {
        font-size: 18px;
        font-weight: 600;
        color: #1d1d1f;
        margin: 0 0 4px 0;
    }
    .sender-contact {
        font-size: 14px;
        color: #86868b;
        display: flex;
        gap: 12px;
    }
    .inq-date {
        font-size: 13px;
        color: #86868b;
    }
    .inq-property {
        display: inline-block;
        padding: 6px 12px;
        background: #f5f5f7;
        border-radius: 8px;
        font-size: 13px;
        color: #1d1d1f;
        text-decoration: none;
        margin-bottom: 16px;
        font-weight: 500;
    }
    .inq-property:hover {
        background: #e8e8ed;
    }
    .inq-message {
        font-size: 16px;
        line-height: 1.5;
        color: #424245;
        background: #f9f9f9;
        padding: 16px;
        border-radius: 12px;
        white-space: pre-line;
    }
    .empty-state {
        text-align: center;
        padding: 100px 20px;
    }
    .btn-read {
        margin-top: 16px;
        font-size: 13px;
        color: #0071e3;
        background: none;
        border: none;
        cursor: pointer;
        font-weight: 500;
        padding: 0;
    }
    .btn-read:hover {
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
<div class="inquiries-page">
    <div class="container-inq">
        <div class="inq-header">
            <h1 class="inq-title">Buzón de <span class="marker-white">Mensajes</span></h1>
            <p style="color: #86868b; margin-top: 8px;">Consultas recibidas por tus inmuebles publicados</p>
        </div>

        @forelse($inquiries as $inq)
            <div class="inq-card {{ !$inq->leida ? 'unread' : '' }}" id="inq-{{ $inq->id }}">
                @if(!$inq->leida)
                    <div class="unread-dot"></div>
                @endif
                
                <div class="inq-meta">
                    <div class="sender-info">
                        <h3>{{ $inq->nombre_visitante }}</h3>
                        <div class="sender-contact">
                            <span>{{ $inq->correo_visitante }}</span>
                            @if($inq->telefono_visitante)
                                <span>•</span>
                                <span>{{ $inq->telefono_visitante }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="inq-date">{{ $inq->created_at->diffForHumans() }}</div>
                </div>

                @if($inq->property)
                    <a href="{{ route('properties.show', [$inq->property->id, $inq->property->slug]) }}" class="inq-property">
                        Propiedad: {{ $inq->property->titulo }}
                    </a>
                @endif

                <div class="inq-message">{{ $inq->mensaje }}</div>

                @if(!$inq->leida)
                    <button class="btn-read" onclick="markAsRead({{ $inq->id }})">Marcar como leído</button>
                @endif
            </div>
        @empty
            <div class="empty-state">
                <div style="font-size: 64px; opacity: 0.1; margin-bottom: 20px;">
                    <svg viewBox="0 0 24 24" width="80" height="80" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </div>
                <h2 style="font-family: var(--font-display); font-weight: 600;">No tienes mensajes</h2>
                <p style="color: #86868b; margin-top: 8px;">Cuando alguien se interese por tus propiedades, aparecerá aquí.</p>
            </div>
        @endforelse

        <div style="margin-top: 40px;">
            {{ $inquiries->links('components.pagination') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function markAsRead(id) {
    fetch(`/mis-consultas/${id}/leer`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const card = document.getElementById(`inq-${id}`);
            card.classList.remove('unread');
            const dot = card.querySelector('.unread-dot');
            if (dot) dot.remove();
            const btn = card.querySelector('.btn-read');
            if (btn) btn.remove();
            
            // Opcional: Actualizar contador del header
            const badge = document.querySelector('.notification-btn span');
            if (badge) {
                let count = parseInt(badge.innerText);
                count--;
                if (count <= 0) {
                    badge.remove();
                } else {
                    badge.innerText = count;
                }
            }
        }
    });
}
</script>
@endpush
