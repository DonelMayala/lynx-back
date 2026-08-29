<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CameraController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetectionController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\VideoRecordingController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function (): void {
    Route::get('/connexion', [AuthController::class, 'create'])->name('login');
    Route::post('/connexion', [AuthController::class, 'store'])->middleware('throttle:6,1')->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/deconnexion', [AuthController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/alertes', [AlertController::class, 'index'])->name('alerts.index');
    Route::patch('/alertes/{alert}', [AlertController::class, 'update'])->name('alerts.update');
    Route::get('/cameras', [CameraController::class, 'index'])->name('cameras.index');
    Route::get('/cameras/nouvelle', [CameraController::class, 'create'])->name('cameras.create');
    Route::post('/cameras', [CameraController::class, 'store'])->name('cameras.store');
    Route::get('/detections', [DetectionController::class, 'index'])->name('detections.index');
    Route::patch('/detections/{detection}', [DetectionController::class, 'update'])->name('detections.update');
    Route::get('/enregistrements/{recording}', [VideoRecordingController::class, 'show'])->name('recordings.show');
    Route::get('/incidents', [IncidentController::class, 'index'])->name('incidents.index');
    Route::get('/incidents/{incident}', [IncidentController::class, 'show'])->name('incidents.show');
    Route::get('/administration', AdminController::class)->name('admin.index');
    Route::get('/administration/sites', [SiteController::class, 'index'])->name('sites.index');
    Route::post('/administration/sites', [SiteController::class, 'store'])->name('sites.store');
});
