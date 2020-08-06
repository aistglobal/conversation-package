<?php

namespace Aistglobal\Conversation\Http\Controllers\Group;

use Aistglobal\Conversation\Exceptions\API\UnauthorisedAPIException;
use Aistglobal\Conversation\Repositories\Group\GroupRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class RetrieveMembersByGroupIDController extends Controller
{
    private $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function __invoke(Request $request, int $group_id)
    {
        $group = $this->groupRepository->findOneByID($group_id);

        if ($group->creator_id !== $request->user()->id) {
            throw new UnauthorisedAPIException('Unauthorised');
        }

        $members = $this->groupRepository->retrieveMembersByGroupID($group->id);

        return [
            'data' => $members
        ];
    }
}
