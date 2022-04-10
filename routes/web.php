<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/', fn () => Inertia::render('Dashboard'))->name('dashboard');

    Route::middleware(['role:superuser'])->prefix('superuser')->name('superuser.')->group(fn () => require __DIR__ . '/web/superuser.php');
});

Route::get('/test', [App\Http\Controllers\Test::class, 'index']);