<?php

use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/',  [PortfolioController::class, 'index'])->name('home');
Route::get('/portfolio/all', [PortfolioController::class, 'all'])->name('portfolio.all');
Route::get('/portfolio/{project:slug}', [PortfolioController::class, 'show'])->name('portfolio.show');


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
