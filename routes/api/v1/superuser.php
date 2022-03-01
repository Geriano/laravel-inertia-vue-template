<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user/{id}/find', fn (int $id) => App\Models\User::with(['roles', 'permissions'])->findOrFail($id))->name('user.find');
Route::patch('/user/{id}/reset-password', [App\Http\Controllers\UserController::class, 'reset'])->name('user.reset-password');
Route::patch('/user/toggle-permission', [App\Http\Controllers\UserController::class, 'togglePermission'])->name('user.toggle-permission');
Route::patch('/user/toggle-role', [App\Http\Controllers\UserController::class, 'toggleRole'])->name('user.toggle-role');