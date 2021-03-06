<?php

namespace Aistglobal\Conversation\Repositories\Message;

use Illuminate\Pagination\LengthAwarePaginator;
use Aistglobal\Conversation\Models\Message;

interface MessageRepository
{
    public function findOneByID(int $message_id): Message;

    public function markAsReadByMessageID(int $message_id): Message;

    public function create(array $data): Message;

    public function findByConversationID(int $conversation_id, int $page = 1, int $per_page = 50): LengthAwarePaginator;

    public function findLastByConversationID(int $conversation_id): ?Message;
}
