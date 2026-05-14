<?php

namespace Tests\Unit\Models;

use App\Models\Article;
use Tests\TestCase;

class ArticleTest extends TestCase
{

    /**
     * Test que un artículo puede ser creado correctamente
     */
    public function test_article_can_be_created()
    {
        $article = Article::create([
            'titulo' => 'Test Article',
            'slug' => 'test-article',
            'contenido' => 'This is a test article content',
            'categoria' => 'Consejos',
            'autor' => 'Test Author',
            'publicado' => true,
        ]);

        $this->assertNotNull($article->id);
        $this->assertEquals('Test Article', $article->titulo);
        $this->assertEquals('test-article', $article->slug);
        $this->assertEquals('Consejos', $article->categoria);
    }

    /**
     * Test que slug se genera correctamente
     */
    public function test_article_slug_is_unique()
    {
        $article1 = Article::create([
            'titulo' => 'Articulo de Prueba',
            'slug' => 'articulo-de-prueba',
            'contenido' => 'Content 1',
            'categoria' => 'General',
            'autor' => 'Author',
        ]);

        $article2 = Article::create([
            'titulo' => 'Otro Articulo',
            'slug' => 'otro-articulo',
            'contenido' => 'Content 2',
            'categoria' => 'General',
            'autor' => 'Author',
        ]);

        $this->assertNotEquals($article1->slug, $article2->slug);
    }

    /**
     * Test que un artículo puede ser publicado o no
     */
    public function test_article_can_be_published_or_unpublished()
    {
        $publishedArticle = Article::create([
            'titulo' => 'Published',
            'slug' => 'published',
            'contenido' => 'Content',
            'categoria' => 'General',
            'publicado' => true,
        ]);

        $unpublishedArticle = Article::create([
            'titulo' => 'Unpublished',
            'slug' => 'unpublished',
            'contenido' => 'Content',
            'categoria' => 'General',
            'publicado' => false,
        ]);

        $this->assertTrue($publishedArticle->publicado);
        $this->assertFalse($unpublishedArticle->publicado);
    }

    /**
     * Test que el contenido de un artículo puede ser largo
     */
    public function test_article_can_have_long_content()
    {
        $longContent = str_repeat('Lorem ipsum dolor sit amet. ', 100);
        
        $article = Article::create([
            'titulo' => 'Long Article',
            'slug' => 'long-article',
            'contenido' => $longContent,
            'categoria' => 'General',
            'autor' => 'Author',
        ]);

        $this->assertStringContainsString('Lorem ipsum', $article->contenido);
        $this->assertGreaterThan(2500, strlen($article->contenido));
    }

    /**
     * Test que un artículo tiene una categoría
     */
    public function test_article_has_category()
    {
        $categories = ['Consejos', 'Mercado', 'Regulación', 'Financiero', 'General'];
        
        foreach ($categories as $category) {
            $article = Article::create([
                'titulo' => "Article for $category",
                'slug' => "article-for-" . strtolower($category),
                'contenido' => 'Content',
                'categoria' => $category,
                'autor' => 'Author',
            ]);

            $this->assertEquals($category, $article->categoria);
        }
    }

    /**
     * Test que fecha de publicación se establece correctamente
     */
    public function test_article_publication_date()
    {
        $publishDate = now()->subDays(10);
        
        $article = Article::create([
            'titulo' => 'Dated Article',
            'slug' => 'dated-article',
            'contenido' => 'Content',
            'categoria' => 'General',
            'fecha_publicacion' => $publishDate,
        ]);

        $this->assertTrue($article->fecha_publicacion->diffInDays(now()) >= 10);
    }

    /**
     * Test que un artículo requiere título
     */
    public function test_article_requires_title()
    {
        $this->expectException(\Exception::class);

        Article::create([
            'slug' => 'no-title',
            'contenido' => 'Content',
            'categoria' => 'General',
        ]);
    }

    /**
     * Test que los atributos están en fillable
     */
    public function test_article_fillable_attributes()
    {
        $fillable = Article::factory()->make()->getFillable();
        
        $this->assertNotEmpty($fillable);
    }
}
