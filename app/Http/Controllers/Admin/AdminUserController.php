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
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();
        $unreadCount = \App\Models\Inquiry::unread()->count();

        return view('admin.users.index', compact('users', 'unreadCount'));
    }

    /**
     * Crea un nuevo usuario validando los datos recibidos
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:150|unique:users',
            'phone'    => 'nullable|string|max:20',
            'role'     => 'required|in:admin,agent,user',
            'password' => ['required', Password::min(8)],
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'role'     => $request->role,
            'password' => Hash::make($request->password), // Encriptamos la clave
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Actualiza un usuario existente permitiendo cambiar la clave si se proporciona
     */
    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:150|unique:users,email,' . $user->id,
            'phone'    => 'nullable|string|max:20',
            'role'     => 'required|in:admin,agent,user',
            'password' => ['nullable', Password::min(8)],
        ]);

        $data = $request->only('name', 'email', 'phone', 'role');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json(['success' => true]);
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
        $adminCount = User::where('role', 'admin')->where('is_active', true)->count();
        if ($user->role === 'admin' && $adminCount <= 1) {
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

        $user->update(['is_active' => !$user->is_active]);
        return response()->json(['success' => true, 'is_active' => $user->is_active]);
    }
}
