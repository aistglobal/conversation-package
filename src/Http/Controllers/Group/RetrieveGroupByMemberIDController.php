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
        if($member_id !== $request->user()->id){
            throw new UnauthorisedAPIException('Unauthorised');
        }

        $groups = $this->groupRepository->retrieveGroupsBYMemberID($member_id);

        return GroupResource::collection($groups);
    }
}
