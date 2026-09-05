<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('trainees', function (Blueprint $table) {
            $table->string('course_type')->default('physical')->after('id')->index();
        });
    }

    public function down()
    {
        Schema::table('trainees', function (Blueprint $table) {
            $table->dropColumn('course_type');
        });
    }
};
