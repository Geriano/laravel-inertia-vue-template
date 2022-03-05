<?php

use Illuminate\Support\Facades\Route;

Route::resource('user', App\Http\Controllers\UserController::class)->except(['show']);
Route::prefix('user')->name('user.')->controller(App\Http\Controllers\UserController::class)->group(function () {
  Route::get('/{user}/profile', 'profile')->name('profile');
  Route::patch('/{user}/recovery', 'recovery')->name('recovery')->withTrashed();
  Route::patch('/{user}/reset-password', 'reset')->name('reset-password');
  Route::patch('/{user}/toggle-permission/{permission}', 'togglePermission')->name('toggle-permission');
  Route::patch('/{user}/toggle-role/{role}', 'toggleRole')->name('toggle-role');
});

Route::resource('permission', App\Http\Controllers\PermissionController::class)->only(['index', 'store', 'destroy']);
Route::resource('role', App\Http\Controllers\RoleController::class)->except(['show']);
Route::patch('/role/{role}/detach', [App\Http\Controllers\RoleController::class, 'detach'])->name('role.detach');