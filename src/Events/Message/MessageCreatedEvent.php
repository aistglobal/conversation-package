<?php

namespace Aistglobal\Conversation\Events\Message;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Aistglobal\Conversation\Models\Message;
use Aistglobal\Conversation\Repositories\Conversation\EloquentConversationRepository;

class MessageCreatedEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return 'message_created';
    }

    public function broadcastAs()
    {
        $owner_id = $this->retrieveConversationByID($this->message->conversation_id);

        return 'message_created_' . $owner_id;
    }

    public function broadcastWith()
    {
        return [
            'message_id' => $this->message->id,
            'author_id' => $this->message->author_id,
            'text' => $this->message->text,
            'conversation_id' => $this->message->conversation_id,
            'conversation_owner_id' => $this->retrieveConversationByID($this->message->conversation_id)
        ];
    }


    public function retrieveConversationByID(int $conversation_id): int
    {
        $conversationRepository = new EloquentConversationRepository();
        $conversation = $conversationRepository->findOneByID($this->message->conversation_id);

        return $conversation->owner_id;
    }
}
