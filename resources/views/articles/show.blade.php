@extends('layouts.app')
@section('title', $article->titulo . ' - Noticias IberPiso')

@section('content')
<div style="background:#ffffff; min-height:100vh;">
    @if($article->imagen_url)
    <div style="width: 100%; height: 50vh; background-image: url('{{ $article->imagen_url }}'); background-size: cover; background-position: center; position:relative;">
        <div style="position:absolute; bottom:0; left:0; width:100%; height:50%; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);"></div>
    </div>
    @endif
    
    <div class="container" style="max-width: 800px; margin: 0 auto; padding: {{ $article->imagen_url ? '64px' : '120px' }} 24px 80px;">
        <div style="margin-bottom: 32px; text-align: center;">
            <span style="font-family: var(--font-body); font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #0071e3; display:block; margin-bottom: 12px;">{{ $article->categoria }}</span>
            <h1 style="font-family: var(--font-display); font-size: 48px; font-weight: 700; line-height: 1.05; letter-spacing: -0.5px; color: #1d1d1f; margin: 0 0 24px 0;">{{ $article->titulo }}</h1>
            <p style="font-family: var(--font-body); font-size: 17px; color: #86868b; letter-spacing: -0.374px; margin:0;">
                Por <strong>{{ $article->autor }}</strong> • Publicado el {{ $article->fecha_publicacion->format('d de M, Y') }}
            </p>
        </div>

        <div style="font-family: var(--font-body); font-size: 19px; line-height: 1.6; color: #1d1d1f; letter-spacing: -0.2px;">
            {!! nl2br(e($article->contenido)) !!}
        </div>

        <div style="margin-top: 64px; padding-top: 32px; border-top: 1px solid rgba(0,0,0,0.1); text-align:center;">
            <a href="{{ route('articles.index') }}" style="display:inline-block; font-family:var(--font-body); font-size:17px; color:#0071e3; text-decoration:none; font-weight:600;">
                ← Volver a Noticias
            </a>
        </div>
    </div>
</div>
@endsection
