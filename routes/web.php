<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ClusteringController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ForgotPasswordController;
use App\Http\Controllers\Admin\ResetPasswordController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\MasterBansosController;
use App\Http\Controllers\Admin\MasterKondisiRumahController;
use App\Http\Controllers\Admin\MasterPendidikanController;
use App\Http\Controllers\Admin\MasterStatusProduktivitasController;
use App\Http\Controllers\Admin\WargaController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('home'))->name('home');

// Auth
Route::get('admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::get('admin/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
Route::post('admin/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.password.email');
Route::get('admin/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('admin.password.reset');
Route::post('admin/reset-password', [ResetPasswordController::class, 'reset'])->name('admin.password.update');

// Admin Area
Route::prefix('admin')->middleware(['web', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Profile
    Route::get('profile', [ProfileController::class, 'index'])->name('admin.profile.index');
    Route::put('profile/info', [ProfileController::class, 'updateInfo'])->name('admin.profile.info.update');
    Route::post('profile/password', [ProfileController::class, 'updatePassword'])->name('admin.profile.password.update');

    // Warga CRUD (admin only)
    Route::get('warga/export-pdf', [WargaController::class, 'exportPdf'])->name('admin.warga.export-pdf');
    Route::get('warga/export-excel', [WargaController::class, 'exportExcel'])->name('admin.warga.export-excel');
    Route::get('warga/import-template', [WargaController::class, 'downloadTemplate'])->name('admin.warga.import-template');
    Route::post('warga/import-excel', [WargaController::class, 'importExcel'])->name('admin.warga.import-excel');
    Route::resource('warga', WargaController::class)->names('admin.warga');

    // Master Data (admin only)
    Route::resource('master-pendidikan', MasterPendidikanController::class)->names('admin.master-pendidikan')->except(['create', 'edit', 'show']);
    Route::resource('master-kondisi-rumah', MasterKondisiRumahController::class)->names('admin.master-kondisi-rumah');
    Route::resource('master-bansos', MasterBansosController::class)->names('admin.master-bansos')->except(['create', 'edit', 'show']);
    Route::get('master-status-produktivitas', [MasterStatusProduktivitasController::class, 'index'])->name('admin.master-status-produktivitas.index');
    Route::post('master-status-produktivitas', [MasterStatusProduktivitasController::class, 'store'])->name('admin.master-status-produktivitas.store');
    Route::put('master-status-produktivitas/{id}', [MasterStatusProduktivitasController::class, 'update'])->name('admin.master-status-produktivitas.update');
    Route::delete('master-status-produktivitas/{id}', [MasterStatusProduktivitasController::class, 'destroy'])->name('admin.master-status-produktivitas.destroy');

    // Pengelompokan
    Route::get('clustering', [ClusteringController::class, 'index'])->name('admin.clustering.index');
    Route::post('clustering/process', [ClusteringController::class, 'process'])->name('admin.clustering.process');
    Route::get('clustering/history', [ClusteringController::class, 'history'])->name('admin.clustering.history');
    Route::get('clustering/pending-classifications', [ClusteringController::class, 'pendingClassifications'])->name('admin.clustering.pending-classifications');
    Route::get('clustering/{id}', [ClusteringController::class, 'show'])->name('admin.clustering.show');
    Route::get('clustering/{id}/pdf', [ClusteringController::class, 'downloadPdf'])->name('admin.clustering.pdf');
    Route::get('clustering/{id}/excel', [ClusteringController::class, 'downloadExcel'])->name('admin.clustering.excel');
    Route::post('clustering/classification/approve-all', [ClusteringController::class, 'approveAllClassifications'])->name('admin.clustering.approve-all-classifications');
    Route::post('clustering/classification/{id}/approve', [ClusteringController::class, 'approveClassification'])->name('admin.clustering.approve-classification');
    Route::post('clustering/classification/{id}/reject', [ClusteringController::class, 'rejectClassification'])->name('admin.clustering.reject-classification');
});
