<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ELIMINAR ESTA RUTA DESPUÉS DE LA MIGRACIÓN
Route::get('/run-migrate-and-seed/{key}', function ($key) {
    // Reemplaza 'TU_CLAVE_SECRETA' con una cadena muy larga y aleatoria
    if ($key !== 'TU_CLAVE_SECRETA_UNICA') { 
        abort(403, 'Acceso Denegado.');
    }

    try {
        Artisan::call('migrate', ['--force' => true]);
        // Si tienes seeds para el usuario admin, también puedes ejecutarlo aquí:
        // Artisan::call('db:seed'); 

        return '¡Migraciones y Seeds (si aplica) ejecutados con éxito!';
    } catch (\Exception $e) {
        return 'Error al ejecutar migraciones: ' . $e->getMessage();
    }
});
// FIN DE LA RUTA TEMPORAL

require __DIR__.'/auth.php';
