<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\FlowController;
use \App\Http\Controllers\TagController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/user', [App\Http\Controllers\Auth\AuthController::class, 'user'])->name('user');
// Route::post('/login', [App\Http\Controllers\Auth\AuthController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::apiResources([
        'flows' => FlowController::class,
        'tags' => TagController::class
    ]);

    Route::get('/incomes', [FlowController::class, 'getIncomes']);
    Route::get('/expenses', [FlowController::class, 'getExpenses']);
});
