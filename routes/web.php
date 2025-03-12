<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to Voyatek Group backend laravel task',
        'data' => [
            'route' => request()->path(),
            'method' => request()->method(),
            'time' => now()->toDateTimeString(),
            'info' => [
                'message' => 'For more details you can reach out to our engineering team',
                'data' => [
                    'email' => 'dev@'. config('app.name'). '.com',
                    'phoneNumber' => '+2348100788859'
                ]
            ]
        ],
        'success' => true
    ],Response::HTTP_OK);
});

