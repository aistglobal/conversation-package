<?php

namespace Aistglobal\Conversation\Repositories\Group;

use Aistglobal\Conversation\Models\Group;
use Illuminate\Http\Resources\Json\JsonResource;

interface GroupRepository
{
    public function findOneByID(int $group_id): Group;

    public function create(array $data): Group;

    public function update(int $group_id, array $data): Group;

    public function delete(int $group_id);
}
