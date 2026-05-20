<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresentationReviewersTable extends Migration
{
    public function up()
    {
        Schema::create('presentation_reviewers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('trainee_document_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
            $table->unique(['trainee_document_id', 'user_id']);
            $table->foreign('trainee_document_id')->references('id')->on('trainee_documents')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('presentation_reviewers');
    }
}
