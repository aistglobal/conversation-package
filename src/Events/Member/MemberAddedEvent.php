<?php

namespace Aistglobal\Conversation\Events\Member;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MemberAddedEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $member_id;

    public function __construct(int $member_id)
    {
        $this->member_id = $member_id;
    }

    public function broadcastOn()
    {
        return 'member_added';
    }

    public function broadcastAs()
    {
        return 'member_added_' . $this->member_id;
    }

    public function broadcastWith()
    {
        return [
            'member_id' => $this->member_id,
        ];
    }

}
