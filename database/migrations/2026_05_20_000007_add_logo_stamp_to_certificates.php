<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogoStampToCertificates extends Migration
{
    public function up()
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('sig2_path');
            $table->string('stamp_path')->nullable()->after('logo_path');
            $table->string('org_name')->nullable()->after('stamp_path')->default('College of Surgeons of East, Central & Southern Africa');
        });
    }

    public function down()
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn(['logo_path', 'stamp_path', 'org_name']);
        });
    }
}
