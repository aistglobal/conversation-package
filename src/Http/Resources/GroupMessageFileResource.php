<?php


namespace Aistglobal\Conversation\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class GroupMessageFileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'file_name' => $this->file,
            'file' => $this->file_path ? config('conversation.AWS_URL') . '/' . $this->file_path : null,
        ];
    }

}
