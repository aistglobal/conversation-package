<?php

namespace Aistglobal\Conversation\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupRequest extends FormRequest
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
