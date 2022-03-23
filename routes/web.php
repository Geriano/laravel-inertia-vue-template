<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard');

    Route::middleware(['role:superuser'])->prefix('superuser')->name('superuser.')->group(fn () => require __DIR__ . '/web/superuser.php');
});

Route::any('/icon/{file:string}/{r:int}/{g:int}/{b:int}', [App\Http\Controllers\Icon::class, 'rgba'])->name('icon.rgb');
Route::any('/icon/{file:string}/{r:int}/{g:int}/{b:int}/{a:float}', [App\Http\Controllers\Icon::class, 'rgba'])->name('icon.rgba');

Route::get('/test', [App\Http\Controllers\Test::class, 'index']);