<?php

use Illuminate\Support\Facades\Route;

Route::resource('user', App\Http\Controllers\UserController::class)->except(['show']);
Route::patch('/user/{id}/restore', [App\Http\Controllers\UserController::class, 'restore'])->name('user.restore');
Route::patch('/user/{id}/reset-password', [App\Http\Controllers\UserController::class, 'reset'])->name('user.reset-password');
Route::resource('permission', App\Http\Controllers\PermissionController::class)->only(['index', 'store', 'destroy']);