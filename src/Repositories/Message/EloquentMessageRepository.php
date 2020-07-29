<?php


namespace Aistglobal\Conversation\Repositories\Message;


use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Aistglobal\Conversation\Models\Message;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

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

    public function findOneByID(int $message_id): Message
    {
        $message = Message::find($message_id);

        throw_if(is_null($message), ResourceNotFoundException::class);

        return $message;
    }

    public function markAsReadByMessageID(int $message_id): Message
    {
        $message = Message::find($message_id);

        $message->update([
            'read_at' => Carbon::now()
        ]);

        return $message->refresh();
    }
}
