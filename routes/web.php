<?php

use Illuminate\Support\Facades\Route;
use NeputerTech\GetpayGateway\Controllers\GetpayController;

Route::group(['prefix' => 'getpay'], function () {
    Route::get('/checkout', [GetpayController::class, 'checkout'])->name('getpay.checkout');

    Route::get('/payment', [GetpayController::class, 'payment'])->name('getpay.payment');
});
