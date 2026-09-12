<?php

namespace app\controllers;

class Index extends AbstractController
{
    public function index() : void
    {
        echo 'Hello';
        session_start();
        var_dump($_SESSION['user']);;
        unset($_SESSION['logged_in']);
        echo 'Goodbye';
        exit();        
    }
}