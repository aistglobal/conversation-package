<?php

namespace Aistglobal\Conversation\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMessages extends Model
{
    protected $table = 'group_messages';

    protected $fillable = [
        'group_id',
        'author_id',
        'text',
        'file_name',
    ];
}
