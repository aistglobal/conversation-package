<?php


namespace Aistglobal\Conversation\Http\Resources;


use Aistglobal\Conversation\Models\Message;
use Aistglobal\Conversation\Repositories\Message\EloquentMessageRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{

    public $messageRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->messageRepository = new EloquentMessageRepository();
    }

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'peer_id' => $this->peer_id,
            'last_message' => $this->lastMessage(),
            'peer' => $this->user
        ];
    }

    public function lastMessage(): JsonResource
    {
        $last_message = $this->messageRepository->findLastByConversationID($this->id);

        return MessageResource::make($last_message);
    }


}
