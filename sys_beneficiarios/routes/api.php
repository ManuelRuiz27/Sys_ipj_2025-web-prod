<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SeccionesController;

Route::middleware('throttle:30,1')->get('/secciones/{seccional}', [SeccionesController::class, 'show']);
