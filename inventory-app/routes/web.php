<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\InventoryLogController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Items
    Route::get('/items', [ItemController::class, 'index'])
        ->name('items.index');

    Route::post('/items', [ItemController::class, 'store'])
        ->name('items.store');

    Route::get('/items/search', [ItemController::class, 'search'])
        ->name('items.search');

    // Inventory Logs
    Route::post('/inventory', [InventoryLogController::class, 'store'])
        ->name('inventory.store');

    Route::get('/inventory', [InventoryLogController::class, 'all'])
        ->name('inventory.all');

    Route::get('/inventory/{item}/edit', [InventoryLogController::class, 'edit'])
        ->name('inventory.edit');

    Route::post('/inventory/deduct', [InventoryLogController::class, 'deduct'])
        ->name('inventory.deduct');

    Route::get('/inventory/{item}', [InventoryLogController::class, 'index'])
        ->name('inventory.index');
});

require __DIR__.'/settings.php';
