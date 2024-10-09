<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\AppointmentController;

Route::post('/book', [AppointmentController::class, 'book'])->name('book');
Route::post('/end-booking/{id}', [AppointmentsController::class, 'endBooking'])->name('endBooking');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

use App\Http\Controllers\StripePaymentController;

Route::controller(StripePaymentController::class)->group(function(){
    Route::get('success', 'success')->name('success');
    Route::get('cancel', 'cancel')->name('cancel');
    Route::post('stripe', 'stripe')->name('stripe.create');
});

Route::get('/stripe-checkout', [StripePaymentController::class, 'checkout'])->name('stripe.checkout');
