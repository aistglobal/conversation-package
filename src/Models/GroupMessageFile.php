<?php

namespace Aistglobal\Conversation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupMessageFile extends Model
{
    protected $table = 'group_message_files';

    protected $fillable = [
        'group_message_id',
        'file',
        'file_path'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(GroupMessage::class, 'group_message_id');
    }
}
