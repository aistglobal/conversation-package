<?php

namespace Aistglobal\Conversation\Http\Controllers\Conversation;

use Aistglobal\Conversation\Exceptions\API\UnauthorisedAPIException;
use Aistglobal\Conversation\Models\Conversation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Aistglobal\Conversation\Http\Resources\ConversationResource;
use Aistglobal\Conversation\Repositories\Conversation\ConversationRepository;


class RetrieveUserConversationsController extends Controller
{
    private $conversationRepository;

    public function __construct(ConversationRepository $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }

    public function __invoke(Request $request, int $user_id): JsonResource
    {
        if($user_id !== $request->user()->id){
            throw new UnauthorisedAPIException('Unauthorised');
        }

        $conversations = $this->conversationRepository->findByOwner($user_id);

        return ConversationResource::collection($conversations);
    }
}
