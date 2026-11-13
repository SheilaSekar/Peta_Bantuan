<?php

use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home']);
Route::get('/cari', [SiteController::class, 'search'])->name('search');
Route::get('/cari/{provinsi}/{kabkota?}', [SiteController::class, 'results']);
Route::get('/layanan/{slug}', [SiteController::class, 'detail']);
Route::get('/hak-dan-alur', [SiteController::class, 'rights']);
Route::get('/keamanan-digital', [SiteController::class, 'safety']);
Route::get('/tentang', [SiteController::class, 'about']);
Route::get('/robots.txt', [SiteController::class, 'robots']);
Route::get('/sitemap.xml', [SiteController::class, 'sitemap']);
