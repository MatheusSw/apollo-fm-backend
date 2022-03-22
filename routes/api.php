<?php

use App\Models\User;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();

    return redirect(env('LOCAL_FRONT_END_URL') . '/login');
});

Route::middleware('auth:sanctum')->put('/settings', function (Request $request) {
    $user = User::find($request->user()->twitter_id);

    $user->report_text = $request->report_text;
    $user->lastfm_user = $request->lastfm_user;
    $user->report_day = $request->report_day;
    $user->report_time = $request->report_time;
    $result = $user->save();

    return response('', $result ? 204 : 500);
});
