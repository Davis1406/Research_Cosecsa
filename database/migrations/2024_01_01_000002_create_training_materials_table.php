<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingMaterialsTable extends Migration
{
    public function up()
    {
        Schema::create('training_materials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('category')->nullable(); // e.g. Surgery, Anatomy, Clinical Skills
            $table->string('type')->default('document'); // document, presentation, video, image
            $table->text('description')->nullable();
            $table->string('external_url')->nullable();
            $table->unsignedInteger('speaker_id')->nullable();
            $table->foreign('speaker_id')->references('id')->on('speakers')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('training_materials');
    }
}
