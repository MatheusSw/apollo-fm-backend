<?php

namespace App\Services;

use App\Repositories\LastFmRepository;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\ArrayShape;

class LastFm
{
    const WEEKLY_REPORT_LIMIT = ['limit' => 5];
    private LastFmRepository $lastFmRepository;

    public function __construct(LastFmRepository $lastFmRepository)
    {
        $this->lastFmRepository = $lastFmRepository;
    }

    #[ArrayShape(['total_loved_tracks' => "int", 'play_count' => "int", 'top_artists' => "int"])] public function get_monthly_stats($username): array
    {
        return $this->lastFmRepository->get_monthly_stats($username);
    }

    protected function get_report_text_placeholders($report_text): array {
        if(preg_match_all('/{([artists|albums|tracks]+)}/',$report_text, $matches) !== false){
            return $matches[1];
        }
        return [];
    }

    public function parse_report_text($username, $report_text){
        Log::info("Parsing report text", ['user' => $username, 'report_text' => $report_text]);

        $placeholders = $this->get_report_text_placeholders($report_text);
        if(empty($placeholders)){
            return false;
        }
        Log::debug('Placeholders found in user report text', ['place_holders' => $placeholders]);
        foreach ($placeholders as $placeholder){
            switch ($placeholder){
                case 'artists':
                    $artists_report = $this->get_weekly_artists_report($username);
                    $report_text = preg_replace('/{artists}/', $artists_report, $report_text);
                    break;
                case 'albums':
                    $albums_report = $this->get_weekly_albums_report($username);
                    $report_text = preg_replace('/{albums}/', $albums_report, $report_text);
                    break;
                case 'tracks':
                    $tracks_report = $this->get_weekly_tracks_report($username);
                    $report_text = preg_replace('/{tracks}/', $tracks_report, $report_text);
                    break;
                default:
                    break;
            }
        }
        Log::debug('Parsed report text', ['report_text' => $report_text]);
        return $report_text;
    }

    protected function format_weekly_text($name, $value): string{
        return $name. '(' . $value . ')';
    }

    private function get_weekly_artists_report($username){
        $weekly_artists = $this->lastFmRepository->get_weekly_artists($username, self::WEEKLY_REPORT_LIMIT);
        Log::debug('Weekly artists found for user', ['user' => $username, 'artists' => $weekly_artists]);
        $artists = $weekly_artists['weeklyartistchart']['artist'];
        $text = [];
        foreach ($artists as $artist){
            $text[] = $this->format_weekly_text($artist['name'], $artist['playcount']);;
        }
        return implode(' ', $text);
    }

    private function get_weekly_albums_report($username){
        $weekly_albums = $this->lastFmRepository->get_weekly_albums($username, self::WEEKLY_REPORT_LIMIT);
        Log::debug('Weekly albums found for user', ['user' => $username, 'albums' => $weekly_albums]);

        $albums = $weekly_albums['weeklyalbumchart']['album'];
        $text = [];
        foreach ($albums as $album){
            $text[] = $this->format_weekly_text($album['name'], $album['playcount']);;
        }
        return implode(' ', $text);
    }

    private function get_weekly_tracks_report($username){
        $weekly_tracks = $this->lastFmRepository->get_weekly_tracks($username, self::WEEKLY_REPORT_LIMIT);
        Log::debug('Weekly tracks found for user', ['user' => $username, 'tracks' => $weekly_tracks]);
        $tracks = $weekly_tracks['weeklytrackchart']['track'];
        $text = [];
        foreach ($tracks as $track){
            $text[] = $this->format_weekly_text($track['name'], $track['playcount']);;
        }
        return implode(' ', $text);
    }
}
