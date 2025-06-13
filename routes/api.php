<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/tripay/callback', [App\Http\Controllers\TripayController::class, 'handleCallback'])->name('tripay.callback');
