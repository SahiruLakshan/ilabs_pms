<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::get('/login',[UserController::class,'login'])->name('users.login');
Route::post('/login',[UserController::class,'userlogin'])->name('login.submit');;
Route::get('/register',[UserController::class,'register'])->name('register.view');
Route::post('/register',[UserController::class,'submit'])->name('register');
Route::post('/logout',[UserController::class,'logout'])->name('user.logout');

Route::middleware(Authenticate::class)->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/addproduct', [ProductController::class, 'add'])->name('product.add');
    Route::post('/product', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::get('/product/delete/{id}', [ProductController::class, 'delete'])->name('product.delete');
});

