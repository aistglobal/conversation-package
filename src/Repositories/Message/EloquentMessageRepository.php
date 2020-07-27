<?php


namespace Aistglobal\Conversation\Repositories\Message;


use Illuminate\Pagination\LengthAwarePaginator;
use Aistglobal\Conversation\Models\Message;
use Aistglobal\Conversation\Repositories\Message\MessageRepository;

class EloquentMessageRepository implements MessageRepository
{
    public function create(array $data): Message
    {
        return Message::create($data);
    }

    public function findByConversationID(int $conversation_id, int $page = 1, int $per_page = 15): LengthAwarePaginator
    {
        return Message::byConversationID($conversation_id)->paginate($per_page, ['*'], 'page', $page);
    }
}
