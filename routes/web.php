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

// Language switcher
Route::get('/lang/{locale}', [App\Http\Controllers\LocaleController::class, 'switch'])->name('lang.switch');

// Homepage: List all articles
Route::get('/', [App\Http\Controllers\ShopController::class, 'articlesHome'])->name('shop.home');

// Static pages
Route::get('/about', [App\Http\Controllers\ShopController::class, 'about'])->name('shop.about');
Route::get('/contact', [App\Http\Controllers\ShopController::class, 'contact'])->name('shop.contact');
Route::post('/contact', [App\Http\Controllers\ShopController::class, 'contactSubmit'])->name('shop.contact.submit');

// All products, optionally filtered by category
Route::get('/products', [App\Http\Controllers\ShopController::class, 'products'])->name('shop.products');

// Category page (famille or sous-famille)
Route::get('/category/{category}', [App\Http\Controllers\CategoryController::class, 'show'])->name('shop.category');

// Product detail page
Route::get('/product/{article}', [App\Http\Controllers\ShopController::class, 'show'])->name('shop.product');

// Checkout page for an article (quick "Buy Now" flow)
Route::get('/checkout/{article}', [App\Http\Controllers\ShopController::class, 'checkout'])->name('shop.checkout');

// Order submission (POST)
Route::post('/checkout/{article}', [App\Http\Controllers\ShopController::class, 'orderSubmit'])->name('shop.order.submit');

// Cart
Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{article}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{key}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{key}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/checkout', [App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/cart/checkout', [App\Http\Controllers\CartController::class, 'checkoutSubmit'])->name('cart.checkout.submit');
