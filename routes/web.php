<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');


Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth', 'verified'],
], function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::group([
        'as' => 'admin.',
    ], function () {
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

        Route::resource('projects', \App\Http\Controllers\Admin\ProjectController::class);
    });
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
