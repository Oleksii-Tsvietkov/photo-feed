<?php

namespace app\exceptions;

class ConnectionException extends \Exception
{
    public function __construct(string $message)    //ToDo: to complete
    {
        $this->message = 'No database conection.' . $message;
        $this->code = 503;
    }
}