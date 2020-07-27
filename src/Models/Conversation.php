<?php

namespace Aistglobal\Conversation\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
        return $builder->where('owner_id', $owner_id)->orderBy('id', config('conversation.conversations_order'));
    }

    public function user()
    {
        return $this->belongsTo(config('conversation.users'), 'peer_id');
    }
}
