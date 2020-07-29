<?php

namespace Aistglobal\Conversation\Exceptions\API;

class UnauthorisedAPIException extends APIException
{
    protected function code()
    {
        return 403;
    }

    protected function httpStatusCode(): int
    {
        return 403;
    }
}
