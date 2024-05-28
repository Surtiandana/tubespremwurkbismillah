<?php

// use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
// Route::get('home', [HomeController::class, 'index'])->name('home');

Route::resource('checkout', CheckoutController::class);
Route::get('/checkout/view', [CheckoutController::class, 'viewOnly'])->name('checkout.view');





