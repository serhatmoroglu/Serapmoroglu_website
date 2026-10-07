<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\TreatmentController;
use Illuminate\Support\Facades\Route;

/* ---------- Herkese açık site ---------- */
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/hakkimizda', [PageController::class, 'about'])->name('about');
Route::get('/klinik', [PageController::class, 'clinic'])->name('clinic');
Route::get('/iletisim', [PageController::class, 'contact'])->name('contact');
Route::get('/randevu', [PageController::class, 'appointment'])->name('appointment');

Route::get('/tedaviler', [TreatmentController::class, 'index'])->name('treatments.index');
Route::get('/tedaviler/{treatment:slug}', [TreatmentController::class, 'show'])->name('treatments.show');

Route::get('/yazilar', [PostController::class, 'index'])->name('posts.index');
Route::get('/yazilar/{post:slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('/basinda-biz', [PostController::class, 'press'])->name('press');

Route::post('/talep', [LeadController::class, 'store'])->middleware('throttle:6,1')->name('lead.store');

Route::get('/sitemap.xml', [SeoController::class, 'sitemap']);
Route::get('/robots.txt', [SeoController::class, 'robots']);

/* ---------- Yönetim paneli ---------- */
Route::prefix('yonetim')->name('admin.')->group(function () {
    Route::get('giris', [Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('giris', [Admin\AuthController::class, 'login'])->middleware('throttle:8,1');

    Route::middleware('auth')->group(function () {
        Route::post('cikis', [Admin\AuthController::class, 'logout'])->name('logout');
        Route::get('sifre', [Admin\AuthController::class, 'showPassword'])->name('password');
        Route::post('sifre', [Admin\AuthController::class, 'updatePassword']);

        Route::middleware('password.changed')->group(function () {
            Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

            Route::resource('tedaviler', Admin\TreatmentController::class)->except('show')->parameters(['tedaviler' => 'treatment']);
            Route::get('icerik/{kind}', [Admin\PostController::class, 'index'])->whereIn('kind', ['yazi', 'gazete', 'video'])->name('posts.index');
            Route::get('icerik/{kind}/yeni', [Admin\PostController::class, 'create'])->whereIn('kind', ['yazi', 'gazete', 'video'])->name('posts.create');
            Route::post('icerik/{kind}', [Admin\PostController::class, 'store'])->whereIn('kind', ['yazi', 'gazete', 'video'])->name('posts.store');
            Route::get('icerik-duzenle/{post}', [Admin\PostController::class, 'edit'])->name('posts.edit');
            Route::put('icerik-duzenle/{post}', [Admin\PostController::class, 'update'])->name('posts.update');
            Route::delete('icerik-duzenle/{post}', [Admin\PostController::class, 'destroy'])->name('posts.destroy');

            Route::get('talepler', [Admin\LeadController::class, 'index'])->name('leads.index');
            Route::get('talepler/disa-aktar', [Admin\LeadController::class, 'export'])->name('leads.export');
            Route::get('talepler/{lead}', [Admin\LeadController::class, 'show'])->name('leads.show');
            Route::put('talepler/{lead}', [Admin\LeadController::class, 'update'])->name('leads.update');
            Route::delete('talepler/{lead}', [Admin\LeadController::class, 'destroy'])->name('leads.destroy');

            Route::get('ayarlar/{section?}', [Admin\SettingController::class, 'edit'])->name('settings');
            Route::put('ayarlar/{section}', [Admin\SettingController::class, 'update'])->name('settings.update');
            Route::post('ayarlar/eposta/test', [Admin\SettingController::class, 'testMail'])->name('settings.testmail');

            Route::post('yukle', [Admin\UploadController::class, 'store'])->name('upload');
        });
    });
});
