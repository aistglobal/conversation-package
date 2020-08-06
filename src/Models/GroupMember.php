<?php

namespace Aistglobal\Conversation\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    protected $table = 'group_member';

    protected $fillable = [
        'group_id',
        'member_id',
    ];

    public function scopeByMember(Builder $builder, int $member_id): Builder
    {
        return $builder->where('member_id', $member_id);
    }
}
