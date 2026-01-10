<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Orders Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::controller(OrderController::class)
        ->prefix('admin/orders')
        ->name('orders.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::post('{order}/assign-technician', 'assignTechnician')->name('orders.assign-technician');

        });
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::controller(UsersController::class)
        ->prefix('admin/users')
        ->name('technicians.')
        ->group(function () {
            Route::get('/technicians', 'index')->name('index');
            Route::get('/clients', 'clients')->name('clients');
            Route::post('/', 'store')->name('users.store');
        });
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::controller(ServiceController::class)
        ->prefix('admin/services')
        ->name('services.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{service}', 'update')->name('update');
            Route::delete('/{service}', 'destroy')->name('destroy');
        });
});

// Client Orders Routes
Route::middleware(['auth', 'role:client'])->group(function () {
    Route::controller(OrderController::class)
        ->prefix('orders')
        ->name('orders.client.')
        ->group(function () {
            Route::get('/', 'clientOrders')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
        });
});

require __DIR__.'/settings.php';
