<?php

namespace App\Http\Controllers;

/**
 * Controlador para la sección pública de Noticias y Artículos del blog de IberPiso.
 *
 * Muestra el listado de noticias publicadas y el detalle de cada artículo
 * accesible desde la navegación principal de la web.
 */
class ArticleController extends Controller
{
    /**
     * Muestra el listado de artículos publicados, ordenados por fecha de publicación.
     *
     * Solo muestra artículos con publicado = true.
     * Los resultados se paginan de 12 en 12.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $articles = \App\Models\Article::where('publicado', \DB::raw('true'))
                        ->latest('fecha_publicacion')
                        ->paginate(12);
        return view('articles.index', compact('articles'));
    }

    /**
     * Muestra el detalle de un artículo concreto buscándolo por su slug.
     *
     * Si el artículo no existe o no está publicado, devuelve un error 404.
     *
     * @param  string $slug Identificador amigable del artículo en la URL
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $article = \App\Models\Article::where('publicado', \DB::raw('true'))
                        ->where('slug', $slug)
                        ->firstOrFail();

        return view('articles.show', compact('article'));
    }
}
