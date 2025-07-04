<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Back\UserController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Back\CategoryController;
use App\Http\Controllers\Back\DashboardController;
use App\Http\Controllers\Back\PostinganController;
use App\Http\Controllers\Front\CategoryController as FrontCategoryController;
use App\Http\Controllers\Front\PostinganController as FrontPostinganController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Front\ProfileController;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', [HomeController::class, 'index']);
Route::POST('/postingan/search', [HomeController::class, 'index'])->name('search');

Route::get('/p/{slug}', [FrontPostinganController::class, 'show'])->name('poatingan.show');

Route::get('category/{slug}', [FrontCategoryController::class, 'index']);

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

    Route::post('/profile/categories', [ProfileController::class, 'updateCategories'])->name('profile.updateCategories');
    Route::delete('/profile/categories/{category}', [ProfileController::class, 'removeCategory'])->name('profile.remove-category');

    Route::put('/profile/update-biodata', [ProfileController::class, 'updateBiodata'])->name('profile.updateBiodata');

    Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.uploadPhoto');

    Route::post('/profile/certificate', [ProfileController::class, 'uploadCertificate'])->name('profile.uploadCertificate');

    Route::resource('/postingan', PostinganController::class);

    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy'])->middleware([\App\Http\Middleware\UserAccess::class . ':1']);

    Route::resource('/users', UserController::class);

    Route::post('postingan/{id}/request-join', [PostinganController::class, 'requestJoin'])->name('postingan.requestJoin');

    Route::post('postingan/{id}/request-join', [FrontPostinganController::class, 'requestJoin'])->name('postingan.requestJoin');
    Route::get('postingan/{id}/requests', [PostinganController::class, 'joinRequests'])->name('postingan.joinRequests');
    Route::post('postingan/{postingan_id}/approve/{user_id}', [PostinganController::class, 'approveJoin'])->name('postingan.approveJoin');
    Route::post('postingan/{postingan_id}/reject/{user_id}', [PostinganController::class, 'rejectJoin'])->name('postingan.rejectJoin');

    Route::group(['prefix' => 'laravel-filemanager'], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });
});

Route::middleware(['auth', \App\Http\Middleware\UserAccess::class . ':1'])->group(function () {
    Route::get('/approval', [\App\Http\Controllers\Back\ApprovalController::class, 'index'])->name('approval.index');
    Route::post('/approval/{id}/approve', [\App\Http\Controllers\Back\ApprovalController::class, 'approve'])->name('approval.approve');
    Route::post('/approval/{id}/reject', [\App\Http\Controllers\Back\ApprovalController::class, 'reject'])->name('approval.reject');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
