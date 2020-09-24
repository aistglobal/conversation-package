<?php


namespace Aistglobal\Conversation\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class GroupMessageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'group_id' => $this->group_id,
            'author_id' => $this->author_id,
            'text' => $this->text,
            'created_at' => $this->created_at,
            'file' => $this->file_name ? config('conversation.AWS_URL') . '/' . $this->file_name : null,
            'author' => $this->author,
        ];
    }


    public function recourseForGroup()
    {
        return GroupResource::make($this->group);
    }
}
