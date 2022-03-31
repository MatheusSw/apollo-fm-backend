<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('twitter_id')->index();
            $table->string('name');
            $table->string('screen_name');
            $table->string('profile_picture_url');
            $table->string('access_token');
            $table->string('access_token_secret');
            $table->string('lastfm_user')->unique()->nullable();
            $table->string('report_text')->nullable()->default("This week, the following artists had the honor to be on my ears {artists}");
            $table->string('report_day')->default("Sunday");
            $table->bigInteger('monthly_scrobbles')->nullable();
            $table->bigInteger('monthly_loved_tracks')->nullable();
            $table->bigInteger('monthly_artists')->nullable();
            $table->dateTime('report_time')->default("1970-01-01T18:00:00");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
