<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DestinationController;

Route::prefix('v1')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::group(['as' => 'api.', 'namespace' => 'Api'], function () {
        /*
         * Destinations Endpoints
         */
        Route::get('destinations', [DestinationController::class, 'index'])->name('destinations.index');
    });
});
