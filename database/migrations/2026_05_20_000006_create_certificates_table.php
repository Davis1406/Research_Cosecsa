<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatesTable extends Migration
{
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('trainee_id');
            $table->string('event_name');
            $table->string('venue')->nullable();
            $table->string('event_date');
            $table->unsignedInteger('issued_by');
            $table->string('course_name')->default('Fundamentals of Surgical Research Course');
            $table->string('sig1_name')->nullable();
            $table->string('sig1_title')->nullable();
            $table->string('sig1_path')->nullable();
            $table->string('sig2_name')->nullable();
            $table->string('sig2_title')->nullable();
            $table->string('sig2_path')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            $table->foreign('trainee_id')->references('id')->on('trainees')->onDelete('cascade');
            $table->foreign('issued_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificates');
    }
}
