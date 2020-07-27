<?php

namespace Aistglobal\Conversation\Exceptions\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

abstract class APIException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    protected abstract function code();

    protected function additionalFields()
    {
        return [];
    }

    protected function httpStatusCode(): int
    {
        return 400;
    }

    public function render(Request $request): Response
    {
        $data = [
            'code' => $this->code(),
            'message' => trans("errors.{$this->code()}"),
        ];

        return response([
            'error' => array_merge($data, $this->additionalFields())
        ], $this->httpStatusCode());
    }
}
