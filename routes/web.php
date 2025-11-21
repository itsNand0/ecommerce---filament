<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfileController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layout');
});

Route::get('/categoria/{slug}', function($slug) {
    $category = Category::where('slug', $slug)->first();
    return view('category', ['category' => $category]);
});

Route::get('/producto/{slug}', function($slug) {
    $product = Product::where('slug', $slug)->with('category')->first();
    return view('product', ['product' => $product]);
});

// Rutas del carrito
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/agregar', [CartController::class, 'add'])->name('cart.add');
Route::patch('/carrito/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/carrito', [CartController::class, 'clear'])->name('cart.clear');

// Rutas protegidas
Route::middleware('auth')->group(function () {
    // Dashboard redirige a mi cuenta
    Route::get('/dashboard', function() {
        return redirect()->route('account.index');
    })->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas de cuenta y favoritos
    Route::get('/mi-cuenta', function() {
        return view('account.index');
    })->name('account.index');
    
    Route::get('/favoritos', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favoritos/{product}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::delete('/favoritos/{id}', [FavoriteController::class, 'remove'])->name('favorites.remove');
    
    // Rutas de checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/pedido/{order}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');
});

require __DIR__.'/auth.php';
