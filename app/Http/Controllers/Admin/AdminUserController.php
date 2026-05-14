<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Controlador para la gestión de usuarios desde el panel administrativo
 */
class AdminUserController extends Controller
{
    /**
     * Lista los usuarios con buscador y paginación
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Buscador por nombre o email
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%{$q}%")
                    ->orWhere('correo', 'like', "%{$q}%");
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();
        $unreadCount = \App\Models\Inquiry::unread()->count();

        return view('admin.users.index', compact('users', 'unreadCount'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Crea un nuevo usuario validando los datos recibidos
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'     => 'required|string|max:100',
            'correo'    => 'required|email|max:150|unique:usuarios',
            'telefono'    => 'nullable|string|max:20',
            'rol'     => 'required|in:admin,usuario',
            'contrasena' => ['required', Password::min(8)],
        ]);

        User::create([
            'nombre'     => $request->nombre,
            'correo'    => $request->correo,
            'telefono'    => $request->telefono,
            'rol'     => $request->rol,
            'contrasena' => Hash::make($request->contrasena),
            'activo' => \DB::raw('true'),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado correctamente');
    }

    /**
     * Muestra el formulario para editar un usuario existente
     */
    public function edit(int $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Actualiza un usuario existente permitiendo cambiar la clave si se proporciona
     */
    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nombre'     => 'required|string|max:100',
            'correo'    => 'required|email|max:150|unique:usuarios,correo,' . $user->id,
            'telefono'    => 'nullable|string|max:20',
            'rol'     => 'required|in:admin,usuario',
            'contrasena' => ['nullable', Password::min(8)],
        ]);

        $data = $request->only('nombre', 'correo', 'telefono', 'rol');
        if ($request->filled('contrasena')) {
            $data['contrasena'] = Hash::make($request->contrasena);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Elimina un usuario con validaciones de seguridad
     */
    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        // Impedimos que un usuario se borre a sí mismo
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'No puedes eliminar tu propia cuenta.'], 403);
        }

        // Evitamos dejar el sistema sin administradores activos
        $adminCount = User::where('rol', 'admin')->where('activo', \DB::raw('true'))->count();
        if ($user->rol === 'admin' && $adminCount <= 1) {
            return response()->json(['error' => 'No puedes eliminar el último administrador.'], 403);
        }

        $user->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Cambia el estado de activación de un usuario
     */
    public function toggleActive(int $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'No puedes desactivar tu propia cuenta.'], 403);
        }

        $user->update(['activo' => $user->activo ? \DB::raw('false') : \DB::raw('true')]);
        return response()->json(['success' => true, 'activo' => $user->activo]);
    }
}
