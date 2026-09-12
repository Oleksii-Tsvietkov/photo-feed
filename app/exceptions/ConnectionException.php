<?php

namespace app\exceptions;

class ConnectionException extends \Exception
{
    public function __construct()    //ToDo: to complete
    {
        $this->message = 'No database conection.';
        $this->code = 503;
    }
}