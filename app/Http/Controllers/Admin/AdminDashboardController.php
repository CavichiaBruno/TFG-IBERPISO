<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Inquiry;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_properties'  => Property::count(),
            'active_properties' => Property::active()->count(),
            'new_inquiries'     => Inquiry::pending()->count(),
            'total_users'       => User::where('is_active', true)->count(),
        ];

        $recentProperties = Property::with(['media'])
            ->latest()->take(5)->get();

        $recentInquiries = Inquiry::with(['property'])
            ->latest()->take(5)->get();

        $unreadCount = Inquiry::unread()->count();

        return view('admin.dashboard', compact('stats', 'recentProperties', 'recentInquiries', 'unreadCount'));
    }
}
