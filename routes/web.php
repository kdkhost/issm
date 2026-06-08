<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\CronController;
use App\Http\Controllers\Admin\CmsPublicPageController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FrontendMenuController;
use App\Http\Controllers\Admin\GoogleDriveFolderController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MaintenanceIpController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\OdsController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TransparencyCategoryController;
use App\Http\Controllers\Admin\TransparencyController;
use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicAboutController;
use App\Http\Controllers\PublicContactController;
use App\Http\Controllers\PublicGalleryController;
use App\Http\Controllers\PublicNewsController;
use App\Http\Controllers\PublicOdsController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\PublicProjectController;
use App\Http\Controllers\PublicTransparencyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sobre', [PublicAboutController::class, 'index'])->name('about.index');
Route::get('/ods', [PublicOdsController::class, 'index'])->name('ods.index');
Route::get('/galeria', [PublicGalleryController::class, 'index'])->name('gallery.index');
Route::get('/noticias', [PublicNewsController::class, 'index'])->name('news.index');
Route::get('/noticias/{slug}', [PublicNewsController::class, 'show'])->name('news.show');
Route::get('/projetos', [PublicProjectController::class, 'index'])->name('projects.index');
Route::get('/projetos/{slug}', [PublicProjectController::class, 'show'])->name('projects.show');
Route::post('/contato', [ContactFormController::class, 'store'])->name('contact.store');
Route::get('/pagina/{slug}', [PublicPageController::class, 'show'])->name('pages.show');
Route::get('/transparencia', [PublicTransparencyController::class, 'index'])->name('transparency.index');
Route::get('/contato', [PublicContactController::class, 'index'])->name('contact.index');

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
    Route::get('cms-paginas-publicas', [CmsPublicPageController::class, 'index'])->name('cms-public-pages.index');
    Route::get('cms-paginas-publicas/{cmsPublicPage}/editar', [CmsPublicPageController::class, 'edit'])->name('cms-public-pages.edit');
    Route::put('cms-paginas-publicas/{cmsPublicPage}', [CmsPublicPageController::class, 'update'])->name('cms-public-pages.update');
    Route::get('cms-paginas-publicas/{cmsPublicPage}/html-completo', [CmsPublicPageController::class, 'editFullHtml'])->name('cms-public-pages.edit-full-html');
    Route::put('cms-paginas-publicas/{cmsPublicPage}/html-completo', [CmsPublicPageController::class, 'updateFullHtml'])->name('cms-public-pages.update-full-html');
    Route::get('cms-paginas-publicas/{cmsPublicPage}/seo', [CmsPublicPageController::class, 'editSeo'])->name('cms-public-pages.seo');
    Route::put('cms-paginas-publicas/{cmsPublicPage}/seo', [CmsPublicPageController::class, 'updateSeo'])->name('cms-public-pages.update-seo');
    Route::post('cms-paginas-publicas/{cmsPublicPage}/limpar-cache', [CmsPublicPageController::class, 'clearCache'])->name('cms-public-pages.clear-cache');
    Route::resource('ods', OdsController::class)->only(['index', 'edit', 'update']);
    Route::resource('ips-manutencao', MaintenanceIpController::class)->parameters(['ips-manutencao' => 'maintenanceIp']);
    Route::resource('transparencia', TransparencyController::class)->parameters(['transparencia' => 'transparency']);
    Route::get('transparencia-categorias', [TransparencyCategoryController::class, 'index'])->name('transparency-categories.index');
    Route::post('transparencia-categorias', [TransparencyCategoryController::class, 'store'])->name('transparency-categories.store');
    Route::post('transparencia-categorias/ordenar', [TransparencyCategoryController::class, 'updateOrder'])->name('transparency-categories.update-order');
    Route::put('transparencia-categorias/{category}', [TransparencyCategoryController::class, 'update'])->name('transparency-categories.update');
    Route::delete('transparencia-categorias/{category}', [TransparencyCategoryController::class, 'destroy'])->name('transparency-categories.destroy');
    Route::resource('depoimentos', TestimonialController::class)->parameters(['depoimentos' => 'testimonial']);
    Route::resource('faq', FaqController::class);
    Route::post('depoimentos/{testimonial}/toggle', [TestimonialController::class, 'toggleActive'])->name('testimonials.toggle');
    Route::post('faq/{faq}/toggle', [FaqController::class, 'toggleActive'])->name('faqs.toggle');
    Route::post('ips-manutencao/add-current', [MaintenanceIpController::class, 'addCurrentIp'])->name('ips-manutencao.add-current');
    // Profile
    Route::get('perfil', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('perfil/senha', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Analytics
    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    // Menu Editor (Admin sidebar)
    Route::get('menu', [MenuController::class, 'index'])->name('menu.index');
    Route::post('menu/ordenar', [MenuController::class, 'updateOrder'])->name('menu.update-order');
    Route::get('menu/render', [MenuController::class, 'renderMenu'])->name('menu.render');

    // Frontend Menu Editor
    Route::get('menu-site', [FrontendMenuController::class, 'index'])->name('frontend-menu.index');
    Route::post('menu-site/ordenar', [FrontendMenuController::class, 'updateOrder'])->name('frontend-menu.update-order');
    Route::get('menu-site/render', [FrontendMenuController::class, 'renderMenu'])->name('frontend-menu.render');

    // Central de Cron
    Route::get('cron', [CronController::class, 'index'])->name('cron.index');
    Route::put('cron/{task}', [CronController::class, 'update'])->name('cron.update');
    Route::post('cron/{task}/toggle', [CronController::class, 'toggle'])->name('cron.toggle');
    Route::post('cron/{task}/run', [CronController::class, 'runNow'])->name('cron.run');

    // Pastas do Google Drive
    Route::get('drive-pastas', [GoogleDriveFolderController::class, 'index'])->name('drive-folders.index');
    Route::post('drive-pastas', [GoogleDriveFolderController::class, 'store'])->name('drive-folders.store');
    Route::put('drive-pastas/{folderId}', [GoogleDriveFolderController::class, 'update'])->name('drive-folders.update');
    Route::delete('drive-pastas/{folderId}', [GoogleDriveFolderController::class, 'destroy'])->name('drive-folders.destroy');

    // CMS Routes
});

// CMS Public Routes

// CMS Redirect Handler (must be near the end to not interfere)

// CMS Páginas Dinâmicas (catch-all for CMS-managed pages)
