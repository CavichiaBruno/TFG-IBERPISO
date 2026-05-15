<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Inquiry;
use App\Models\User;

/**
 * Controlador del Panel de Control del administrador (Dashboard).
 *
 * Muestra un resumen con las estadísticas clave de la plataforma:
 * total de propiedades, consultas pendientes, usuarios activos
 * y las últimas actividades registradas.
 */
class AdminDashboardController extends Controller
{
    /**
     * Muestra la página principal del panel de administración.
     *
     * Recopila estadísticas generales y los registros más recientes
     * de propiedades y consultas para mostrar un resumen rápido al admin.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $stats = [
            'total_properties'  => Property::count(),
            'active_properties' => Property::active()->count(),
            'new_inquiries'     => Inquiry::pending()->count(),
            'total_users'       => User::where('activo', \DB::raw('true'))->count(),
        ];

        $recentProperties = Property::with(['coverImage'])
            ->select('id', 'titulo', 'ciudad', 'precio', 'activa')
            ->latest()->take(5)->get();

        $recentInquiries = Inquiry::with(['property' => function($q) {
                $q->select('id', 'titulo');
            }])
            ->select('id', 'propiedad_id', 'nombre_visitante', 'estado', 'created_at')
            ->latest()->take(5)->get();

        $unreadCount = Inquiry::unread()->count();

        return view('admin.dashboard', compact('stats', 'recentProperties', 'recentInquiries', 'unreadCount'));
    }
}
