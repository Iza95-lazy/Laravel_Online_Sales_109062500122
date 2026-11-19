<?php
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductCatalogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\AuthController; 
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/shop', function () {
    return 'Halaman Shop (Dummy)';
})->name('shop.index');

Route::middleware('guest')->group(function () { 
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login'); 
    Route::post('/login', [AuthController::class, 'login'])->name('login.store'); 
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register'); 
    Route::post('/register', [AuthController::class, 'register'])->name('register.store'); 
}); 
 
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard'); // Sesuaikan dengan view admin Anda
    })->name('admin.dashboard');
});

Route::prefix('admin')->name('admin.')->middleware(['auth','role:admin'])->group(function () { 
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index'); 
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store'); 
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update'); 
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy'); 
    Route::resource('products', ProductController::class)->except('show'); 
    Route::get('/stock', [App\Http\Controllers\Admin\StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/create', [App\Http\Controllers\Admin\StockController::class, 'create'])->name('stock.create');
    Route::post('/stock', [App\Http\Controllers\Admin\StockController::class, 'store'])->name('stock.store');
    Route::resource('shippings', App\Http\Controllers\Admin\ShippingServiceController::class);
});

Route::middleware(['auth','role:customer'])->group(function () { 
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index'); 
    Route::post('/keranjang/{product}', [CartController::class, 'store'])->name('cart.store'); 
    Route::patch('/keranjang/{product}', [CartController::class, 'update'])->name('cart.update'); 
    Route::delete('/keranjang/{product}', [CartController::class, 'destroy'])->name('cart.destroy'); 
}); 

Route::get('/', [ProductCatalogController::class, 'index'])->name('shop.index');
Route::get('/produk/{product:slug}', [ProductCatalogController::class, 'show'])->name('shop.show');

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login')->with('success', 'Berhasil logout!');
})->name('logout');