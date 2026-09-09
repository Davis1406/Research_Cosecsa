<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingMaterialViewsTable extends Migration
{
    public function up()
    {
        Schema::create('training_material_views', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('training_material_id');
            $table->timestamp('viewed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('training_material_id')->references('id')->on('training_materials')->onDelete('cascade');
            $table->unique(['user_id', 'training_material_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('training_material_views');
    }
}
