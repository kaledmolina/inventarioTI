<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Sucursales\Index as SucursalesIndex;
use App\Livewire\Empleados\Index as EmpleadosIndex;
use App\Livewire\Equipos\Index as EquiposIndex;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';

// New Inventory Routes (Protected)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/sucursales', SucursalesIndex::class)->name('sucursales.index');
    Route::get('/empleados', EmpleadosIndex::class)->name('empleados.index');
    Route::get('/equipos', EquiposIndex::class)->name('equipos.index');

    // Assignments
    Route::get('/asignaciones', \App\Livewire\Asignaciones\Index::class)->name('asignaciones.index');
    Route::get('/asignaciones/crear', \App\Livewire\Asignaciones\Create::class)->name('asignaciones.create');
    Route::get('/asignaciones/devolver/{id}', \App\Livewire\Asignaciones\Devolver::class)->name('asignaciones.devolver');
    Route::get('/asignaciones/pdf/entrega/{id}', [\App\Http\Controllers\PdfController::class, 'generarActaEntrega'])->name('asignaciones.pdf.entrega');
    Route::get('/asignaciones/pdf/devolucion/{id}', [\App\Http\Controllers\PdfController::class, 'generarActaDevolucion'])->name('asignaciones.pdf.devolucion');
});
