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
    private $member_id;

    public function __construct(GroupMessage $groupMessage, int $member_id)
    {
        $this->groupMessage = $groupMessage;
        $this->member_id = $member_id;
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
        return [
            'id' => $this->groupMessage->id
        ];
    }

}
