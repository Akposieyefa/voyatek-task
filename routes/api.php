<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api', 'prefix' => 'v1'], function ($router) {

    Route::middleware('voyatek.verify')->group(function () {
        Route::prefix('blogs')->group(function () { // blogs routes
            Route::controller(App\Http\Controllers\Api\BlogController::class)->group(function () {
                Route::post('create', 'store');
                Route::get('all', 'index');
                Route::get('single/{slug}', 'show');
                Route::patch('update/{slug}', 'update');
                Route::delete('delete/{slug}', 'destroy');
            });
        });// blogs routes

        Route::prefix('posts')->group(function () { // post routes
            Route::controller(App\Http\Controllers\Api\PostController::class)->group(function () {
                Route::post('create', 'store');
                Route::get('all', 'index');
                Route::get('single/{slug}', 'show');
                Route::patch('update/{slug}', 'update');
                Route::delete('delete/{slug}', 'destroy');
                Route::post('likes', 'likeMethod');
                Route::post('comments', 'commentMethod');
            });
        });// post routes

    });

});
