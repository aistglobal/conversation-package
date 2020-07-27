<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('conversation_id')->index();
            $table->unsignedBigInteger('author_id');
            $table->text('text')->nullable();
            $table->string('file_name')->nullable();
            $table->dateTime('read_at')->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->index(['conversation_id', 'read_at']);
            $table->index(['conversation_id', 'author_id']);
            $table->foreign('conversation_id')->references('id')
                ->on('conversations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
