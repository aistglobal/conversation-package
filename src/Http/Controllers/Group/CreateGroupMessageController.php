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
use Illuminate\Support\Facades\Storage;


class CreateGroupMessageController extends Controller
{
    private $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function __invoke(CreateGroupMessageRequest $request, int $group_id): JsonResource
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

        $group = $this->groupRepository->findOneByID($group_id);

        if ($request->has('files')) {
            collect($request->file('files'))->each(function ($file) use ($group, $message) {

                $saved_file = $this->saveFile($file, "groups/{$group->id}");

                $message->files()->create([
                    'file' => $saved_file['file_name'],
                    'file_path' => $saved_file['file_path'],
                ]);
            });
        }
        event(new GroupMessageCreatedEvent($message, $request->user()->id));

        $group->members->each(function ($member) use ($message) {
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

    public function saveFile($file, string $path): array
    {
        if ($file) {
            try {
                $fileName = $file->getClientOriginalName();

                $s3 = Storage::disk('public');
                $filePath = '/' . $path . '/' . $fileName;

                $contest = file_get_contents($file);
                $s3->put($filePath, $contest, 'public');

                return [
                    'file_name' => $fileName,
                    'file_path' => $filePath
                ];

            } catch (\Exception $e) {
                dd($e->getMessage());
            }

        }
    }


}
