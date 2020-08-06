<?php

use Aistglobal\Conversation\Http\Controllers\Group\AddMemberController;
use Aistglobal\Conversation\Http\Controllers\Group\CreateGroupController;
use Aistglobal\Conversation\Http\Controllers\Group\CreateGroupMessageController;
use Aistglobal\Conversation\Http\Controllers\Group\DeleteGroupController;
use Aistglobal\Conversation\Http\Controllers\Group\DeleteMemberController;
use Aistglobal\Conversation\Http\Controllers\Group\RetrieveGroupByIDController;
use Aistglobal\Conversation\Http\Controllers\Group\RetrieveGroupByMemberIDController;
use Aistglobal\Conversation\Http\Controllers\Group\RetrieveGroupMessageController;
use Aistglobal\Conversation\Http\Controllers\Group\RetrieveMembersByGroupIDController;
use Aistglobal\Conversation\Http\Controllers\Group\UpdateGroupController;
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

            Route::get('/{user_id}/groups', RetrieveGroupByMemberIDController::class);
        });

        Route::prefix('groups')->group(function () {
            Route::post('/', CreateGroupController::class);
            Route::get('/{group_id}', RetrieveGroupByIDController::class);
            Route::put('/{group_id}', UpdateGroupController::class);
            Route::delete('/{group_id}', DeleteGroupController::class);

            //  MEMBERS
            Route::post('/{group_id}', AddMemberController::class);
            Route::delete('/{group_id}', DeleteMemberController::class);
            Route::get('/{group_id}/members', RetrieveMembersByGroupIDController::class);

            // Messages
            Route::post('/{group_id}/messages', CreateGroupMessageController::class);
            Route::get('/{group_id}/messages', RetrieveGroupMessageController::class);
        });

    });
});
