<?php

namespace Aistglobal\Conversation\Http\Controllers\Group;

use Aistglobal\Conversation\Events\Group\GroupUpdatedEvent;
use Aistglobal\Conversation\Exceptions\API\UnauthorisedAPIException;
use Aistglobal\Conversation\Http\Requests\Group\UpdateGroupRequest;
use Aistglobal\Conversation\Http\Resources\GroupResource;
use Aistglobal\Conversation\Repositories\Group\GroupRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;


class UpdateGroupController extends Controller
{
    private $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function __invoke(UpdateGroupRequest $request, int $group_id): JsonResource
    {
        $data = $request->only([
            'name'
        ]);

        $group = $this->groupRepository->findOneByID($group_id);

        if ($group->creator_id !== $request->user()->id) {
            throw new UnauthorisedAPIException('Unauthorised');
        }

        $updated_group = $this->groupRepository->update($group->id, $data);

        event(new GroupUpdatedEvent($updated_group));

        return GroupResource::make($updated_group);
    }
}
