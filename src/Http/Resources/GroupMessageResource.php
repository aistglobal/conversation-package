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
            'author' => $this->author,
            'files' => $this->recourseForFiles()
        ];
    }


    public function recourseForFiles()
    {
        return GroupMessageFileResource::collection($this->files);
    }
}
