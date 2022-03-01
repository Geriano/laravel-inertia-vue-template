<?php

use Illuminate\Support\Facades\Route;

Route::prefix('superuser')->name('superuser.')->group(fn () => require __DIR__ . '/v1/superuser.php');