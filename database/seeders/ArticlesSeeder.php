<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticlesSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'titulo' => 'Guía completa para comprar tu primera vivienda',
                'contenido' => 'Consejos y recomendaciones para realizar tu primera compra de inmueble. Desde la búsqueda hasta la firma de la escritura.',
                'categoria' => 'Consejos',
                'autor' => 'Admin',
                'imagen_url' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800',
            ],
            [
                'titulo' => 'Tendencias inmobiliarias 2026',
                'contenido' => 'Análisis de las principales tendencias en el mercado inmobiliario español durante 2026.',
                'categoria' => 'Mercado',
                'autor' => 'Admin',
                'imagen_url' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800',
            ],
            [
                'titulo' => 'Cómo mejorar el valor de tu propiedad',
                'contenido' => 'Reformas y mejoras que pueden aumentar significativamente el valor de tu inmueble en el mercado.',
                'categoria' => 'Consejos',
                'autor' => 'Admin',
                'imagen_url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
            ],
            [
                'titulo' => 'Certificación energética: todo lo que debes saber',
                'contenido' => 'Explicación detallada sobre la certificación energética de edificios, su importancia y clasificación.',
                'categoria' => 'Regulación',
                'autor' => 'Admin',
                'imagen_url' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=800',
            ],
            [
                'titulo' => 'Arrendar vs Comprar: ventajas y desventajas',
                'contenido' => 'Análisis comparativo entre comprar y alquilar una vivienda considerando factores económicos y personales.',
                'categoria' => 'Consejos',
                'autor' => 'Admin',
                'imagen_url' => 'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=800',
            ],
            [
                'titulo' => 'Financiación hipotecaria: opciones disponibles',
                'contenido' => 'Guía sobre las diferentes opciones de financiamiento para la compra de vivienda en España.',
                'categoria' => 'Financiero',
                'autor' => 'Admin',
                'imagen_url' => 'https://images.unsplash.com/photo-1576091160550-112173f7f477?w=800',
            ],
            [
                'titulo' => 'Zonificación urbana y su impacto en propiedades',
                'contenido' => 'Entender cómo la zonificación urbana afecta al valor y características de las propiedades inmobiliarias.',
                'categoria' => 'Mercado',
                'autor' => 'Admin',
                'imagen_url' => 'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=800',
            ],
            [
                'titulo' => 'Impuestos en transacciones inmobiliarias',
                'contenido' => 'Resumen de los principales impuestos a considerar en la compra y venta de propiedades en España.',
                'categoria' => 'Regulación',
                'autor' => 'Admin',
                'imagen_url' => 'https://images.unsplash.com/photo-1554224311-beee415c15cb?w=800',
            ],
            [
                'titulo' => 'Protección al consumidor en inmuebles',
                'contenido' => 'Derechos y protecciones de los consumidores en operaciones inmobiliarias según la legislación vigente.',
                'categoria' => 'Regulación',
                'autor' => 'Admin',
                'imagen_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800',
            ],
            [
                'titulo' => 'Mantenimiento preventivo de viviendas',
                'contenido' => 'Consejos sobre el mantenimiento regular de tu hogar para evitar problemas costosos a futuro.',
                'categoria' => 'Consejos',
                'autor' => 'Admin',
                'imagen_url' => 'https://images.unsplash.com/photo-1552321554-5fefe8c9ef14?w=800',
            ],
        ];

        foreach ($articles as $article) {
            Article::create([
                'titulo' => $article['titulo'],
                'slug' => Str::slug($article['titulo']),
                'contenido' => $article['contenido'],
                'categoria' => $article['categoria'],
                'autor' => $article['autor'],
                'imagen_url' => $article['imagen_url'],
                'publicado' => true,
                'fecha_publicacion' => now()->subDays(rand(0, 30)),
            ]);
        }
    }
}
