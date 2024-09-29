<?php

use App\Http\Controllers\{DataController, JobController};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/phpinfo', function () {
    return phpinfo();
});

Route::controller(DataController::class)
    ->prefix('data')
    ->name('data.')
    ->group(function () {
        Route::get('/', 'search')->name('search');
        Route::put('/', 'refresh')->name('refresh');
        Route::delete('/', 'delete')->name('delete');
    });

Route::get('/jobs', [JobController::class, 'list']);
