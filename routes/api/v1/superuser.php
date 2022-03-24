<?php

use Illuminate\Support\Facades\Route;

Route::post('/data-table', [App\Http\Controllers\Examples\DataTableController::class, 'data'])->name('data-table');