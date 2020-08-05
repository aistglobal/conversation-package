<?php

namespace Aistglobal\Conversation\Http\Controllers\Message;

use Aistglobal\Conversation\Exceptions\API\UnauthorisedAPIException;
use Aistglobal\Conversation\Repositories\Conversation\ConversationRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RetrieveUnreadMessagesCountByPeerIDController extends Controller
{
    private $conversationRepository;

    public function __construct(ConversationRepository $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }

    public function __invoke(Request $request, int $user_id): JsonResource
    {

        dd(12);

        if ($user_id !== $request->user()->id) {
            throw new UnauthorisedAPIException('Unauthorised');
        }

        $unread_messages_count = 0;

        $conversations = $this->conversationRepository->findByPeer($user_id);

        $conversations->each(function ($conversation) use (&$unread_messages_count) {
            $unread_messages_count += $conversation->messages()->whereNull('read_at')->count();
        });

        return JsonResource::make([
            'unread_messages_count' => $unread_messages_count
        ]);
    }
}
