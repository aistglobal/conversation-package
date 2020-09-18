<?php


namespace Aistglobal\Conversation\Repositories\Group;

use Aistglobal\Conversation\Exceptions\API\NotFoundAPIException;
use Aistglobal\Conversation\Models\Group;
use Aistglobal\Conversation\Models\GroupMember;
use Aistglobal\Conversation\Models\GroupMessage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentGroupRepository implements GroupRepository
{

    public function findOneByID(int $group_id): Group
    {
        $group = Group::find($group_id);

        throw_if(is_null($group), NotFoundAPIException::class);

        return $group;
    }

    public function create(array $data): Group
    {
        return Group::create($data);
    }

    public function update(int $group_id, array $data): Group
    {
        $group = $this->findOneByID($group_id);

        $group->update($data);

        return $group->refresh();
    }

    public function delete(int $group_id)
    {
        $this->findOneByID($group_id)->delete();
    }

    public function addMember(int $group_id, array $member_ids)
    {
        $group = $this->findOneByID($group_id);

        $group->members()->sync($member_ids, false);
    }

    public function deleteMember(int $group_id, array $member_ids)
    {
        $group = $this->findOneByID($group_id);

        $group->members()->detach($member_ids);
    }

    public function createGroupMessage(array $data): GroupMessage
    {
        return GroupMessage::create($data);
    }

    public function retrieveMembersByGroupID(int $group_id): Collection
    {
        $group = $this->findOneByID($group_id);

        return $group->members;
    }

    public function retrieveGroupsBYMemberID(int $member_id): Collection
    {
        $group_ids = GroupMember::byMember($member_id)->pluck('group_id')->toArray();

        return Group::findByIds($group_ids)->get();
    }

    public function retrieveMessagesByGroupID(int $group_id, int $page = 1, int $per_page = 50): LengthAwarePaginator
    {
        return GroupMessage::byGroup($group_id)->paginate($per_page, ['*'], 'page', $page);
    }
}