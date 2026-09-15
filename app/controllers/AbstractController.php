<?php

namespace app\controllers;

use app\core\controllerable;
use app\core\View;
use app\core\Route;
use app\core\Singleton;

abstract class AbstractController implements controllerable
{
    /**
     * Use trait with singletone pattern and set alias
     */
    use Singleton {
        Singleton::getInstance as getInstanceSingleton;
    }
    /**
     * Object of model class
     */
    protected $model;
    /**
     * Object of class View
     */
    protected View $view;
    /**
     * Gets instance of this class from getInstance(), if property view not set - initialize it, ultimately returns instance of this class
     * @return static instance of this class
     */
    public static function getInstance() : static
    {
        $instance = self::getInstanceSingleton();
        if(!isset($instance->view)){
            $instance->view = new View();
        }
        return $instance;
    }
    /**
     * Checks if user already loggin, if so - redirect to default page
     * @param bool $login boolean flag, true by default, using to switch check
     */
    protected function checkLogin(bool $login = true) : void
    {
        session_start();
        if(isset($_SESSION[LOGIN_FLAG]) && $_SESSION[LOGIN_FLAG] == $login){    // ToDo: check
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