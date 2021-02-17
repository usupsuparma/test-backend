<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PresenceController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'jwt.verify',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware([JwtMiddleware::class]);
    Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware([JwtMiddleware::class]);;
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);

});

Route::group([
    'middleware' => 'jwt.verify',
    'prefix' => 'presence'
], function ($route) {
    Route::post('/in', [PresenceController::class, 'presenceIn']);
    Route::post('/out', [PresenceController::class, 'presenceOut']);
    Route::get('/detail{$id}', [PresenceController::class, 'detail']);
    Route::get('/details', [PresenceController::class, 'details']);
    Route::get('/cek-status', [PresenceController::class, 'cekStatus']);
});
