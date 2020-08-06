<?php

namespace Aistglobal\Conversation\Events\Group;

use Aistglobal\Conversation\Models\Group;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupDeletedEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $group;

    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    public function broadcastOn()
    {
        return 'group_deleted';
    }

    public function broadcastAs()
    {
        return 'group_deleted';
    }

    public function broadcastWith()
    {
        return [
            'group_id' => $this->group->id,
        ];
    }

}
