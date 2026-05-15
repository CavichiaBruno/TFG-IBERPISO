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

// --- Rutas Públicas ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/load-featured', [HomeController::class, 'loadFeatured'])->name('home.loadFeatured');
Route::get('/propiedades', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/propiedades/{id}-{slug}', [PropertyController::class, 'show'])->name('properties.show');
Route::get('/propiedades/{id}/certificado', [PropertyController::class, 'downloadCertificate'])->name('properties.download_certificate');
Route::post('/propiedades/{id}/contactar', [InquiryController::class, 'store'])->name('inquiries.store');

// --- Chatbot ---
Route::get('/chatbot', function() {
    return redirect()->route('home', ['chat' => 1]);
})->name('chatbot.index');
Route::post('/chatbot/chat', [\App\Http\Controllers\ChatbotController::class, 'chat'])->name('chatbot.chat');

// --- Rutas de Autenticación ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register'])->name('register.post');
});

// --- Noticias (Articles) ---
Route::get('/noticias', [\App\Http\Controllers\ArticleController::class, 'index'])->name('articles.index');
Route::get('/noticias/{slug}', [\App\Http\Controllers\ArticleController::class, 'show'])->name('articles.show');


// --- Rutas de Usuarios Autenticados ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Gestión de propiedades del usuario
    Route::get('/mis-publicaciones', [\App\Http\Controllers\UserPropertyController::class, 'index'])->name('user.properties.index');
    Route::get('/mis-publicaciones/{id}/editar', [\App\Http\Controllers\UserPropertyController::class, 'edit'])->name('user.properties.edit');
    Route::put('/mis-publicaciones/{id}', [\App\Http\Controllers\UserPropertyController::class, 'update'])->name('user.properties.update');
    Route::patch('/mis-media/{id}/cover', [\App\Http\Controllers\UserPropertyController::class, 'setCover'])->name('user.media.cover');
    Route::delete('/mis-media/{id}', [\App\Http\Controllers\UserPropertyController::class, 'deleteMedia'])->name('user.media.destroy');
    Route::get('/mis-consultas', [\App\Http\Controllers\UserPropertyController::class, 'inquiries'])->name('user.inquiries');
    Route::post('/mis-consultas/{id}/leer', [\App\Http\Controllers\UserPropertyController::class, 'markAsRead'])->name('user.inquiries.read');
    Route::get('/publicar', [\App\Http\Controllers\UserPropertyController::class, 'create'])->name('user.properties.create');
    Route::post('/publicar', [\App\Http\Controllers\UserPropertyController::class, 'store'])->name('user.properties.store');
    Route::patch('/mis-publicaciones/{id}/toggle', [\App\Http\Controllers\UserPropertyController::class, 'toggleActive'])->name('user.properties.toggle');
    Route::delete('/mis-publicaciones/{id}', [\App\Http\Controllers\UserPropertyController::class, 'destroy'])->name('user.properties.destroy');

    // Integración con IA
    Route::post('/ai/analyze-image', [AiController::class, 'analyzeImage'])->name('user.ai.analyzeImage');

    // --- Rutas de Scroll y Favoritos ---
    Route::get('/scroll', [\App\Http\Controllers\ScrollController::class, 'index'])->name('scroll');
    Route::post('/scroll/interact', [\App\Http\Controllers\ScrollController::class, 'interact'])->name('scroll.interact');
    Route::get('/guardados', [\App\Http\Controllers\ScrollController::class, 'saved'])->name('saved');
    Route::delete('/guardados/{property_id}', [\App\Http\Controllers\ScrollController::class, 'removeFavorite'])->name('favorites.remove');
});

// --- Rutas de Administración ---
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Propiedades
    Route::get('/propiedades', [AdminPropertyController::class, 'index'])->name('properties.index');
    Route::get('/propiedades/crear', [AdminPropertyController::class, 'create'])->name('properties.create');
    Route::post('/propiedades', [AdminPropertyController::class, 'store'])->name('properties.store');
    Route::get('/propiedades/{id}/editar', [AdminPropertyController::class, 'edit'])->name('properties.edit');
    Route::put('/propiedades/{id}', [AdminPropertyController::class, 'update'])->name('properties.update');
    Route::delete('/propiedades/{id}', [AdminPropertyController::class, 'destroy'])->name('properties.destroy');
    Route::patch('/propiedades/{id}/toggle-active', [AdminPropertyController::class, 'toggleActive'])->name('properties.toggleActive');

    // Medios (Imágenes)
    Route::post('/propiedades/{id}/media', [AdminMediaController::class, 'store'])->name('media.store');
    Route::delete('/media/{id}', [AdminMediaController::class, 'destroy'])->name('media.destroy');
    Route::patch('/media/{id}/cover', [AdminMediaController::class, 'setCover'])->name('media.cover');

    // Consultas (Inquiries)
    Route::get('/consultas', [AdminInquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/consultas/crear', [AdminInquiryController::class, 'create'])->name('inquiries.create');
    Route::post('/consultas', [AdminInquiryController::class, 'store'])->name('inquiries.store');
    Route::get('/consultas/{id}/editar', [AdminInquiryController::class, 'edit'])->name('inquiries.edit');
    Route::put('/consultas/{id}', [AdminInquiryController::class, 'update'])->name('inquiries.update');
    Route::patch('/consultas/{id}/estado', [AdminInquiryController::class, 'updateStatus'])->name('inquiries.status');
    Route::delete('/consultas/{id}', [AdminInquiryController::class, 'destroy'])->name('inquiries.destroy');

    // Interacciones (Interactions)
    Route::resource('interacciones', \App\Http\Controllers\Admin\AdminInteractionController::class)->names([
        'index' => 'interactions.index',
        'create' => 'interactions.create',
        'store' => 'interactions.store',
        'edit' => 'interactions.edit',
        'update' => 'interactions.update',
        'destroy' => 'interactions.destroy',
        'show' => 'interactions.show',
    ]);

    // Noticias (Articles)
    Route::resource('noticias', \App\Http\Controllers\Admin\AdminArticleController::class)->names([
        'index' => 'articles.index',
        'create' => 'articles.create',
        'store' => 'articles.store',
        'edit' => 'articles.edit',
        'update' => 'articles.update',
        'destroy' => 'articles.destroy',
        'show' => 'articles.show',
    ]);

    // Usuarios
    Route::get('/usuarios', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/usuarios/crear', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/usuarios', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/usuarios/{id}/editar', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/usuarios/{id}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/usuarios/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/usuarios/{id}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('users.toggleActive');

    // IA
    Route::post('/ai/analyze-image', [AiController::class, 'analyzeImage'])->name('ai.analyzeImage');
});



