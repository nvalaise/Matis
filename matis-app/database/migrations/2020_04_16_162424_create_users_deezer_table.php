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
            $table->unsignedBigInteger('id');
            $table->foreign('id')->references('id')->on('users');
            
            $table->integer('deezerId')->unique();
            $table->string('email')->unique();
            
            $table->string('name')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->integer('status')->nullable();
            $table->date('inscriptionDate')->nullable();
            $table->string('profileLink')->nullable();
            $table->string('picture')->nullable();
            $table->string('country')->nullable();
            $table->string('lang')->nullable();
            $table->boolean('isKid')->nullable();
            $table->string('tracklist')->nullable();

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
