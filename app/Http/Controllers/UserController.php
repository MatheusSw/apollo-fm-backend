<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LastFm;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class UserController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected LastFm $lastFmService;

    public function __construct(LastFm $lastFmService)
    {
        $this->lastFmService = $lastFmService;
    }

    public function update(Request $request)
    {
        $user = User::findOrFail($request->user()->twitter_id);
        Log::info('Updating settings for user', ['user' => $user, 'request' => $request->all()]);

        $user->report_text = $request->report_text;
        $user->lastfm_user = $request->lastfm_user;
        $user->report_day = $request->report_day;
        $user->report_time = $request->report_time;

        if ((is_null($user->monthly_artists) || is_null($user->monthly_loved_tracks) || is_null($user->monthly_scrobbles))
            && !is_null($user->lastfm_user)) {
            $monthly_stats = $this->lastFmService->get_monthly_stats($user->lastfm_user);
            Log::info('Updating monthly stats for user', ['stats' => $monthly_stats]);
            $user->monthly_loved_tracks = $monthly_stats['total_loved_tracks'];
            $user->monthly_scrobbles = $monthly_stats['play_count'];
            $user->monthly_artists = $monthly_stats['top_artists'];
        }
        $result = $user->save();
        return response('', $result ? 204 : 500);
    }

    public function show(Request $request)
    {
        return $request->user();
    }
}
