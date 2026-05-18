<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimetableFieldsToSchedulesTable extends Migration
{
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->date('date')->nullable()->after('day_number');
            $table->time('end_time')->nullable()->after('start_time');
            $table->string('location')->nullable()->after('subtitle');
        });
    }

    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn(['date', 'end_time', 'location']);
        });
    }
}
