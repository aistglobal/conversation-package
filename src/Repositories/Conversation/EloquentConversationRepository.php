<?php

namespace Aistglobal\Conversation\Repositories\Conversation;

use Aistglobal\Conversation\Exceptions\API\NotFoundAPIException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Aistglobal\Conversation\Models\Conversation;

class EloquentConversationRepository implements ConversationRepository
{
    public function create(array $data): Conversation
    {
        return Conversation::create($data);
    }

    public function findByOwnerAndPeer(int $owner_id, int $peer_id): Conversation
    {
        $conversation = Conversation::byOwnerAndPeer($owner_id, $peer_id)->first();

        throw_if(is_null($conversation), NotFoundAPIException::class);

        return $conversation;
    }

    public function findOneByID(int $conversation_id): Conversation
    {
        $conversation = Conversation::find($conversation_id);

        throw_if(is_null($conversation), NotFoundAPIException::class);

        return $conversation;
    }

    public function findByOwner(int $owner_id, int $page = 1, int $per_page = 15): LengthAwarePaginator
    {
        return Conversation::byOwner($owner_id)->paginate($per_page, ['*'], 'page', $page);
    }

    public function findByPeer(int $peer_id): Collection
    {
        return Conversation::byPeer($peer_id)->get();
    }
}
