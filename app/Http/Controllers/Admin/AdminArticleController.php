<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Controlador para la gestión de Noticias y Artículos desde el panel de administración.
 *
 * Permite al administrador crear, editar, publicar y eliminar los artículos
 * del blog que aparecen en la sección de noticias de IberPiso.
 */
class AdminArticleController extends Controller
{
    /**
     * Muestra el listado de todos los artículos, ordenados por fecha de publicación.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $articles = \App\Models\Article::latest('fecha_publicacion')->paginate(20);
        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Muestra el formulario para crear un nuevo artículo.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.articles.create');
    }

    /**
     * Guarda un nuevo artículo en la base de datos.
     *
     * Si no se especifica autor, se usa "IberPiso" por defecto.
     * Si no se especifica categoría, se usa "Noticias" por defecto.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Muestra el formulario de edición de un artículo existente.
     *
     * @param  int $id ID del artículo
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $article = \App\Models\Article::findOrFail($id);
        return view('admin.articles.edit', compact('article'));
    }

    /**
     * Actualiza los datos de un artículo existente.
     *
     * El slug debe seguir siendo único, pero se excluye el artículo actual
     * para que pueda guardar sin cambiar el slug.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id ID del artículo a actualizar
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Elimina permanentemente un artículo de la base de datos.
     *
     * @param  int $id ID del artículo
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        \App\Models\Article::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
