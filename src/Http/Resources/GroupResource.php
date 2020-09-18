<?php


namespace Aistglobal\Conversation\Http\Resources;

use Aistglobal\Conversation\Repositories\Group\EloquentGroupRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    private $groupRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->groupRepository = new EloquentGroupRepository();
    }

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'creator_id' => $this->creator_id,
            'creator' => $this->creator,
            'last_message' => $this->lastMessage()
        ];
    }

    public function lastMessage(): ?array
    {
        if($this->last_message_id){
            return [
                'id' => $this->last_message_id,
                'text' => $this->last_message_text,
                'created_at' => $this->last_message_created_at,
            ];
        }

        return null;
    }
}



