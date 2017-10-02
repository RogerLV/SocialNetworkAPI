<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SocialNetworkAPI extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email'); // default length 255

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('friends', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('requestorID')->references('id')->on('users');
            $table->integer('targetID')->references('id')->on('users');
            $table->boolean('isBlocked')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('requestorID')->references('id')->on('users');
            $table->integer('targetID')->references('id')->on('users');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
        Schema::drop('friends');
        Schema::drop('subscriptions');
    }
}
