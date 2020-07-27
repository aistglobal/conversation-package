<?php

namespace Aistglobal\Conversation\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Message extends Model
{
    use SoftDeletes;

    protected $table = 'messages';

    protected $fillable = [
        'conversation_id',
        'author_id',
        'text',
        'file_name',
        'read_at',
    ];

    public function scopeByConversationID(Builder $builder, int $conversation_id)
    {
        return $builder->where('conversation_id', $conversation_id)->orderBy('id', config('conversation.messages_order'));
    }
}
