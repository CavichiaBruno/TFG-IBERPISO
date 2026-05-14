<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('pages.auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('correo', 'password');
        $remember    = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()->withErrors(['correo' => 'Las credenciales no son correctas.'])->withInput();
        }

        $user = Auth::user();

        if (!$user->activo) {
            Auth::logout();
            return back()->withErrors(['correo' => 'Tu cuenta está desactivada.']);
        }

        $request->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('home'));
    }

    public function showRegister()
    {
        return view('pages.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre'     => 'required|string|max:100',
            'correo'     => 'required|email|max:150|unique:usuarios',
            'telefono'   => 'nullable|string|max:20',
            'contrasena' => 'required|string|min:8|confirmed',
        ], [
            'nombre.required'     => 'El nombre es obligatorio.',
            'correo.required'     => 'El correo electrónico es obligatorio.',
            'correo.email'        => 'El formato del correo no es válido.',
            'correo.unique'       => 'Este correo ya está registrado.',
            'contrasena.required' => 'La contraseña es obligatoria.',
            'contrasena.min'      => 'La contraseña debe tener al menos 8 caracteres.',
            'contrasena.confirmed'=> 'Las contraseñas no coinciden.',
        ]);

        $user = User::create([
            'nombre'     => $request->nombre,
            'correo'    => $request->correo,
            'telefono'    => $request->telefono,
            'contrasena' => Hash::make($request->contrasena),
            'rol'     => 'usuario',
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', '\u00a1Bienvenido a IberPiso!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
