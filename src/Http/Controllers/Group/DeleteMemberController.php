<?php

namespace Aistglobal\Conversation\Http\Controllers\Group;

use Aistglobal\Conversation\Events\Member\MemberDeletedEvent;
use Aistglobal\Conversation\Exceptions\API\UnauthorisedAPIException;
use Aistglobal\Conversation\Repositories\Group\GroupRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class DeleteMemberController extends Controller
{
    private $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function __invoke(Request $request, int $group_id): JsonResource
    {
        $member_ids = $request->member_ids;

        $group = $this->groupRepository->findOneByID($group_id);

        if ($group->creator_id !== $request->user()->id) {
            throw new UnauthorisedAPIException('Unauthorised');
        }

        $this->groupRepository->deleteMember($group->id, $member_ids);

        collect($member_ids)->each(function (int $member_id){
            event(new MemberDeletedEvent($member_id));
        });

        return JsonResource::make([
            'deleted' => true
        ]);
    }
}
