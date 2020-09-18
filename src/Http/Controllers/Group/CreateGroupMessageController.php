<?php

namespace Aistglobal\Conversation\Http\Controllers\Group;

use Aistglobal\Conversation\Events\Group\GroupMessageCreatedEvent;
use Aistglobal\Conversation\Exceptions\API\UnauthorisedAPIException;
use Aistglobal\Conversation\Http\Requests\Group\CreateGroupMessageRequest;
use Aistglobal\Conversation\Http\Resources\GroupMessageResource;
use Aistglobal\Conversation\Repositories\Group\GroupRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;


class CreateGroupMessageController extends Controller
{
    private $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function __invoke(CreateGroupMessageRequest $request, int $group_id)
    {
        $data = $request->only([
                'text',
                'file_name',
                'peer_id',
            ]) + [
                'group_id' => $group_id,
                'author_id' => $request->user()->id,
            ];

        $this->checkIfGroupMember($group_id, $request->user()->id);

        $message = $this->groupRepository->createGroupMessage($data);

        event(new GroupMessageCreatedEvent($message, $request->user()->id));

        return GroupMessageResource::make($message);
    }

    public function checkIfGroupMember(int $group_id, int $auth_user_id)
    {
        $members = $this->groupRepository->retrieveMembersByGroupID($group_id)->pluck('id')->toArray();

        if (!in_array($auth_user_id, $members)) {
            throw new UnauthorisedAPIException('Unauthorised');
        }
    }
}
