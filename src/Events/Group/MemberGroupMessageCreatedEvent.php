<?php

namespace Aistglobal\Conversation\Events\Group;

use Aistglobal\Conversation\Http\Resources\GroupResource;
use Aistglobal\Conversation\Models\GroupMessage;
use Aistglobal\Conversation\Repositories\Group\EloquentGroupRepository;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Queue\SerializesModels;

class MemberGroupMessageCreatedEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $groupMessage;

    private $member_id;

    private $groupRepository;

    public function __construct(GroupMessage $groupMessage, int $member_id)
    {
        $this->groupMessage = $groupMessage;
        $this->member_id = $member_id;

        $this->groupRepository = new EloquentGroupRepository();
    }

    public function broadcastOn()
    {
        return 'user_new_message';
    }

    public function broadcastAs()
    {
        return 'user_new_message_' . $this->member_id;
    }

    public function broadcastWith()
    {
        $group = $this->groupRepository->findOneByID($this->groupMessage->group_id);

        $group->auth_user_id = $this->member_id;

        return $this->retrieveData($group)->toArray($group);
    }

    protected function retrieveData($group): JsonResource
    {
        return GroupResource::make($group);
    }

}
