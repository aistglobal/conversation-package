<?php

namespace Aistglobal\Conversation\Http\Controllers\Group;

use Aistglobal\Conversation\Exceptions\API\UnauthorisedAPIException;
use Aistglobal\Conversation\Http\Resources\GroupResource;
use Aistglobal\Conversation\Repositories\Group\GroupRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class RetrieveGroupByMemberIDController extends Controller
{
    private $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function __invoke(Request $request, int $member_id)
    {
        if ($member_id !== $request->user()->id) {
            throw new UnauthorisedAPIException('Unauthorised');
        }

        $page = 1;

        if ($request->has('page')) {
            $page = $request->page;
        }

        $per_page = 25;

        if ($request->has('per_page')) {
            $per_page = $request->per_page;
        }

        $groups = $this->groupRepository->retrieveGroupsBYMemberID($member_id, $page, $per_page);

        return GroupResource::collection($groups);

    }
}
