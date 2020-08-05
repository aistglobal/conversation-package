<?php

use Aistglobal\Conversation\Http\Controllers\Group\CreateGroupController;
use Aistglobal\Conversation\Http\Controllers\Message\MessageMarkAsReadController;
use Aistglobal\Conversation\Http\Controllers\Message\RetrieveMessageByIDController;
use Aistglobal\Conversation\Http\Controllers\Message\RetrieveUnreadMessagesCountByPeerIDController;
use Illuminate\Support\Facades\Route;
use Aistglobal\Conversation\Http\Controllers\Conversation\RetrieveUserConversationsController;
use Aistglobal\Conversation\Http\Controllers\Message\CreateMessageController;
use Aistglobal\Conversation\Http\Controllers\Message\RetrieveMessagesByConversationIDController;


Route::get(config('conversation.conversation_url'), function () {
    return view('conversation::conversation');
});

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::group(['prefix' => 'api'], function () {

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
            Route::get('/{user_id}/unread_messages_count', RetrieveUnreadMessagesCountByPeerIDController::class);
        });

        Route::prefix('groups')->group(function () {
            Route::post('/', CreateGroupController::class);
        });

    });
});
