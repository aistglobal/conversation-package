<?php

namespace Aistglobal\Conversation\Exceptions\API;

class NotFoundAPIException extends APIException
{
    protected function code()
    {
        return 404;
    }

    protected function httpStatusCode(): int
    {
        return 404;
    }
}
