<?php


namespace Aistglobal\Conversation\Http\Resources;


use Aistglobal\Conversation\Repositories\Group\EloquentGroupRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupMessageResource extends JsonResource
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
            'group_id' => $this->group_id,
            'author_id' => $this->author_id,
            'text' => $this->text,
            'created_at' => $this->created_at,
            'author' => $this->author,
            'pinned' => $this->pinned,
            'files' => $this->recourseForFiles(),

        ];
    }


    public function recourseForFiles()
    {
        return GroupMessageFileResource::collection($this->files);
    }
}
