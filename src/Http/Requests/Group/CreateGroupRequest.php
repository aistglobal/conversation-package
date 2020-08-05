<?php

namespace Aistglobal\Conversation\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class CreateGroupRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required',
            ]
        ];
    }
}
