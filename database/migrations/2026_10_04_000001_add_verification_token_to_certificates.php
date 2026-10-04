<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddVerificationTokenToCertificates extends Migration
{
    public function up()
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->string('verification_token', 64)->nullable()->unique()->after('auto_generated');
        });

        // Backfill existing certificates with a unique verification token so
        // every previously-issued certificate is verifiable via QR straight away.
        DB::table('certificates')->orderBy('id')->chunkById(200, function ($certificates) {
            foreach ($certificates as $cert) {
                DB::table('certificates')->where('id', $cert->id)->update([
                    'verification_token' => Str::random(32),
                ]);
            }
        });
    }

    public function down()
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropUnique(['verification_token']);
            $table->dropColumn('verification_token');
        });
    }
}