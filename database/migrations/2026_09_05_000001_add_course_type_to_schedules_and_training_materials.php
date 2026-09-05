<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->string('course_type')->default('physical')->after('id')->index();
        });

        Schema::table('training_materials', function (Blueprint $table) {
            $table->string('course_type')->default('physical')->after('id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('course_type');
        });

        Schema::table('training_materials', function (Blueprint $table) {
            $table->dropColumn('course_type');
        });
    }
};
