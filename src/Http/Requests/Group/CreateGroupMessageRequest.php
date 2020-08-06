<?php

namespace Aistglobal\Conversation\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class CreateGroupMessageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'text' => [
                'required_without:file_name',
            ],
        ];
    }
}
