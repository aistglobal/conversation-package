<?php

namespace Aistglobal\Conversation\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $table = 'groups';

    protected $fillable = [
        'creator_id',
        'name',
        'message_count',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(config('conversation.users'), 'creator_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(config('conversation.users'), 'group_member', 'group_id', 'member_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(GroupMessage::class);
    }

    public function scopeFindByIds(Builder $builder, array $ids): Builder
    {
        return $builder->whereIn('id', $ids)
            ->addSelect(['last_message_id' => GroupMessage::select(['id'])
                ->whereColumn('group_id', 'groups.id')
                ->latest()
                ->take(1)]);
    }

}
