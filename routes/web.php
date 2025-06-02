<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return inertia('Web/Home/Index');
});

Route::post('/logout', \App\Http\Controllers\Auth\LogoutController::class)->name('logout')->middleware('auth');

Route::prefix('admin')->group(function () {

    //middleware "auth"
    Route::group(['middleware' => ['auth', 'role:admin']], function () {

        //route dashboard
        Route::get('/dashboard', App\Http\Controllers\Admin\DashboardController::class)->name('admin.dashboard');
        // route sliders
        Route::resource('/sliders', App\Http\Controllers\Admin\SliderController::class, ['as' => 'admin']);
        //route categories
        Route::resource('/categories', App\Http\Controllers\Admin\CategoryController::class, ['as' => 'admin']);
        // route cars
        Route::resource('/cars', App\Http\Controllers\Admin\CarController::class, ['as' => 'admin']);
    });
});
