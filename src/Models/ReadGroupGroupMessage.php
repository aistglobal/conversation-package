<?php

namespace Aistglobal\Conversation\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ReadGroupGroupMessage extends Model
{
    protected $table = 'read_group_messages';

    protected $fillable = [
        'group_message_id',
        'member_id',
    ];

    protected $dates = [
        'created_at'
    ];

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d H:i:s');
    }

    public function group_message(): BelongsTo
    {
        return $this->belongsTo(GroupMessage::class);
    }

    public function scopeByGroupAndMember(Builder $builder, int $group_id, int $member_id): Builder
    {
        return $builder->where('member_id', $member_id)->whereHas('group_message', function ($query) use ($group_id){
            return $query->whereHas('group', function ($query) use ($group_id){
                return $query->where('groups.id', $group_id);
            });
        });
    }
}
