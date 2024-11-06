<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;


Route::get('/', [MainController::class,'index']);
Route::get('/product', [MainController::class,'allProduct']);
Route::get('/product/{id}', [MainController::class,'showProduct']);
Route::get('/checkout', [MainController::class,'checkout'])->middleware('auth');
Route::post('/process', [MainController::class,'process'])->middleware(middleware: 'auth');
Route::post('/checkout', [OrderController::class,'process'])->middleware('auth');
Route::get('/payment/{id}', [OrderController::class,'payment'])->middleware('auth');
Route::get('/history', [OrderController::class,'history'])->middleware('auth');
Route::get('/checkout/success/{id}', [OrderController::class, 'success'])->middleware('auth');

Route::get('/login', [AuthController::class,'loginView'])->middleware('guest');
Route::post('/login', [AuthController::class,'login'])->name('login');
Route::get('/register', [AuthController::class,'registerView'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class,'register'])->name('register');
Route::get('/logout', [AuthController::class,'logout'])->name('logout');