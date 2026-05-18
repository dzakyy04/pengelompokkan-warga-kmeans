<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ClusteringController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MasterBansosController;
use App\Http\Controllers\Admin\MasterKondisiRumahController;
use App\Http\Controllers\Admin\MasterPendidikanController;
use App\Http\Controllers\Admin\WargaController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('admin.login'));

// Auth
Route::get('admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin Area
Route::prefix('admin')->middleware(['web', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Warga CRUD (admin only)
    Route::resource('warga', WargaController::class)->names('admin.warga');

    // Master Data (admin only)
    Route::resource('master-pendidikan', MasterPendidikanController::class)->names('admin.master-pendidikan')->except(['create', 'edit', 'show']);
    Route::resource('master-kondisi-rumah', MasterKondisiRumahController::class)->names('admin.master-kondisi-rumah');
    Route::resource('master-bansos', MasterBansosController::class)->names('admin.master-bansos')->except(['create', 'edit', 'show']);

    // Clustering
    Route::get('clustering', [ClusteringController::class, 'index'])->name('admin.clustering.index');
    Route::post('clustering/process', [ClusteringController::class, 'process'])->name('admin.clustering.process');
    Route::get('clustering/history', [ClusteringController::class, 'history'])->name('admin.clustering.history');
    Route::get('clustering/{id}', [ClusteringController::class, 'show'])->name('admin.clustering.show');
    Route::post('clustering/{id}/validate', [ClusteringController::class, 'validateSession'])->name('admin.clustering.validate');
    Route::post('clustering/{id}/reject', [ClusteringController::class, 'reject'])->name('admin.clustering.reject');
    Route::get('clustering/{id}/pdf', [ClusteringController::class, 'downloadPdf'])->name('admin.clustering.pdf');
});
