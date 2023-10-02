<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;
use App\Models\Seller;

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

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin');

// -------------------------Products Route--------------------

// Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');

// Route::post('/products', [ProductController::class, 'store'])->name('products.store');

// Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');

// Route::patch('/products/{id}', [ProductController::class, 'update'])->name('products.update');

// Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

// Route::resource('products', ProductController::class);

// -------------------------Categories Route--------------------

// Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

// Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');

// Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');

// Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');

// Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');

// Route::patch('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');

// Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

// Route::resource('categories', CategoryController::class);

Route::resources([
    'products' => ProductController::class,
    'categories' => CategoryController::class,
]);

Route::middleware('auth')->prefix('admin')->group(function () {
    // Route::get('/sellers', [SellerController::class, 'index'])->name('sellers.index');

    // Route::get('/sellers/create', [SellerController::class, 'create'])->name('sellers.create');

    // Route::post('/sellers/store', [SellerController::class, 'store'])->name('sellers.store');

    // Route::get('/sellers/{id}', [SellerController::class, 'show'])->name('sellers.show');

    // Route::get('/sellers/{id}/edit', [SellerController::class, 'edit'])->name('sellers.edit');

    // Route::patch('/sellers/{id}', [SellerController::class, 'update'])->name('sellers.update');

    // Route::delete('/sellers/{id}', [SellerController::class, 'destroy'])->name('sellers.destroy');

    Route::resource('sellers', SellerController::class);
});
