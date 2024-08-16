<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\DestinationMapController;

// Guest Route
Route::get('/', function () { return view('guest');})->middleware('guest');

// Authenticated User Routes
Route::middleware('auth')->group(function () {

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Destination Routes
    Route::resource('destinations', DestinationController::class);
    Route::post('/destinations/order', [DestinationController::class, 'updateOrder'])->name('destinations.updateOrder');
    Route::get('/destination-map', [DestinationMapController::class, 'index'])->name('destination_map.index');

});

// User Dashboard Route
Route::get('/dashboard', [UserDashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Authentication Routes
require __DIR__.'/auth.php';
