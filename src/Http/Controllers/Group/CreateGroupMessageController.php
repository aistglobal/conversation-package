<?php

namespace Aistglobal\Conversation\Http\Controllers\Group;

use Aistglobal\Conversation\Events\Group\GroupMessageCreatedEvent;
use Aistglobal\Conversation\Events\Group\MemberGroupMessageCreatedEvent;
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

    public function __invoke(CreateGroupMessageRequest $request, int $group_id): JsonResource
    {
        $member_id = $request->user()->id;

        $data = $request->only([
                'text',
                'file_name',
                'peer_id',
            ]) + [
                'group_id' => $group_id,
                'author_id' => $member_id,
            ];

        $this->checkIfGroupMember($group_id, $request->user()->id);

        $message = $this->groupRepository->createGroupMessage($data);

        $group = $this->groupRepository->findOneByID($group_id);

        $group->update([
            'message_count' => $group->message_count + 1
        ]);

        if ($request->has('files')) {
            collect($request->file('files'))->each(function ($file) use ($group, $message) {

                $file_name = $file->getClientOriginalName();
                $file_path = $file->store("public/groups/{$group->id}");

                $message->files()->create([
                    'file' => $file_name,
                    'file_path' => str_replace('public/', '', $file_path),
                ]);
            });
        }

        event(new GroupMessageCreatedEvent($message, $request->user()->id));

        $this->groupRepository->markAsReadGroupMessage([
            'group_message_id' => $message->id,
            'member_id' => $member_id
        ]);

        $group->members->each(function ($member) use ($message, $member_id) {

            event(new MemberGroupMessageCreatedEvent($message, $member->id));
        });

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
