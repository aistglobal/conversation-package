<?php

use Aistglobal\Conversation\Http\Controllers\Message\MessageMarkAsReadController;
use Aistglobal\Conversation\Http\Controllers\Message\RetrieveMessageByIDController;
use Illuminate\Support\Facades\Route;
use Aistglobal\Conversation\Http\Controllers\Conversation\RetrieveUserConversationsController;
use Aistglobal\Conversation\Http\Controllers\Message\CreateMessageController;
use Aistglobal\Conversation\Http\Controllers\Message\RetrieveMessagesByConversationIDController;

Route::group(['prefix' => 'api', 'middleware' => 'auth:sanctum'], function () {

    Route::prefix('messages')->group(function () {
        Route::post('/', CreateMessageController::class);
        Route::get('/{message_id}', RetrieveMessageByIDController::class);
        Route::post('/{message_id}/mark_as_read', MessageMarkAsReadController::class);
    });

    Route::prefix('conversations')->group(function () {
        Route::get('/{conversation_id}/messages', RetrieveMessagesByConversationIDController::class);
    });

    Route::prefix('users')->group(function () {
        Route::get('/{user_id}/conversations', RetrieveUserConversationsController::class);
    });
});
