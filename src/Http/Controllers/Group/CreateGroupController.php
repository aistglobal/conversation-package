<?php

namespace Aistglobal\Conversation\Http\Controllers\Group;

use Aistglobal\Conversation\Exceptions\API\UnauthorisedAPIException;
use Aistglobal\Conversation\Http\Requests\Group\CreateGroupRequest;
use Aistglobal\Conversation\Repositories\Group\GroupRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;


class CreateGroupController extends Controller
{
    private $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function __invoke(CreateGroupRequest $request): JsonResource
    {
        $data = $request->only([
            'name'
        ]);

        $data['creator_id'] = $request->user()->id;

        $group = $this->groupRepository->create($data);

        return $group;
    }
}
