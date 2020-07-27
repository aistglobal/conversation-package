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
        return 'message_created';
    }

    public function broadcastWith()
    {
        $conversationRepository = new EloquentConversationRepository();
        $conversation = $conversationRepository->findOneByID($this->message->conversation_id);

        return [
            'message_id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'conversation_owner_id' => $conversation->owner_id
        ];
    }
}
