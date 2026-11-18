<?php
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

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login')->with('success', 'Berhasil logout!');
})->name('logout');