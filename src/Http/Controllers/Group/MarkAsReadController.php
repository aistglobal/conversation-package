<?php

namespace Aistglobal\Conversation\Http\Controllers\Group;

use Aistglobal\Conversation\Exceptions\API\UnauthorisedAPIException;
use Aistglobal\Conversation\Repositories\Group\GroupRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class MarkAsReadController extends Controller
{
    private $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function __invoke(Request $request, int $group_id, int $message_id): JsonResource
    {
        $member_id = $request->user()->id;

        $group = $this->groupRepository->findOneByID($group_id);

        $this->checkIfGroupMember($group->id, $member_id);

        $this->markAsRead($group_id, $message_id, $member_id);

        return JsonResource::make([
            'mark_as_read' => true
        ]);
    }

    public function checkIfGroupMember(int $group_id, int $auth_user_id)
    {
        $members = $this->groupRepository->retrieveMembersByGroupID($group_id)->pluck('id')->toArray();

        if (!in_array($auth_user_id, $members)) {
            throw new UnauthorisedAPIException('Unauthorised');
        }
    }

    public function markAsRead(int $group_id, int $message_id, int $member_id): void
    {
        $read_message = $this->groupRepository->retrieveReadMessageByGroupAndMember($group_id, $member_id);

        if ($read_message) {
            $read_message->update([
                'group_message_id' => $message_id
            ]);
        } else {
            $this->groupRepository->markAsReadGroupMessage([
                'group_message_id' => $message_id,
                'member_id' => $member_id
            ]);
        }
    }

}
