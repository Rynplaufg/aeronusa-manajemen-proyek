<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightBookingController;

Route::get('/', [FlightBookingController::class, 'index'])->name('landing');
Route::post('/booking/store', [FlightBookingController::class, 'store'])->name('booking.store');
Route::post('/booking/{pnr}/refund', [FlightBookingController::class, 'refund'])->name('booking.refund');