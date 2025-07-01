<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authenticate;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/login',[UserController::class,'login'])->name('users.login');
Route::post('/login',[UserController::class,'userlogin'])->name('login.submit');;
Route::get('/register',[UserController::class,'register'])->name('register.view');
Route::post('/register',[UserController::class,'submit'])->name('register');
Route::post('/logout',[UserController::class,'logout'])->name('user.logout');


Route::get('/verify-email/{id}', function ($id, Request $request) {
    if (!$request->hasValidSignature()) {
        return redirect()->route('users.login')->with('error', 'Invalid or expired verification link.');
    }

    $user = User::findOrFail($id);

    if ($user->email_verified_at) {
        return redirect()->route('users.login')->with('info', 'Email already verified.');
    }

    $user->email_verified_at = now();
    $user->save();

    return redirect()->route('users.login')->with('success', 'Email verified! You can now log in.');
})->name('verify.email')->middleware('signed');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');


Route::middleware(Authenticate::class,'verified')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/addproduct', [ProductController::class, 'add'])->name('product.add');
    Route::post('/product/submit', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::get('/product/delete/{id}', [ProductController::class, 'delete'])->name('product.delete');
});

