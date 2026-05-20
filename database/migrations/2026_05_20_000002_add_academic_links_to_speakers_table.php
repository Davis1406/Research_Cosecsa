<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcademicLinksToSpeakersTable extends Migration
{
    public function up()
    {
        Schema::table('speakers', function (Blueprint $table) {
            $table->string('researchgate')->nullable()->after('linkedin');
            $table->string('orcid')->nullable()->after('researchgate');
            $table->string('web_of_science')->nullable()->after('orcid');
            $table->string('google_scholar')->nullable()->after('web_of_science');
        });
    }

    public function down()
    {
        Schema::table('speakers', function (Blueprint $table) {
            $table->dropColumn(['researchgate', 'orcid', 'web_of_science', 'google_scholar']);
        });
    }
}
