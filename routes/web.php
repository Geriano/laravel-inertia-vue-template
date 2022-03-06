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

Route::get('/mfs', function () {
    Artisan::call('migrate:fresh', [
        '--seed' => true,
    ]);

    return redirect()->route('login');
});

Route::get('/trc', function () {
    Illuminate\Support\Facades\DB::table('users')->truncate();
    Illuminate\Support\Facades\DB::table('password_resets')->truncate();
    Illuminate\Support\Facades\DB::table('failed_jobs')->truncate();
    Illuminate\Support\Facades\DB::table('personal_access_tokens')->truncate();
    Illuminate\Support\Facades\DB::table('teams')->truncate();
    Illuminate\Support\Facades\DB::table('team_user')->truncate();
    Illuminate\Support\Facades\DB::table('team_invitations')->truncate();
    Illuminate\Support\Facades\DB::table('sessions')->truncate();
    Illuminate\Support\Facades\DB::table('permissions')->truncate();
    Illuminate\Support\Facades\DB::table('roles')->truncate();
    Illuminate\Support\Facades\DB::table('model_has_permissions')->truncate();
    Illuminate\Support\Facades\DB::table('model_has_roles')->truncate();
    Illuminate\Support\Facades\DB::table('role_has_permissions')->truncate();
    Illuminate\Support\Facades\DB::table('menus')->truncate();
    Illuminate\Support\Facades\DB::table('menu_permission')->truncate();
    Illuminate\Support\Facades\DB::table('migrations')->truncate();

    return redirect()->to(url('/mfs'));
});