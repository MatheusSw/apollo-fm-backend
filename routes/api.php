<?php

use App\Http\Controllers\UserController;
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
Route::get('foo', fn() => 'bar');

Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'show']);
    Route::put('settings', [UserController::class, 'update']);
});

Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    //For some reason response('')->withoutCookie doesn't work :/
    \Illuminate\Support\Facades\Cookie::expire('apollo_fm_session');
    return response('');
});

