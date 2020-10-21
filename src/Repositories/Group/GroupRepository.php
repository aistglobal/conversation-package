<?php

namespace Aistglobal\Conversation\Repositories\Group;

use Aistglobal\Conversation\Models\Group;
use Aistglobal\Conversation\Models\GroupMessage;
use Aistglobal\Conversation\Models\ReadGroupGroupMessage;
use Illuminate\Support\Collection;

interface GroupRepository
{
    public function findOneByID(int $group_id): Group;

    public function create(array $data): Group;

    public function update(int $group_id, array $data): Group;

    public function delete(int $group_id);

    public function addMember(int $group_id, array $member_ids);

    public function deleteMember(int $group_id, array $member_ids);

    public function createGroupMessage(array $data): GroupMessage;

    public function retrieveGroupMessageByID(int $group_message_id): GroupMessage;

    public function retrieveMembersByGroupID(int $group_id): Collection;

    public function retrieveMessagesByGroupID(int $group_id, ?int $message_id): Collection;

    public function retrieveGroupsBYMemberID(int $member_id): Collection;

    public function markAsReadGroupMessage(array $data): ReadGroupGroupMessage;

    public function retrieveReadMessageByGroupAndMember(int $group_id, int $member_id): ?ReadGroupGroupMessage;
}
