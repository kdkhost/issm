<?php

/**
 * @autor marcelo-brad rj
 * @contato Tel: 21 981325441
 * Email: contato@kdkhost.com.br
 * Telegram: @MARCELO_BRAD
 * Instagram: @marcelobradrj
 * WhatsApp: 21981325441
 */

use App\Http\Controllers\Admin\CmsPageController;
use App\Http\Controllers\Admin\CmsSectionController;
use App\Http\Controllers\Admin\CmsBlockController;
use App\Http\Controllers\Admin\CmsMediaController;
use App\Http\Controllers\Admin\CmsSeoController;
use App\Http\Controllers\Admin\CmsAuditController;
use App\Http\Controllers\Admin\CmsCacheController;
use App\Http\Controllers\Admin\CmsVersionController;
use App\Http\Controllers\Admin\CmsMenuController;
use Illuminate\Support\Facades\Route;

Route::name('cms.')->group(function () {

    // Pages
    Route::get('/pages', [CmsPageController::class, 'index'])->name('pages.index');
    Route::get('/pages/create', [CmsPageController::class, 'create'])->name('pages.create');
    Route::post('/pages', [CmsPageController::class, 'store'])->name('pages.store');
    Route::get('/pages/{page}/edit', [CmsPageController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{page}', [CmsPageController::class, 'update'])->name('pages.update');
    Route::delete('/pages/{page}', [CmsPageController::class, 'destroy'])->name('pages.destroy');
    Route::post('/pages/{page}/publish', [CmsPageController::class, 'publish'])->name('pages.publish');
    Route::post('/pages/{page}/archive', [CmsPageController::class, 'archive'])->name('pages.archive');
    Route::post('/pages/{page}/duplicate', [CmsPageController::class, 'duplicate'])->name('pages.duplicate');
    Route::post('/pages/{page}/toggle-status', [CmsPageController::class, 'toggleStatus'])->name('pages.toggle-status');

    // Sections
    Route::get('/sections', [CmsSectionController::class, 'index'])->name('sections.index');
    Route::post('/sections', [CmsSectionController::class, 'store'])->name('sections.store');
    Route::put('/sections/{section}', [CmsSectionController::class, 'update'])->name('sections.update');
    Route::delete('/sections/{section}', [CmsSectionController::class, 'destroy'])->name('sections.destroy');
    Route::post('/sections/reorder', [CmsSectionController::class, 'reorder'])->name('sections.reorder');

    // Blocks
    Route::get('/blocks', [CmsBlockController::class, 'index'])->name('blocks.index');
    Route::post('/blocks', [CmsBlockController::class, 'store'])->name('blocks.store');
    Route::put('/blocks/{block}', [CmsBlockController::class, 'update'])->name('blocks.update');
    Route::delete('/blocks/{block}', [CmsBlockController::class, 'destroy'])->name('blocks.destroy');
    Route::post('/blocks/reorder', [CmsBlockController::class, 'reorder'])->name('blocks.reorder');
    Route::post('/blocks/{block}/toggle-status', [CmsBlockController::class, 'toggleStatus'])->name('blocks.toggle-status');

    // Media
    Route::get('/media', [CmsMediaController::class, 'index'])->name('media.index');
    Route::post('/media/upload', [CmsMediaController::class, 'upload'])->name('media.upload');
    Route::put('/media/{medium}', [CmsMediaController::class, 'update'])->name('media.update');
    Route::delete('/media/{medium}', [CmsMediaController::class, 'destroy'])->name('media.destroy');
    Route::get('/media/json', [CmsMediaController::class, 'getMediaJson'])->name('media.json');

    // SEO
    Route::get('/seo/{page}/edit', [CmsSeoController::class, 'edit'])->name('seo.edit');
    Route::put('/seo/{page}', [CmsSeoController::class, 'update'])->name('seo.update');

    // Audit
    Route::get('/audit', [CmsAuditController::class, 'index'])->name('audit.index');

    // Cache
    Route::get('/cache', [CmsCacheController::class, 'index'])->name('cache.index');
    Route::post('/cache/clear', [CmsCacheController::class, 'clearCache'])->name('cache.clear');
    Route::post('/cache/clear-page', [CmsCacheController::class, 'clearPageCache'])->name('cache.clear-page');

    // Versions
    Route::get('/versions/{modelType}/{modelId}', [CmsVersionController::class, 'index'])->name('versions.index');
    Route::get('/versions/{version}', [CmsVersionController::class, 'show'])->name('versions.show');
    Route::post('/versions/{version}/restore', [CmsVersionController::class, 'restore'])->name('versions.restore');

    // Menus
    Route::get('/menus', [CmsMenuController::class, 'index'])->name('menus.index');
    Route::post('/menus', [CmsMenuController::class, 'store'])->name('menus.store');
    Route::put('/menus/{menu}', [CmsMenuController::class, 'update'])->name('menus.update');
    Route::delete('/menus/{menu}', [CmsMenuController::class, 'destroy'])->name('menus.destroy');

});
