<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CatalogosController;
use App\Http\Controllers\Admin\BeneficiariosController as AdminBeneficiariosController;
use App\Http\Controllers\Encargado\BeneficiariosController as EncargadoBeneficiariosController;
use App\Http\Controllers\BeneficiarioController;
use App\Http\Controllers\DomicilioController;
use App\Http\Controllers\MisRegistrosController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    if (! Auth::check()) {
        // Mostrar login directamente (200 OK) para mejorar DX/tests
        return view('auth.login');
    }
    $user = Auth::user();
    if ($user->hasRole('admin')) {
        return redirect('/admin');
    }
    if ($user->hasRole('encargado')) {
        return redirect('/encargado');
    }
    if ($user->hasRole('capturista')) {
        return redirect('/capturista');
    }
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Compatibilidad: endpoint antiguo de KPIs de capturista (200 OK)
Route::get('/mi-progreso/kpis', [DashboardController::class, 'miProgresoKpis'])->middleware(['auth','role:capturista']);

// Alias de registro de captura usado en tests
Route::post('/captura/registrar', [BeneficiarioController::class, 'store'])->name('captura.registrar')->middleware(['auth','role:admin|encargado|capturista']);

// Secciones por rol
Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'admin'])->name('admin.home');
    Route::get('/admin/kpis', [DashboardController::class, 'adminKpis'])->name('admin.kpis');
});
Route::middleware(['auth','role:encargado'])->group(function () {
    Route::get('/encargado', [DashboardController::class, 'encargado'])->name('encargado.home');
    Route::get('/encargado/kpis', [DashboardController::class, 'encargadoKpis'])->name('encargado.kpis');
    Route::get('/encargado/beneficiarios', [EncargadoBeneficiariosController::class, 'index'])->name('encargado.beneficiarios.index');
    // Export debe ir antes de la ruta con parámetro para evitar colisiones
    Route::get('/encargado/beneficiarios/export', [EncargadoBeneficiariosController::class, 'export'])->name('encargado.beneficiarios.export');
    Route::get('/encargado/beneficiarios/{beneficiario}', [EncargadoBeneficiariosController::class, 'show'])->name('encargado.beneficiarios.show');
});
Route::middleware(['auth','role:capturista'])->group(function () {
    Route::get('/capturista', [DashboardController::class, 'capturista'])->name('capturista.home');
    // KPIs capturista consistente bajo /capturista/kpis
    Route::get('/capturista/kpis', [DashboardController::class, 'miProgresoKpis'])->name('capturista.kpis');
    // Redirección de compatibilidad desde ruta anterior
    // REDIRECT DISABLED

    // Mis registros (solo capturista)
    Route::prefix('mis-registros')->name('mis-registros.')->group(function () {
        Route::get('/', [MisRegistrosController::class, 'index'])->name('index');
        Route::get('/{beneficiario}', [MisRegistrosController::class, 'show'])->name('show');
        Route::get('/{beneficiario}/edit', [MisRegistrosController::class, 'edit'])->name('edit');
        Route::put('/{beneficiario}', [MisRegistrosController::class, 'update'])->name('update');
    });
});

// Beneficiarios y Domicilios (admin, encargado, capturista)
Route::middleware(['auth','role:admin|encargado|capturista'])->group(function () {
    Route::resource('beneficiarios', BeneficiarioController::class)->except(['show']);
    Route::resource('domicilios', DomicilioController::class)->except(['show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin: gestión de usuarios
Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('usuarios', UserController::class)->parameters(['usuarios' => 'usuario']);
    Route::get('catalogos', [CatalogosController::class, 'index'])->name('catalogos.index');
    Route::post('catalogos/import', [CatalogosController::class, 'import'])->name('catalogos.import');
    Route::get('beneficiarios', [AdminBeneficiariosController::class, 'index'])->name('beneficiarios.index');
    // Export antes de parámetro para no capturar "export" como {beneficiario}
    Route::get('beneficiarios/export', [AdminBeneficiariosController::class, 'export'])->name('beneficiarios.export');
    Route::get('beneficiarios/{beneficiario}', [AdminBeneficiariosController::class, 'show'])->name('beneficiarios.show');
});

require __DIR__.'/auth.php';

// Compatibilidad adicional: asegurar que /mi-progreso/kpis responda 200 OK
Route::get('/mi-progreso/kpis', [DashboardController::class, 'miProgresoKpis'])->middleware(['auth','role:capturista']);
