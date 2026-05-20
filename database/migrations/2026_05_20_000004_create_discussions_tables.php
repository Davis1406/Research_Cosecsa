<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscussionsTables extends Migration
{
    public function up()
    {
        Schema::create('discussions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('body');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('schedule_id')->nullable();
            $table->boolean('is_general')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('set null');
        });

        Schema::create('discussion_replies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('discussion_id');
            $table->unsignedInteger('user_id');
            $table->text('body');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('discussion_id')->references('id')->on('discussions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('discussion_replies');
        Schema::dropIfExists('discussions');
    }
}
