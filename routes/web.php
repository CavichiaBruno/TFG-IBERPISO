<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPropertyController;
use App\Http\Controllers\Admin\AdminMediaController;
use App\Http\Controllers\Admin\AdminInquiryController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AiController;

// --- Public Routes ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/propiedades', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/propiedades/{id}-{slug}', [PropertyController::class, 'show'])->name('properties.show');
Route::post('/propiedades/{id}/contactar', [InquiryController::class, 'store'])->name('inquiries.store');

// --- Auth Routes ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register'])->name('register.post');
});


// --- Authenticated User Routes ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // User Property Management
    Route::get('/mis-publicaciones', [\App\Http\Controllers\UserPropertyController::class, 'index'])->name('user.properties.index');
    Route::get('/publicar', [\App\Http\Controllers\UserPropertyController::class, 'create'])->name('user.properties.create');
    Route::post('/publicar', [\App\Http\Controllers\UserPropertyController::class, 'store'])->name('user.properties.store');
    Route::patch('/mis-publicaciones/{id}/toggle', [\App\Http\Controllers\UserPropertyController::class, 'toggleActive'])->name('user.properties.toggle');
    Route::delete('/mis-publicaciones/{id}', [\App\Http\Controllers\UserPropertyController::class, 'destroy'])->name('user.properties.destroy');

    // AI Integration
    Route::post('/ai/analyze-image', [AiController::class, 'analyzeImage'])->name('ai.analyzeImage');
});

// --- Admin Routes ---
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,agent'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Properties
    Route::get('/propiedades', [AdminPropertyController::class, 'index'])->name('properties.index');
    Route::get('/propiedades/crear', [AdminPropertyController::class, 'create'])->name('properties.create');
    Route::post('/propiedades', [AdminPropertyController::class, 'store'])->name('properties.store');
    Route::get('/propiedades/{id}/editar', [AdminPropertyController::class, 'edit'])->name('properties.edit');
    Route::put('/propiedades/{id}', [AdminPropertyController::class, 'update'])->name('properties.update');
    Route::delete('/propiedades/{id}', [AdminPropertyController::class, 'destroy'])->name('properties.destroy');
    Route::patch('/propiedades/{id}/toggle-active', [AdminPropertyController::class, 'toggleActive'])->name('properties.toggleActive');

    // Media
    Route::post('/propiedades/{id}/media', [AdminMediaController::class, 'store'])->name('media.store');
    Route::delete('/media/{id}', [AdminMediaController::class, 'destroy'])->name('media.destroy');
    Route::patch('/media/{id}/cover', [AdminMediaController::class, 'setCover'])->name('media.cover');

    // Inquiries
    Route::get('/consultas', [AdminInquiryController::class, 'index'])->name('inquiries.index');
    Route::patch('/consultas/{id}/estado', [AdminInquiryController::class, 'updateStatus'])->name('inquiries.updateStatus');
    Route::delete('/consultas/{id}', [AdminInquiryController::class, 'destroy'])->name('inquiries.destroy');

    // Users (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::get('/usuarios', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/usuarios', [AdminUserController::class, 'store'])->name('users.store');
        Route::put('/usuarios/{id}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/usuarios/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        Route::patch('/usuarios/{id}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('users.toggleActive');
    });
});

// --- Scroll & Favorites Routes ---
Route::get('/scroll', [\App\Http\Controllers\ScrollController::class, 'index'])->name('scroll');
Route::post('/scroll/interact', [\App\Http\Controllers\ScrollController::class, 'interact'])->name('scroll.interact');
Route::get('/guardados', [\App\Http\Controllers\ScrollController::class, 'saved'])->name('saved');
Route::delete('/guardados/{property_id}', [\App\Http\Controllers\ScrollController::class, 'removeFavorite'])->name('favorites.remove');

