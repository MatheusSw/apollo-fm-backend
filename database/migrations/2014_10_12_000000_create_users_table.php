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
            $table->string('report_text')->nullable()->default("This week I played {artists}");
            $table->string('report_day')->default("Sunday");
            $table->timeTz('report_time')->default("18:00:00-03:00");
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
