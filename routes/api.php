<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DisasterController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
  Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
  Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
  Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth:api');
  Route::get('/profile', [AuthController::class, 'profile'])->name('auth.profile')->middleware('auth:api');
});

Route::group(['middleware' => 'auth:api'], function () {
  Route::get('/disasters', [DisasterController::class, 'index']);
  Route::get('/disasters/{id}', [DisasterController::class, 'show']);
  Route::post('/disasters', [DisasterController::class, 'store']);
  Route::put('/disasters/{id}', [DisasterController::class, 'update']);
  Route::delete('/disasters/{id}', [DisasterController::class, 'destroy']);
});