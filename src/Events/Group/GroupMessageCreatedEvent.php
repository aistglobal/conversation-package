<?php

namespace Aistglobal\Conversation\Events\Group;

use Aistglobal\Conversation\Models\GroupMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupMessageCreatedEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $groupMessage;
    /**
     * @var int
     */
    private $auth_user_id;

    public function __construct(GroupMessage $groupMessage, int $auth_user_id)
    {
        $this->groupMessage = $groupMessage;
        $this->auth_user_id = $auth_user_id;
    }

    public function broadcastOn()
    {
        return 'group_message_created';
    }

    public function broadcastAs()
    {
        return 'group_message_created_' . $this->checkAuthMember();
    }

    public function broadcastWith()
    {
        return [
            'group_message_id' => $this->groupMessage->id,
            'group_id' => $this->groupMessage->group_id,
            'aa' => $this->checkAuthMember()
        ];
    }

    public function checkAuthMember(): ?int
    {
        $member_ids = $members = $this->groupMessage->group->members->pluck('id')->toArray();

        if (in_array($this->auth_user_id, $member_ids)) {
            return $this->auth_user_id;
        }

        return null;
    }


}
