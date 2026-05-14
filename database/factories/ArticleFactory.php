<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $categories = ['Consejos', 'Mercado', 'Regulación', 'Financiero', 'General'];
        
        $title = fake()->sentence();
        
        return [
            'titulo' => $title,
            'slug' => Str::slug($title),
            'contenido' => fake()->paragraphs(5, true),
            'categoria' => $categories[array_rand($categories)],
            'autor' => 'Admin',
            'imagen_url' => fake()->imageUrl(),
            'publicado' => true,
            'fecha_publicacion' => fake()->dateTimeThisMonth(),
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'publicado' => false,
        ]);
    }
}
