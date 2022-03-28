<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $twitter_id
 * @property string $name
 * @property string $screen_name
 * @property string $profile_picture_url
 * @property string $access_token
 * @property string $access_token_secret
 * @property string|null $lastfm_user
 * @property string|null $report_text
 * @property string $report_schedule
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAccessTokenSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastfmUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePictureUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereReportSchedule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereReportText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereScreenName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwitterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $report_day
 * @property string $report_time
 * @method static \Illuminate\Database\Eloquent\Builder|User whereReportDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereReportTime($value)
 * @property int $monthly_scrobbles
 * @property int $monthly_loved_tracks
 * @property int $monthly_artists
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMonthlyArtists($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMonthlyLovedTracks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMonthlyScrobbles($value)
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $incrementing = false;
    protected $primaryKey = 'twitter_id';
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'screen_name', 'profile_picture_url', 'twitter_id', 'access_token', 'access_token_secret', 'lastfm_user'];

    /*
     * Guarded attributes
     * */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
        'access_token',
        'access_token_secret',
        'updated_at',
        'created_at'
    ];
}
