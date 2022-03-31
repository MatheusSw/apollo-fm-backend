<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/logins/twitter', fn(Request $request) => Socialite::driver('twitter')->redirect());

Route::get('/callbacks/twitter', function (Request $request) {
    $twitterUser = Socialite::driver('twitter')->user();

    $user = User::updateOrCreate([
        'twitter_id' => $twitterUser->getId(),
    ], [
        'name' => $twitterUser->getName(),
        'screen_name' => $twitterUser->getNickname(),
        'profile_picture_url' => $twitterUser->avatar_original,
        'access_token' => $twitterUser->token,
        'access_token_secret' => $twitterUser->tokenSecret,
    ]);

    Log::debug("Login-in user", ["user" => $user]);
    Auth::login($user);

    return redirect(env('APP_URL'));
});
