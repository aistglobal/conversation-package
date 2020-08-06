<?php

namespace Aistglobal\Conversation\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Group extends Model
{
    protected $table = 'groups';

    protected $fillable = [
        'creator_id',
        'name',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(config('conversation.users'), 'creator_id');
    }

    public function members()
    {
        return $this->belongsToMany(config('conversation.users'), 'group_member', 'group_id', 'member_id');
    }

    public function scopeFindByIds(Builder $builder, array $ids): Builder
    {
        return $builder->whereIn('id', $ids);
    }
}
