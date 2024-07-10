<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTransactionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// setting route untuk halaman utama dan akses ke method index di class FrontController
Route::get('/', [FrontController::class, 'index'])->name('front.index');
// setting route untuk mencari produk 
Route::get('/search', [FrontController::class, 'search'])->name('front.search');
// setting route untuk melihat detail halaman dengan slug product
Route::get('/details/{product:slug}', [FrontController::class, 'details'])->name('front.product.details');
// setting route untuk melihat produk berdasarkan kategori
Route::get('/category/{category}', [FrontController::class, 'category'])->name('front.product.category');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // route untuk buyer dapat melakukan add to cart, owner tidak bisa
    Route::resource('carts', CartController::class)->middleware('role:buyer');

    // bikin route untuk melihat transaksi yang dilakukan oleh buyer. Owner bisa melihat transaksi berjalan
    Route::resource('product_transactions', ProductTransactionController::class)->middleware('role:owner|buyer');
    // bikin route admin, misal admin/products atau admin/categories dan yang bisa akses route itu cuma owner/admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('products', ProductController::class)->middleware('role:owner');
        Route::resource('categories', CategoryController::class)->middleware('role:owner');
    });
});

require __DIR__ . '/auth.php';
