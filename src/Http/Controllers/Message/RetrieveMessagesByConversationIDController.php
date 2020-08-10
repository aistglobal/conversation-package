<?php

namespace Aistglobal\Conversation\Http\Controllers\Message;

use Aistglobal\Conversation\Exceptions\API\UnauthorisedAPIException;
use Aistglobal\Conversation\Repositories\Conversation\ConversationRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Aistglobal\Conversation\Http\Resources\MessageResource;
use Aistglobal\Conversation\Repositories\Message\MessageRepository;

class RetrieveMessagesByConversationIDController extends Controller
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

    public function __invoke(Request $request, int $conversation_id): JsonResource
    {
        $conversation = $this->conversationRepository->findOneByID($conversation_id);

        if($conversation->owner_id !== $request->user()->id){
            throw new UnauthorisedAPIException('Unauthorised');
        }

        $page = 1;

        if($request->has('page'))
        {
            $page = $request->page;
        }

        $messages = $this->messageRepository->findByConversationID($conversation_id, $page);

        return MessageResource::collection($messages);
    }
}
