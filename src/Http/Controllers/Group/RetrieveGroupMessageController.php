<?php

namespace Aistglobal\Conversation\Http\Controllers\Group;

use Aistglobal\Conversation\Exceptions\API\UnauthorisedAPIException;
use Aistglobal\Conversation\Http\Resources\GroupMessageResource;
use Aistglobal\Conversation\Repositories\Group\GroupRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class RetrieveGroupMessageController extends Controller
{
    private $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function __invoke(Request $request, int $group_id)
    {
        $group = $this->groupRepository->findOneByID($group_id);

        $this->checkIfGroupMember($group->id, $request->user()->id);

        $page = 1;

        if ($request->has('page')) {
            $page = $request->page;
        }

        $group_messages = $this->groupRepository->retrieveMessagesByGroupID($group_id, $page);

        return GroupMessageResource::collection($group_messages);
    }

    public function checkIfGroupMember(int $group_id, int $auth_user_id)
    {
        $members = $this->groupRepository->retrieveMembersByGroupID($group_id)->pluck('id')->toArray();

        if (!in_array($auth_user_id, $members)) {
            throw new UnauthorisedAPIException('Unauthorised');
        }
    }
}
