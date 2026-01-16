<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Sucursales\Index as SucursalesIndex;
use App\Livewire\Empleados\Index as EmpleadosIndex;
use App\Livewire\Equipos\Index as EquiposIndex;

Route::view('/', 'welcome');

Route::get('/dashboard', \App\Livewire\Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';

// New Inventory Routes (Protected)
Route::middleware(['auth', 'verified'])->group(function () {
    // Sucursales
    Route::get('/sucursales', SucursalesIndex::class)->name('sucursales.index');
    Route::get('/sucursales/crear', \App\Livewire\Sucursales\Create::class)->name('sucursales.create');
    Route::get('/sucursales/editar/{id}', \App\Livewire\Sucursales\Edit::class)->name('sucursales.edit');

    // Areas
    Route::get('/areas', \App\Livewire\Areas\Index::class)->name('areas.index');
    Route::get('/areas/crear', \App\Livewire\Areas\Create::class)->name('areas.create');
    Route::get('/areas/editar/{id}', \App\Livewire\Areas\Edit::class)->name('areas.edit');

    // Cargos
    Route::get('/cargos', \App\Livewire\Cargos\Index::class)->name('cargos.index');
    Route::get('/cargos/crear', \App\Livewire\Cargos\Create::class)->name('cargos.create');
    Route::get('/cargos/editar/{id}', \App\Livewire\Cargos\Edit::class)->name('cargos.edit');

    // Cargos
    Route::get('/cargos', \App\Livewire\Cargos\Index::class)->name('cargos.index');
    Route::get('/cargos/crear', \App\Livewire\Cargos\Create::class)->name('cargos.create');
    Route::get('/cargos/editar/{id}', \App\Livewire\Cargos\Edit::class)->name('cargos.edit');

    // Marcas
    Route::get('/marcas', \App\Livewire\Marcas\Index::class)->name('marcas.index');
    Route::get('/marcas/crear', \App\Livewire\Marcas\Create::class)->name('marcas.create');
    Route::get('/marcas/editar/{id}', \App\Livewire\Marcas\Edit::class)->name('marcas.edit');

    // Modelos
    Route::get('/modelos', \App\Livewire\Modelos\Index::class)->name('modelos.index');
    Route::get('/modelos/crear', \App\Livewire\Modelos\Create::class)->name('modelos.create');
    Route::get('/modelos/editar/{id}', \App\Livewire\Modelos\Edit::class)->name('modelos.edit');

    // Tipos de Equipo
    Route::get('/tipos-equipo', \App\Livewire\TiposEquipo\Index::class)->name('tipos-equipo.index');
    Route::get('/tipos-equipo/crear', \App\Livewire\TiposEquipo\Create::class)->name('tipos-equipo.create');
    Route::get('/tipos-equipo/editar/{id}', \App\Livewire\TiposEquipo\Edit::class)->name('tipos-equipo.edit');

    // Estados de Equipo
    Route::get('/estados', \App\Livewire\Estados\Index::class)->name('estados.index');
    Route::get('/estados/crear', \App\Livewire\Estados\Create::class)->name('estados.create');
    Route::get('/estados/editar/{id}', \App\Livewire\Estados\Edit::class)->name('estados.edit');

    Route::get('/empleados', EmpleadosIndex::class)->name('empleados.index');
    Route::get('/empleados/crear', \App\Livewire\Empleados\Create::class)->name('empleados.create');
    Route::get('/empleados/editar/{id}', \App\Livewire\Empleados\Edit::class)->name('empleados.edit');
    Route::get('/equipos', EquiposIndex::class)->name('equipos.index');
    Route::get('/equipos/crear', \App\Livewire\Equipos\Create::class)->name('equipos.create');
    Route::get('/equipos/editar/{id}', \App\Livewire\Equipos\Edit::class)->name('equipos.edit');

    // Repairs
    Route::get('/reparaciones', \App\Livewire\Reparaciones\Index::class)->name('reparaciones.index');
    Route::get('/reparaciones/crear/{id}', \App\Livewire\Reparaciones\Create::class)->name('reparaciones.create');
    Route::get('/reparaciones/finalizar/{id}', \App\Livewire\Reparaciones\Finalizar::class)->name('reparaciones.finalizar');

    // Assignments
    Route::get('/asignaciones', \App\Livewire\Asignaciones\Index::class)->name('asignaciones.index');
    Route::get('/asignaciones/crear', \App\Livewire\Asignaciones\Create::class)->name('asignaciones.create');
    Route::get('/asignaciones/devolver/{id}', \App\Livewire\Asignaciones\Devolver::class)->name('asignaciones.devolver');
    Route::get('/asignaciones/pdf/entrega/{id}', [\App\Http\Controllers\PdfController::class, 'generarActaEntrega'])->name('asignaciones.pdf.entrega');
    Route::get('/asignaciones/pdf/devolucion/{id}', [\App\Http\Controllers\PdfController::class, 'generarActaDevolucion'])->name('asignaciones.pdf.devolucion');
    Route::get('/pdf/acta-baja', [\App\Http\Controllers\PdfController::class, 'actaBaja'])->name('pdf.acta_baja');

    // Disposals (Bajas)
    Route::get('/bajas', \App\Livewire\Bajas\Index::class)->name('bajas.index');
    Route::get('/bajas/crear/{id}', \App\Livewire\Bajas\Create::class)->name('bajas.create');
});
