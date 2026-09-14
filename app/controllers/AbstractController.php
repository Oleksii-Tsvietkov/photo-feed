<?php

namespace app\controllers;

use app\core\controllerable;
use app\core\View;
use app\core\Route;

abstract class AbstractController implements controllerable    // ToDo: add singletone
{
    /**
     * Object of model class
     */
    protected $model;
    /**
     * Object of class View
     */
    protected View $view;
    /**
     * Initialize properties
     * @param string $layout name of layout page, null by default
     */
    public function __construct()
    {
        $this->view = new View();
    }
    /**
     * Checks if user already loggin, if so - redirect to default page
     * @param bool $login boolean flag, true by default, using to switch check
     */
    protected function checkLogin(bool $login = true) : void
    {
        session_start();
        if($_SESSION[LOGIN_FLAG] == $login){
            Route::redirect(Route::url());
            exit();
        }
    }
    /**
     * Checks if using post method, if not - throw custom exception
     */
    protected function checkMethod() : void
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            throw new \app\exceptions\NotAllowedException();
        }
    }
}