<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersDeezerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_deezer', function (Blueprint $table) {
            $table->id();
            $table->integer('deezerId')->unique();
            $table->string('email')->unique();
            $table->string('name');
            $table->string('firstname');
            $table->string('lastname');
            $table->integer('status');
            $table->date('inscriptionDate');
            $table->string('profileLink');
            $table->string('picture');
            $table->string('country');
            $table->string('lang');
            $table->boolean('isKid');
            $table->string('tracklist');

            $table->string('accessToken')->nullable();
            $table->boolean('active')->default(1);
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
        Schema::dropIfExists('users_deezer');
    }
}
