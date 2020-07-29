<?php

namespace Aistglobal\Conversation\Http\Controllers\Message;

use Aistglobal\Conversation\Exceptions\API\UnauthorisedAPIException;
use Aistglobal\Conversation\Http\Resources\MessageResource;
use Aistglobal\Conversation\Models\Message;
use Aistglobal\Conversation\Repositories\Conversation\ConversationRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Aistglobal\Conversation\Repositories\Message\MessageRepository;

class MessageMarkAsReadController extends Controller
{
    private $messageRepository;

    private $conversationRepository;

    public function __construct(
        MessageRepository $messageRepository,
        ConversationRepository $conversationRepository
    )
    {
        $this->messageRepository = $messageRepository;
        $this->conversationRepository = $conversationRepository;
    }

    public function __invoke(Request $request, int $message_id): JsonResource
    {
        $message = $this->messageRepository->markAsReadByMessageID($message_id);

        $this->messagePolicy($message, $request->user()->id);

        return MessageResource::make($message);
    }

    public function messagePolicy(Message $message, int $user_id)
    {
        $conversation = $this->conversationRepository->findOneByID($message->conversation_id);

        if($conversation->owner_id !== $user_id){
            throw new UnauthorisedAPIException('Unauthorised');
        }
    }
}
