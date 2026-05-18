<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraineeDocumentCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('trainee_document_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('trainee_document_id');
            $table->unsignedInteger('user_id');
            $table->text('comment');
            $table->timestamps();
            $table->foreign('trainee_document_id')->references('id')->on('trainee_documents')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('trainee_documents', function (Blueprint $table) {
            $table->string('title')->nullable()->after('original_name');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trainee_document_comments');
        Schema::table('trainee_documents', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
}
