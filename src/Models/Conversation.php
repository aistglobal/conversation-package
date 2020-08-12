<?php

namespace Aistglobal\Conversation\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use SoftDeletes;

    protected $table = 'conversations';

    protected $fillable = [
        'owner_id',
        'peer_id'
    ];

    public function scopeByOwnerAndPeer(Builder $builder, int $owner_id, int $peer_id): Builder
    {
        return $builder->where('owner_id', $owner_id)->where('peer_id', $peer_id);
    }

    public function scopeByOwner(Builder $builder, int $owner_id)
    {
        return $builder->where('owner_id', $owner_id)->orderByDesc(Message::select('created_at')
            ->whereColumn('messages.conversation_id', 'conversations.id')->latest()->take(1));
    }

    public function scopeByPeer(Builder $builder, int $peer_id): Builder
    {
        return $builder->where('peer_id', $peer_id);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('conversation.users'), 'peer_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
