<?php

use Illuminate\Support\Facades\Route;

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

// Homepage: List all articles
Route::get('/', [App\Http\Controllers\ShopController::class, 'articlesHome'])->name('shop.home');
// Route::redirect('/', '/admin');

// Checkout page for an article
Route::get('/checkout/{article}', [App\Http\Controllers\ShopController::class, 'checkout'])->name('shop.checkout');

// Order submission (POST)
Route::post('/checkout/{article}', [App\Http\Controllers\ShopController::class, 'orderSubmit'])->name('shop.order.submit');
