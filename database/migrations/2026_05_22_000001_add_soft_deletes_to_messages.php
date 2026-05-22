<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToMessages extends Migration
{
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
