<?php

namespace Aistglobal\Conversation;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;
use Aistglobal\Conversation\Exceptions\Handler;
use Aistglobal\Conversation\Repositories\Conversation\ConversationRepository;
use Aistglobal\Conversation\Repositories\Conversation\EloquentConversationRepository;
use Aistglobal\Conversation\Repositories\Message\EloquentMessageRepository;
use Aistglobal\Conversation\Repositories\Message\MessageRepository;

class ConversationServiceProvider extends ServiceProvider
{

    public function boot()
    {
        \App::singleton(
            ExceptionHandler::class,
            Handler::class
        );

        $this->app->bind(ConversationRepository::class, EloquentConversationRepository::class);
        $this->app->bind(MessageRepository::class, EloquentMessageRepository::class);

        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->mergeConfigFrom(
            __DIR__.'/config/conversation.php',
            'conversation'
        );

        $this->publishes([
            __DIR__.'/config/conversation.php' => config_path('conversation.php'),
        ]);
    }

    public function register()
    {

    }
}
