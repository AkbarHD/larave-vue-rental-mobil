<?php

use Illuminate\Support\Facades\Route;

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

        // route categories
        Route::resource('/categories', App\Http\Controllers\Admin\CategoryController::class, ['as' => 'admin']);

        // route cars
        Route::resource('/cars', App\Http\Controllers\Admin\CarController::class, ['as' => 'admin']);

        // route rentals
        Route::get('/rentals', [App\Http\Controllers\Admin\RentalController::class, 'index'])->name('admin.rentals.index');

        // route addons
        Route::resource('/addons', App\Http\Controllers\Admin\AddonController::class, ['as' => 'admin']);

        // route payments
        Route::resource('/payments', App\Http\Controllers\Admin\PaymentController::class, ['as' => 'admin']);

        // route reviews
        Route::resource('/reviews', App\Http\Controllers\Admin\ReviewController::class, ['as' => 'admin']);
    });
});


Route::group(['middleware' => ['auth']], function () {
    Route::get('/rentals/{rental}', [App\Http\Controllers\Admin\RentalController::class, 'show'])->name('rentals.show');
});

Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::post('/rentals/{id}/approve', [App\Http\Controllers\Admin\RentalController::class, 'approve'])->name('rentals.approve');
    Route::post('/rentals/{id}/reject', [App\Http\Controllers\Admin\RentalController::class, 'reject'])->name('rentals.reject');
    Route::post('/rentals/{id}/confirm-return', [App\Http\Controllers\Admin\RentalController::class, 'confirmReturn'])->name('rentals.confirmReturn');
});
