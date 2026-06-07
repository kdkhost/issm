<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\CmsPublicPageController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
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
use App\Http\Controllers\Admin\TestimonialController;
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
use App\Http\Controllers\Admin\CmsPageController;
use App\Http\Controllers\Admin\CmsSectionController;
use App\Http\Controllers\Admin\CmsBlockController;
use App\Http\Controllers\Admin\CmsMediaController;
use App\Http\Controllers\Admin\CmsSeoController;
use App\Http\Controllers\Admin\CmsAuditController;
use App\Http\Controllers\Admin\CmsCacheController;
use App\Http\Controllers\Admin\CmsVersionController;
use App\Http\Controllers\Admin\CmsMenuController;
use App\Http\Controllers\Public\CmsPublicController;
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
    Route::get('cms-paginas-originais', [CmsPublicPageController::class, 'index'])->name('cms-original-pages.index');
    Route::get('cms-paginas-originais/{cmsPublicPage}/editar', [CmsPublicPageController::class, 'edit'])->name('cms-original-pages.edit');
    Route::put('cms-paginas-originais/{cmsPublicPage}', [CmsPublicPageController::class, 'update'])->name('cms-original-pages.update');
    Route::get('cms-paginas-originais/{cmsPublicPage}/seo', [CmsPublicPageController::class, 'editSeo'])->name('cms-original-pages.seo');
    Route::put('cms-paginas-originais/{cmsPublicPage}/seo', [CmsPublicPageController::class, 'updateSeo'])->name('cms-original-pages.update-seo');
    Route::post('cms-paginas-originais/{cmsPublicPage}/limpar-cache', [CmsPublicPageController::class, 'clearCache'])->name('cms-original-pages.clear-cache');
    Route::resource('ods', OdsController::class)->only(['index', 'edit', 'update']);
    Route::resource('ips-manutencao', MaintenanceIpController::class)->parameters(['ips-manutencao' => 'maintenanceIp']);
    Route::resource('transparencia', TransparencyController::class)->parameters(['transparencia' => 'transparency']);
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

    // CMS Routes
    require __DIR__.'/cms.php';
});

// CMS Public Routes
Route::get('/sitemap.xml', [CmsPublicController::class, 'sitemap'])->name('cms.sitemap');
Route::get('/robots.txt', [CmsPublicController::class, 'robotsTxt'])->name('cms.robots');

// CMS Redirect Handler (must be near the end to not interfere)
Route::get('/r/{from}', [CmsPublicController::class, 'redirect'])->name('cms.redirect');

// CMS Páginas Dinâmicas (catch-all for CMS-managed pages)
Route::get('/cms/{slug}', [CmsPublicController::class, 'show'])->name('cms.page');
