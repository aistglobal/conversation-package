<?php

namespace Aistglobal\Conversation\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';

    protected $fillable = [
        'creator_id',
        'name',
    ];
}
