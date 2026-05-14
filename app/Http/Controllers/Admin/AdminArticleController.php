<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminArticleController extends Controller
{
    public function index()
    {
        $articles = \App\Models\Article::latest('fecha_publicacion')->paginate(20);
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articulos',
            'contenido' => 'required|string',
            'autor' => 'nullable|string|max:255',
            'imagen_url' => 'nullable|string|max:255',
            'categoria' => 'nullable|string|max:100',
        ]);

        if (empty($validated['autor'])) $validated['autor'] = 'IberPiso';
        if (empty($validated['categoria'])) $validated['categoria'] = 'Noticias';
        $validated['publicado'] = $request->has('publicado') ? \DB::raw('true') : \DB::raw('false');

        \App\Models\Article::create($validated);
        return redirect()->route('admin.articles.index')->with('success', 'Noticia creada correctamente.');
    }

    public function edit(int $id)
    {
        $article = \App\Models\Article::findOrFail($id);
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, int $id)
    {
        $article = \App\Models\Article::findOrFail($id);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articulos,slug,' . $id,
            'contenido' => 'required|string',
            'autor' => 'nullable|string|max:255',
            'imagen_url' => 'nullable|string|max:255',
            'categoria' => 'nullable|string|max:100',
        ]);

        if (empty($validated['autor'])) $validated['autor'] = 'IberPiso';
        if (empty($validated['categoria'])) $validated['categoria'] = 'Noticias';
        $validated['publicado'] = $request->has('publicado') ? \DB::raw('true') : \DB::raw('false');

        $article->update($validated);
        return redirect()->route('admin.articles.index')->with('success', 'Noticia actualizada correctamente.');
    }

    public function destroy(int $id)
    {
        \App\Models\Article::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
