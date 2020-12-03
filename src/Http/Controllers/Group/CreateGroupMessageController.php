<?php

namespace Aistglobal\Conversation\Http\Controllers\Group;

use Aistglobal\Conversation\Events\Group\GroupMessageCreatedEvent;
use Aistglobal\Conversation\Events\Group\MemberGroupMessageCreatedEvent;
use Aistglobal\Conversation\Exceptions\API\UnauthorisedAPIException;
use Aistglobal\Conversation\Http\Requests\Group\CreateGroupMessageRequest;
use Aistglobal\Conversation\Http\Resources\GroupMessageResource;
use Aistglobal\Conversation\Models\GroupMessage;
use Aistglobal\Conversation\Repositories\Group\GroupRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;


class CreateGroupMessageController extends Controller
{
    protected $groupRepository;

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
                'pinned',
            ]) + [
                'group_id' => $group_id,
                'author_id' => $member_id,
            ];

        $this->checkIfGroupMember($group_id, $request->user()->id);

        $message = $this->groupRepository->createGroupMessage($data);

        $this->markAsRead($group_id, $message->id, $member_id);

        $group = $this->groupRepository->findOneByID($group_id);

        $group->update([
            'message_count' => $group->message_count + 1
        ]);

        if ($request->has('files')) {
            collect($request->file('files'))->each(function ($file) use ($group, $message) {

                $file_name = $file->getClientOriginalName();
                $file_path = $file->store("groups/{$group->id}");

                $message->files()->create([
                    'file' => $file_name,
                    'file_path' => $file_path,
                ]);
            });
        }

        event(new GroupMessageCreatedEvent($message, $request->user()->id));

        $group->members->each(function ($member) use ($message) {
            $this->memberGroupMessageCreatedEvent($message,$member->id);
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


    protected function memberGroupMessageCreatedEvent(GroupMessage $message, int $member_id): void
    {
        event(new MemberGroupMessageCreatedEvent($message, $member_id));
    }


}
