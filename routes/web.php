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

Route::get('/run-migrate-and-seed/{key}', function ($key) {
    if ($key !== 'Chimicr-261996') { // Usa tu clave real
        abort(403, 'Acceso Denegado.');
    }

    try {
        // 1. Ejecutar las migraciones
        \Artisan::call('migrate', ['--force' => true]); 

        // 2. Ejecutar los seeders (¡Esto incluye tu AdminUserSeeder!)
        \Artisan::call('db:seed', ['--force' => true]); 

        return '¡Migraciones y Seeder de Admin ejecutados con éxito!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

require __DIR__.'/auth.php';
