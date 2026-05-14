@extends('layouts.app')
@section('title', 'Noticias Inmobiliarias')

@section('content')
<div style="background:#f5f5f7; padding: 80px 0; min-height:100vh;">
    <div class="container" style="max-width: 1400px; margin: 0 auto; padding: 0 24px;">
        <h1 style="font-family: var(--font-display); font-size: 56px; font-weight: 600; letter-spacing: -0.28px; color: #1d1d1f; margin-bottom: 48px; text-align:center;">Noticias del Sector</h1>
        
        @php
            // Separamos los artículos en la sección superior editorial y la cuadrícula inferior
            $topArticles = $articles->take(4);
            $bottomArticles = $articles->slice(4);

            $leftArticles = $topArticles->take(2);
            $rightArticles = $topArticles->slice(2, 2);
        @endphp

        <style>
            .editorial-layout {
                display: grid;
                grid-template-columns: 1fr 500px 1fr;
                gap: 40px;
                align-items: start;
            }
            .article-column {
                display: flex;
                flex-direction: column;
                gap: 32px;
            }
            .article-card {
                text-decoration: none;
                color: inherit;
                background: #ffffff;
                border-radius: 18px;
                overflow: hidden;
                box-shadow: rgba(0,0,0,0.04) 0 8px 30px;
                transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), box-shadow 0.3s;
                display: flex;
                flex-direction: column;
                height: 100%;
            }
            .article-card:hover {
                transform: translateY(-5px);
                box-shadow: rgba(0,0,0,0.1) 0 15px 40px;
            }
            .article-img {
                height: 220px;
                background-color: #e8e8ed;
                background-size: cover;
                background-position: center;
            }
            .article-content {
                padding: 24px;
                flex-grow: 1;
                display: flex;
                flex-direction: column;
            }
            .article-cat {
                font-family: var(--font-body);
                font-size: 12px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 1px;
                color: #0071e3;
                display: block;
                margin-bottom: 8px;
            }
            .article-title {
                font-family: var(--font-display);
                font-size: 22px;
                font-weight: 600;
                line-height: 1.15;
                letter-spacing: -0.1px;
                color: #1d1d1f;
                margin: 0 0 12px 0;
            }
            .article-desc {
                font-family: var(--font-body);
                font-size: 15px;
                line-height: 1.4;
                color: #86868b;
                margin: 0 0 16px 0;
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            .article-meta {
                font-family: var(--font-body);
                font-size: 12px;
                color: #86868b;
                margin-top: auto;
            }
            .center-portrait {
                position: relative;
                border-radius: 24px;
                overflow: hidden;
                height: 100%;
                min-height: 700px;
                background-image: url('https://images.unsplash.com/photo-1512917774080-9991f1c4c750?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=100');
                background-size: cover;
                background-position: center;
                box-shadow: rgba(0,0,0,0.1) 0 20px 50px;
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
            }
            .portrait-overlay {
                background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.6) 50%, transparent 100%);
                padding: 60px 40px 40px;
                color: #ffffff;
            }
            .portrait-title {
                font-family: var(--font-display);
                font-size: 36px;
                font-weight: 600;
                line-height: 1.1;
                letter-spacing: -0.5px;
                margin: 0 0 16px 0;
                color: #ffffff;
            }
            .portrait-desc {
                font-family: var(--font-body);
                font-size: 16px;
                opacity: 0.9;
                margin: 0;
                line-height: 1.5;
                color: #ffffff;
            }

            .bottom-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 40px;
                margin-top: 64px;
            }

            @media (max-width: 1200px) {
                .editorial-layout { grid-template-columns: 1fr 400px 1fr; gap: 32px; }
                .bottom-grid { gap: 32px; }
            }
            @media (max-width: 992px) {
                .editorial-layout { grid-template-columns: 1fr; }
                .center-portrait { height: 500px; min-height: auto; order: -1; margin-bottom: 32px; }
                .bottom-grid { grid-template-columns: repeat(2, 1fr); }
            }
            @media (max-width: 768px) {
                .bottom-grid { grid-template-columns: 1fr; }
            }
        </style>

        @if($topArticles->count() > 0)
        <div class="editorial-layout">
            <!-- Left Column -->
            <div class="article-column">
                @foreach($leftArticles as $article)
                <a href="{{ route('articles.show', $article->slug) }}" class="article-card">
                    <div class="article-img" style="background-image:url('{{ $article->imagen_url ?? '' }}');"></div>
                    <div class="article-content">
                        <span class="article-cat">{{ $article->categoria }}</span>
                        <h2 class="article-title">{{ $article->titulo }}</h2>
                        <p class="article-desc">{{ strip_tags($article->contenido) }}</p>
                        <span class="article-meta">{{ $article->fecha_publicacion->format('d M, Y') }} • Por {{ $article->autor }}</span>
                    </div>
                </a>
                @endforeach
            </div>

            <!-- Center Column: Portrait Image -->
            <div class="center-portrait">
                <div class="portrait-overlay">
                    <span style="font-family: var(--font-body); font-size: 13px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 12px; display:block; opacity: 0.9; color: #ffffff;">Editorial Inmobiliaria</span>
                    <h3 class="portrait-title">Descubre espacios que inspiran vida</h3>
                    <p class="portrait-desc">Nuestra selección exclusiva de propiedades combina diseño contemporáneo con confort absoluto, creando verdaderos hogares para el futuro.</p>
                </div>
            </div>

            <!-- Right Column -->
            <div class="article-column">
                @foreach($rightArticles as $article)
                <a href="{{ route('articles.show', $article->slug) }}" class="article-card">
                    <div class="article-img" style="background-image:url('{{ $article->imagen_url ?? '' }}');"></div>
                    <div class="article-content">
                        <span class="article-cat">{{ $article->categoria }}</span>
                        <h2 class="article-title">{{ $article->titulo }}</h2>
                        <p class="article-desc">{{ strip_tags($article->contenido) }}</p>
                        <span class="article-meta">{{ $article->fecha_publicacion->format('d M, Y') }} • Por {{ $article->autor }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        @if($bottomArticles->count() > 0)
        <div class="bottom-grid">
            @foreach($bottomArticles as $article)
            <a href="{{ route('articles.show', $article->slug) }}" class="article-card">
                <div class="article-img" style="background-image:url('{{ $article->imagen_url ?? '' }}');"></div>
                <div class="article-content">
                    <span class="article-cat">{{ $article->categoria }}</span>
                    <h2 class="article-title">{{ $article->titulo }}</h2>
                    <p class="article-desc">{{ strip_tags($article->contenido) }}</p>
                    <span class="article-meta">{{ $article->fecha_publicacion->format('d M, Y') }} • Por {{ $article->autor }}</span>
                </div>
            </a>
            @endforeach
        </div>
        @endif

        @if($articles->hasPages())
        <div style="margin-top: 64px; text-align:center;">
            {{ $articles->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
