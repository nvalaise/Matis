<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_accounts', function (Blueprint $table) {
            $table->string('pseudo', 100);

            $table->integer('deezer_id')->unsigned()->nullable();
            $table->foreign('deezer_id')->references('id')->on('users');

            $table->integer('spotify_id')->unsigned()->nullable();
            $table->foreign('spotify_id')->references('id')->on('users');

            $table->integer('discogs_id')->unsigned()->nullable();
            $table->foreign('discogs_id')->references('id')->on('users');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_accounts');
    }
}
