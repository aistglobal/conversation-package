<?php

namespace Aistglobal\Conversation\Events\Group;

use Aistglobal\Conversation\Models\GroupMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MemberGroupMessageCreatedEvent implements ShouldBroadcast
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
        return 'user_new_message';
    }

    public function broadcastAs()
    {
        return 'user_new_message_' . $this->checkAuthMember();
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->groupMessage->id
        ];
    }

    public function checkAuthMember(): ?int
    {
        $member_ids = $this->groupMessage->group->members->pluck('id')->toArray();

        if (in_array($this->auth_user_id, $member_ids)) {
            return $this->auth_user_id;
        }

        return null;
    }
}
