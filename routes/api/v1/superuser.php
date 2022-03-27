<?php

use Illuminate\Support\Facades\Route;

Route::post('/user/paginate', [App\Http\Controllers\UserController::class, 'paginate'])->name('user.paginate');
Route::post('/data-table', [App\Http\Controllers\Examples\DataTableController::class, 'data'])->name('data-table');