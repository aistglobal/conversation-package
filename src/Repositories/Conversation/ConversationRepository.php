<?php

namespace Aistglobal\Conversation\Repositories\Conversation;

use Illuminate\Pagination\LengthAwarePaginator;
use Aistglobal\Conversation\Models\Conversation;

interface ConversationRepository
{
    public function findOneByID(int $conversation_id): Conversation;

    public function create(array $data): Conversation;

    public function findByOwnerAndPeer(int $owner_id, int $peer_id): Conversation;

    public function findByOwner(int $owner_id): LengthAwarePaginator;
}
