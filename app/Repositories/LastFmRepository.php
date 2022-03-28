<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\ArrayShape;

class LastFmRepository
{
    protected string $base_url;
    protected string $api_key;

    public function __construct($base_url, $api_key)
    {
        $this->base_url = $base_url;
        $this->api_key = $api_key;
    }

    #[ArrayShape(['total_loved_tracks' => "int", 'play_count' => "int", 'top_artists' => "int"])] public function get_monthly_stats($username): array
    {
        //TODO Pool with Http::pool
        $total_loved_tracks = $this->get_loved_tracks($username, ['limit' => 1])['lovedtracks']['@attr']['total'];
        $play_count = $this->get_user_info($username)['user']['playcount'];
        $top_artists = $this->get_top_artists($username, ['limit' => 1])['topalbums']['@attr']['total'];

        return array('total_loved_tracks' => $total_loved_tracks,
            'play_count' => $play_count,
            'top_artists' => $top_artists);
    }

    private function get_loved_tracks($user,...$query_parameters)
    {
        Log::debug('Retrieving loved tracks with parameters', ['parameters' => $query_parameters]);
        $loved_tracks_url = $this->make_url('user.getLovedTracks', $user, 'json', $query_parameters);

        $response = Http::acceptJson()->get($loved_tracks_url);
        Log::debug('Loved tracks response', ['response', $response]);

        return $response->throw()->json();
    }

    protected function make_url($method, $user, $format = 'json', ...$additional_parameters): string
    {
        $additional_parameters = array_merge(...$additional_parameters);

        $data = [
            'method' => $method,
            'user' => $user,
            'format' => $format,
            'api_key' => $this->api_key
        ];

        foreach ($additional_parameters as $param) {
            $data += $param;
        }

        return $this->base_url . '?' . http_build_query($data);
    }

    private function get_user_info($user,...$query_parameters)
    {
        Log::debug('Retrieving lastFm user info with parameters', ['parameters' => $query_parameters]);
        $user_info_url = $this->make_url('user.getInfo', $user, 'json', $query_parameters);

        $response = Http::acceptJson()->get($user_info_url);
        Log::debug('LastFm user info response', ['response', $response]);

        return $response->throw()->json();
    }

    private function get_top_artists($user, ...$query_parameters)
    {
        Log::debug('Retrieving top artists with parameters', ['parameters' => $query_parameters]);
        $top_artists_url = $this->make_url('user.getTopAlbums',$user, 'json', $query_parameters);

        $response = Http::acceptJson()->get($top_artists_url);
        Log::debug('LastFm top artists response', ['response', $response]);

        return $response->throw()->json();
    }

    public function get_weekly_artists($user, ...$query_parameters){
        Log::debug('Retrieving weekly artists with parameters', ['parameters' => $query_parameters]);
        $weekly_artists_url = $this->make_url('user.getWeeklyArtistChart', $user, 'json', $query_parameters);

        $response = Http::acceptJson()->get($weekly_artists_url);
        Log::debug('LastFm weekly artists response', ['response', $response]);

        return $response->throw()->json();
    }

    public function get_weekly_albums($user, ...$query_parameters){
        Log::debug('Retrieving weekly albums with parameters', ['parameters' => $query_parameters]);
        $weekly_albums_url = $this->make_url('user.getWeeklyAlbumChart',$user, 'json', $query_parameters);

        $response = Http::acceptJson()->get($weekly_albums_url);
        Log::debug('LastFm weekly albums response', ['response', $response]);

        return $response->throw()->json();
    }

    public function get_weekly_tracks($user, ...$query_parameters){
        Log::debug('Retrieving weekly tracks with parameters', ['parameters' => $query_parameters]);
        $weekly_tracks_url = $this->make_url('user.getWeeklyTrackChart',$user, 'json', $query_parameters);

        $response = Http::acceptJson()->get($weekly_tracks_url);
        Log::debug('LastFm weekly tracks response', ['response', $response]);

        return $response->throw()->json();
    }
}
