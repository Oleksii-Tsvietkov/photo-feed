<?php

namespace app\exceptions;

class NotAllowedException extends \ErrorException
{
    public function __construct()    //ToDo: to complete
    {
        $this->message = 'The method specified by the client cannot be applied to the current resource.';
        $this->code = 405;
    }
}