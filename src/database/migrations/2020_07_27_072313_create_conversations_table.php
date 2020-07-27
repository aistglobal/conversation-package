<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('owner_id');

            $table->unsignedBigInteger('peer_id');

            $table->timestamps();

            $table->softDeletes();

            $table->index(['owner_id', 'peer_id']);

            $table->unique(['owner_id', 'peer_id', 'deleted_at'], 'unique_conversation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversations');
    }
}
