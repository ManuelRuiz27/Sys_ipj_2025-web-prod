<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SeccionesController;
use App\Http\Controllers\S360\S360AdminController;
use App\Http\Controllers\S360\S360BienestarController;
use App\Http\Controllers\S360\S360Enc360Controller;
use App\Http\Controllers\S360\S360PsicoController;
use App\Http\Controllers\S360\Api\S360ApiController;

Route::middleware('throttle:30,1')->get('/secciones/{seccional}', [SeccionesController::class, 'show']);

// API Salud360 lectura (Sanctum)
Route::middleware(['auth:sanctum','throttle:30,1'])->prefix('s360')->group(function () {
    // Psicólogo
    Route::get('psico/pacientes', [S360ApiController::class, 'psicoPacientes']);
    Route::get('psico/sesiones/{beneficiario}', [S360ApiController::class, 'psicoHistorial']);
    // Dashboards
    Route::get('enc360/dash', [S360ApiController::class, 'enc360Dash']);
    Route::get('bienestar/dash', [S360ApiController::class, 'bienestarDash']);
});
