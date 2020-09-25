<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupMessageFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_message_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_message_id')->index();
            $table->string('file');
            $table->string('file_path');

            $table->foreign('group_message_id')->references('id')->on('group_messages')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_message_files', function (Blueprint $table) {
            $table->dropForeign(['group_message_id']);
        });

        Schema::dropIfExists('group_message_files');
    }
}
