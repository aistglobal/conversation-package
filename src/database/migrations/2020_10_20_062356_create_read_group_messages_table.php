<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadGroupMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('read_group_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_message_id')->index();
            $table->unsignedBigInteger('member_id')->index();

            $table->foreign('group_message_id')->references('id')->on('group_messages')->onDelete('cascade');
            $table->index(['group_message_id', 'member_id']);

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
        Schema::table('read_group_messages', function (Blueprint $table) {
            $table->dropForeign(['group_message_id']);
        });

        Schema::dropIfExists('read_group_messages');
    }
}
