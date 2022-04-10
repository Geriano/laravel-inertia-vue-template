<?php

use Illuminate\Support\Facades\Route;

Route::resource('user', App\Http\Controllers\UserController::class);
Route::prefix('user')->name('user.')->controller(App\Http\Controllers\UserController::class)->group(function () {
  Route::patch('/{user}/recovery', 'recovery')->name('recovery')->withTrashed();
  Route::patch('/{user}/reset-password', 'reset')->name('reset-password');
  Route::patch('/{user}/toggle-permission/{permission}', 'togglePermission')->name('toggle-permission');
  Route::patch('/{user}/toggle-role/{role}', 'toggleRole')->name('toggle-role');
});

Route::resource('permission', App\Http\Controllers\PermissionController::class)->only(['index', 'store', 'destroy']);
Route::resource('role', App\Http\Controllers\RoleController::class)->except(['show']);
Route::patch('/role/{role}/detach', [App\Http\Controllers\RoleController::class, 'detach'])->name('role.detach');

Route::prefix('/menu')->name('menu.')->controller(App\Http\Controllers\MenuController::class)->group(function () {
  Route::patch('/swap', 'swap')->name('swap');
  Route::patch('/{menu}/remove-parent', 'removeParent')->name('remove-parent');
  Route::patch('/{menu}/set-parent', 'setParent')->name('set-parent');
});
Route::resource('menu', App\Http\Controllers\MenuController::class)->except(['create', 'show']);

Route::get('/data-table', [App\Http\Controllers\Examples\DataTableController::class, 'index'])->name('data-table');
Route::prefix('/element')->name('element.')->controller(App\Http\Controllers\Examples\Element::class)->group(function () {
  Route::get('general', 'general')->name('general');
});