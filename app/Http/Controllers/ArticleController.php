<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = \App\Models\Article::where('publicado', \DB::raw('true'))
                        ->latest('fecha_publicacion')
                        ->paginate(12);
        return view('articles.index', compact('articles'));
    }

    public function show($slug)
    {
        $article = \App\Models\Article::where('publicado', \DB::raw('true'))
                        ->where('slug', $slug)
                        ->firstOrFail();
                        
        return view('articles.show', compact('article'));
    }
}
