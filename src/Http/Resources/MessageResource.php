<?php


namespace Aistglobal\Conversation\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'conversation_id' => $this->conversation_id,
            'author_id' => $this->author_id,
            'text' => $this->text,
            'read_at' => $this->read_at,
            'created_at' => $this->created_at
        ];
    }
}
