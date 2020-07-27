<?php

namespace Aistglobal\Conversation\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Aistglobal\Conversation\Events\Message\MessageCreatedEvent;
use Aistglobal\Conversation\Http\Requests\Message\CreateMessageRequest;
use Aistglobal\Conversation\Http\Resources\MessageResource;
use Aistglobal\Conversation\Models\Conversation;
use Aistglobal\Conversation\Models\Message;
use Aistglobal\Conversation\Repositories\Conversation\ConversationRepository;
use Aistglobal\Conversation\Repositories\Message\MessageRepository;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class CreateMessageController extends Controller
{
    private $conversationRepository;

    private $messageRepository;

    public function __construct(
        ConversationRepository $conversationRepository,
        MessageRepository $messageRepository
    )
    {
        $this->conversationRepository = $conversationRepository;
        $this->messageRepository = $messageRepository;
    }

    public function __invoke(CreateMessageRequest $request): JsonResource
    {
        $data = $request->only([
            'text',
            'file_name',
            'peer_id',
            'author_id',
        ]);

        $message = $this->createMessageForAuthor($data);

        $this->createMessageForPeer($data);

        return MessageResource::make($message);
    }

    public function createMessageForAuthor(array $data): Message
    {
        $conversation = $this->retrieveConversation([
            'owner_id' => $data['author_id'],
            'peer_id' => $data['peer_id'],
        ]);

        $data['read_at'] = Carbon::now();

        $message = $this->createMessage($data + ['conversation_id' => $conversation->id]);

        return $message;
    }

    public function createMessageForPeer(array $data): void
    {
        $conversation = $this->retrieveConversation([
            'owner_id' => $data['peer_id'],
            'peer_id' => $data['author_id'],
        ]);

        $message = $this->createMessage($data + ['conversation_id' => $conversation->id]);
    }

    public function retrieveConversation(array $data): Conversation
    {
        try {
            $conversation = $this->conversationRepository
                ->findByOwnerAndPeer($data['owner_id'], $data['peer_id']);
        } catch (ResourceNotFoundException $exception) {
            $conversation = $this->conversationRepository->create($data);
        }

        return $conversation;
    }

    public function createMessage($data): Message
    {
        $message = $this->messageRepository->create($data);

        event(new MessageCreatedEvent($message));

        return $message;
    }
}
