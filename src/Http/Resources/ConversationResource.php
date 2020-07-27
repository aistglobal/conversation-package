<?php


namespace Aistglobal\Conversation\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'peer_id' => $this->peer_id,
            'peer' => $this->user
        ];
    }
}
