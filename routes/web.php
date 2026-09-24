<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\TripController as CustomerTripController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\PaymentController as CustomerPaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', HomeController::class)->name('home');

Route::get('/dashboard', function () {
    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('customer.dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/perjalanan', [
    CustomerTripController::class,
    'index',
])->name('customer.trips.index');

Route::get('/api/cities/suggestion', [
    CustomerTripController::class,
    'citySuggestions',
])->name('api.cities.suggestion');

Route::get('/perjalanan/{route}', [
    CustomerTripController::class,
    'show',
])->name('customer.trips.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ADMIN
 Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard menggunakan Controller (Hapus Route::view yang lama)
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        // Settings (Untuk menghilangkan hardcode)
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])
            ->name('settings.index');
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])
            ->name('settings.update');

        // Resource Controllers
        Route::resource('buses', \App\Http\Controllers\Admin\BusController::class);
        Route::resource('routes', \App\Http\Controllers\Admin\RouteController::class);

        // Customers
        Route::get('customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])
            ->name('customers.index');

        // Orders
        Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])
            ->name('orders.index');
        Route::get('orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])
            ->name('orders.show');
        Route::patch('orders/qr/check-in', [\App\Http\Controllers\Admin\OrderController::class, 'checkInByQr'])
            ->name('orders.qr.check-in');
        Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])
            ->name('orders.status');
        Route::patch('orders/{order}/cancel', [\App\Http\Controllers\Admin\OrderController::class, 'cancel'])
            ->name('orders.cancel');
        Route::patch('orders/{order}/check-in', [\App\Http\Controllers\Admin\OrderController::class, 'checkIn'])
            ->name('orders.check-in');
        Route::patch('orders/{order}/release-seats', [\App\Http\Controllers\Admin\OrderController::class, 'releaseSeats'])
            ->name('orders.release-seats');
        Route::delete('orders/{order}/archive', [\App\Http\Controllers\Admin\OrderController::class, 'archive'])
            ->name('orders.archive');

        // Payments
        Route::get('payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])
            ->name('payments.index');
        Route::get('payments/{payment}', [\App\Http\Controllers\Admin\PaymentController::class, 'show'])
            ->name('payments.show');
        Route::patch('payments/{payment}/verify', [\App\Http\Controllers\Admin\PaymentController::class, 'verify'])
            ->name('payments.verify');
        Route::patch('payments/{payment}/reject', [\App\Http\Controllers\Admin\PaymentController::class, 'reject'])
            ->name('payments.reject');
    });

    Route::middleware(['auth', 'customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', [
            CustomerDashboardController::class,
            'index',
        ])->name('dashboard');

        Route::get('/perjalanan/{route}/pesan', [
            CustomerBookingController::class,
            'create',
        ])->name('bookings.create');

        Route::post('/bookings', [
            CustomerBookingController::class,
            'store',
        ])->name('bookings.store');

        Route::get('/orders', [
            CustomerOrderController::class,
            'index',
        ])->name('orders.index');

        Route::get('/orders/{order}', [
            CustomerOrderController::class,
            'show',
        ])->name('orders.show');

        Route::get('/orders/{order}/ticket', [
            CustomerOrderController::class,
            'ticket',
        ])->name('orders.ticket');

        Route::get('/orders/{order}/ticket/qr', [
            CustomerOrderController::class,
            'downloadTicketQr',
        ])->name('orders.ticket.qr');

        Route::patch('/orders/{order}/cancel', [
            CustomerOrderController::class,
            'cancel',
        ])->name('orders.cancel');

        Route::post('/orders/{order}/review', [
            CustomerOrderController::class,
            'review',
        ])->name('orders.review');

        Route::get('/orders/{order}/payment', [
            CustomerPaymentController::class,
            'create',
        ])->name('payments.create');

        Route::post('/orders/{order}/payment', [
            CustomerPaymentController::class,
            'store',
        ])->name('payments.store');
    });

require __DIR__.'/auth.php';
