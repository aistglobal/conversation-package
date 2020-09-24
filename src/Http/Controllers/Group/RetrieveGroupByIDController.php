<?php

namespace Aistglobal\Conversation\Http\Controllers\Group;

use Aistglobal\Conversation\Exceptions\API\UnauthorisedAPIException;
use Aistglobal\Conversation\Http\Resources\GroupResource;
use Aistglobal\Conversation\Repositories\Group\GroupRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class RetrieveGroupByIDController extends Controller
{
    private $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }


    public function __invoke(Request $request, int $group_id): JsonResource
    {
        $group = $this->groupRepository->findOneByID($group_id);

        if($group->creator_id !== $request->user()->id){
            throw new UnauthorisedAPIException('Unauthorised');
        }

        return GroupResource::make($group);
    }
}
