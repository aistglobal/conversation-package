<?php

namespace Aistglobal\Conversation\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class CreateMessageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'peer_id' => [
                'required',
                'integer',
            ],

            'text' => [
                'required_without:file_name',
            ],
        ];
    }
}
