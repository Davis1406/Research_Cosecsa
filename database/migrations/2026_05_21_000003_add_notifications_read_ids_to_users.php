<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotificationsReadIdsToUsers extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // JSON string of read notification keys e.g. {"material_5":1,"comment_3":1}
            $table->text('notifications_read_ids')->nullable()->after('notifications_seen_at');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('notifications_read_ids');
        });
    }
}
