<?php

namespace App\Providers;

use App\Repositories\LastFmRepository;
use App\Services\LastFm;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->scoped(LastFmRepository::class, function () {
            return new LastFmRepository(env('LASTFM_API_URL'), env('LASTFM_API_KEY'));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
