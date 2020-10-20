<?php

namespace Aistglobal\Conversation\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupMessage extends Model
{
    protected $table = 'group_messages';

    protected $fillable = [
        'group_id',
        'author_id',
        'text',
        'file_name',
    ];

    protected $dates = [
        'created_at'
    ];

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d H:i:s');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(config('conversation.users'), 'author_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function scopeByGroup(Builder $builder, int $group_id, ?int $message_id): Builder
    {
        return $builder->when($message_id, function ($query) use ($message_id) {
            return $query->where('id', '<', $message_id);
        })
            ->orderByDesc('id')
            ->where('group_id', $group_id)
            ->take(50);
    }

    public function scopeLastMessage(Builder $builder, int $group_id): Builder
    {
        return $builder->where('group_id', $group_id)->orderBy('id', 'desc');
    }

    public function files(): HasMany
    {
        return $this->hasMany(GroupMessageFile::class, 'group_message_id');
    }

    public function read_messages(): HasMany
    {
        return $this->hasMany(ReadGroupGroupMessage::class);
    }
}
