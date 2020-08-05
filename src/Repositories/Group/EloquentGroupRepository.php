<?php


namespace aistglobal\Conversation\Repositories\Group;

use Aistglobal\Conversation\Models\Group;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class EloquentGroupRepository implements GroupRepository
{

    public function findOneByID(int $group_id): Group
    {
        $group = Group::find($group_id);

        throw_if(is_null($group), ResourceNotFoundException::class);

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
}
