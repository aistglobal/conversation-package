<?php

namespace Aistglobal\Conversation\Exceptions\API;

use Illuminate\Validation\ValidationException;

class ValidationAPIException extends APIException
{
    /**
     * @var ValidationException
     */
    private $original_exception;

    public function __construct(ValidationException $exception)
    {
        $this->original_exception = $exception;
    }

    protected function additionalFields()
    {
        $errors = collect($this->original_exception->errors())
            ->map(function ($messages, $key) {
                return [
                    'key' => $key,
                    'messages' => $messages,
                ];
            })
            ->values()
            ->all();

        return [
            'fields' => $errors,
        ];
    }

    protected function code()
    {
        return 422;
    }

    protected function httpStatusCode(): int
    {
        return 422;
    }
}
