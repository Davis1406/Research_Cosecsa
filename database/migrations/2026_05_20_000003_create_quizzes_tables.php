<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizzesTables extends Migration
{
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('schedule_id')->nullable();
            $table->unsignedInteger('created_by');
            $table->integer('time_limit')->nullable();
            $table->integer('pass_score')->default(60);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('quiz_id');
            $table->text('question_text');
            $table->string('type'); // multiple_choice, true_false, short_answer
            $table->integer('points')->default(1);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
        });

        Schema::create('quiz_options', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('question_id');
            $table->string('option_text');
            $table->boolean('is_correct')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('question_id')->references('id')->on('quiz_questions')->onDelete('cascade');
        });

        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('quiz_id');
            $table->unsignedInteger('user_id');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('score')->nullable();
            $table->boolean('passed')->nullable();
            $table->timestamps();

            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('attempt_id');
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('selected_option_id')->nullable();
            $table->text('text_answer')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->timestamps();

            $table->foreign('attempt_id')->references('id')->on('quiz_attempts')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('quiz_questions')->onDelete('cascade');
            $table->foreign('selected_option_id')->references('id')->on('quiz_options')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('quiz_answers');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_options');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
    }
}
