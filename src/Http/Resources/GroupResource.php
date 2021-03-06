<?php


namespace Aistglobal\Conversation\Http\Resources;

use Aistglobal\Conversation\Models\GroupMessage;
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
            'last_message' => $this->lastMessage(),
            'unread_message_count' => $this->unreadMessageCount()
        ];
    }


    public function lastMessage(): ?array
    {
        if ($this->last_message_id) {
            $message = $this->groupRepository->retrieveGroupMessageByID($this->last_message_id);

            return [
                'id' => $message->id,
                'text' => $message->text,
                'files' => $this->getFiles($message),
                'created_at' => $message->created_at,
            ];
        }

        return null;
    }

    public function unreadMessageCount(): int
    {
        $last_read_message = $this->groupRepository
            ->retrieveReadMessageByGroupAndMember($this->id, $this->auth_user_id ?? auth()->id());


        if ($last_read_message) {
            return $this->messages->filter(function ($message) use ($last_read_message){
                return $message->id > $last_read_message->group_message_id;
            })->count();

        } else {
            return $this->message_count;
        }
    }

    public function getFiles(GroupMessage $message): JsonResource
    {
        return GroupMessageFileResource::collection($message->files);
    }
}



