<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Property::with(['media'])
            ->active()->featured()
            ->latest()->take(6)->get();

        $stats = [
            'properties' => Property::active()->count(),
            'cities'     => Property::active()->distinct('city')->count('city'),
            'users'      => User::where('is_active', true)->count(),
        ];

        return view('pages.home', compact('featured', 'stats'));
    }
}
