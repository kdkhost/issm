<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MaintenanceIpController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\OdsController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicGalleryController;
use App\Http\Controllers\PublicNewsController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\PublicProjectController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/galeria', [PublicGalleryController::class, 'index'])->name('gallery.index');
Route::get('/noticias', [PublicNewsController::class, 'index'])->name('news.index');
Route::get('/noticias/{slug}', [PublicNewsController::class, 'show'])->name('news.show');
Route::get('/projetos', [PublicProjectController::class, 'index'])->name('projects.index');
Route::get('/projetos/{slug}', [PublicProjectController::class, 'show'])->name('projects.show');
Route::post('/contato', [ContactFormController::class, 'store'])->name('contact.store');
Route::get('/pagina/{slug}', [PublicPageController::class, 'show'])->name('pages.show');

// Auth Routes
Auth::routes(['register' => false, 'verify' => false, 'confirm' => false]);

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/configuracoes', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/configuracoes', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/configuracoes/upload-image', [SettingsController::class, 'uploadImage'])->name('settings.upload-image');
    Route::resource('banners', BannerController::class);
    Route::post('banners/{banner}/toggle', [BannerController::class, 'toggleActive'])->name('banners.toggle');
    Route::resource('noticias', NewsController::class)->parameters(['noticias' => 'news']);
    Route::resource('projetos', ProjectController::class)->parameters(['projetos' => 'project']);
    Route::resource('equipe', TeamController::class)->parameters(['equipe' => 'team']);
    Route::resource('parceiros', PartnerController::class)->parameters(['parceiros' => 'partner']);
    Route::resource('galeria', GalleryController::class)->parameters(['galeria' => 'gallery']);
    Route::resource('contatos', ContactController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::resource('paginas', PageController::class)->parameters(['paginas' => 'page']);
    Route::resource('ods', OdsController::class)->only(['index', 'edit', 'update']);
    Route::resource('ips-manutencao', MaintenanceIpController::class)->parameters(['ips-manutencao' => 'maintenanceIp']);
    Route::post('ips-manutencao/add-current', [MaintenanceIpController::class, 'addCurrentIp'])->name('ips-manutencao.add-current');
    // Profile
    Route::get('perfil', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('perfil/senha', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Analytics
    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
});
