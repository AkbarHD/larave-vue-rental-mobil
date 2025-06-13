<?php

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


Route::get('/', [App\Http\Controllers\Web\HomeController::class, 'index']);

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

        // route sites
        Route::get('/site-settings', [App\Http\Controllers\Admin\SiteController::class, 'index'])->name('admin.site-settings');
        Route::post('/site-settings', [App\Http\Controllers\Admin\SiteController::class, 'update'])->name('admin.site-settings.update');

        // route messages
        Route::resource('/messages', App\Http\Controllers\Admin\MessageController::class, ['as' => 'admin']);

        Route::get('/reports/rentals', [App\Http\Controllers\Admin\RentalReportController::class, 'index'])
            ->name('reports.rentals');
        Route::post('/reports/rentals/generate', [App\Http\Controllers\Admin\RentalReportController::class, 'generate'])
            ->name('reports.rentals.generate');
        Route::get('/reports/rentals/export', [App\Http\Controllers\Admin\RentalReportController::class, 'export'])
            ->name('reports.rentals.export');

        // route customers
        Route::resource('/customers', App\Http\Controllers\Admin\CustomerController::class, ['as' => 'admin']);
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


Route::get('/cars', [App\Http\Controllers\Web\CarController::class, 'index'])->name('cars.index');
Route::get('/car/{slug}', [App\Http\Controllers\Web\CarController::class, 'show'])->name('car-detail');
Route::get('/load-more-cars', [App\Http\Controllers\Web\CarController::class, 'loadMore'])->name('cars.loadMore');
Route::get('/payment-channels', [App\Http\Controllers\TripayController::class, 'getPaymentChannels']);

Route::group(['middleware' => ['auth']], function () {
    Route::post('/payments', [App\Http\Controllers\Web\CarController::class, 'store'])->name('rentals.store');
    Route::get('/rentals', [App\Http\Controllers\Web\RentalController::class, 'index'])->name('rentals.index');
    Route::post('/rentals/{rental}/review', [App\Http\Controllers\Web\ReviewController::class, 'store'])->name('reviews.store');

    // Rute profile
    Route::get('/profile', [App\Http\Controllers\Web\ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/upload', [App\Http\Controllers\Web\ProfileController::class, 'uploadImage']);
    Route::post('/profile/update', [App\Http\Controllers\Web\ProfileController::class, 'updateProfile']);
});

Route::get('/abouts', function () {
    return Inertia::render('Web/About/Index');
})->name('abouts.index');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');
