<?php

namespace app\core;

class Route
{
    /**
     * Name of default controller
     */
    const DEFAULT_CONTROLLER = 'index';
    /**
     * Name of default action
     */
    const DEFAULT_ACTION = 'index';
    /**
     * Calls certain method from certain class depending on get params if it exists, else - return error code,
     * if get params not set - use default values
     */
    public static function init()
    {
        $controllerName = self::DEFAULT_CONTROLLER;
        $actionName = self::DEFAULT_ACTION;
        if(isset($_GET['controller'])){
            $controllerName = strtolower($_GET['controller']);
        }
        if(isset($_GET['action'])){
            $actionName = strtolower($_GET['action']);
        }
        $controllerClass = 'app\controllers\\' . ucfirst($controllerName);
        if(!class_exists($controllerClass)){
            self::notFound();
        }
        $controller = new $controllerClass();
        if(!method_exists($controller, $actionName)){
            self::notFound();
        }
        $controller->$actionName();
        //self::caller($controller, $actionName);    // ToDo: delete or use
    }
    // private static function caller(\app\controllers\AbstractController $controller, $action){
    //     $controller->$action();
    // }

    /**
     * Returns url with get params controller, action and some optional variables
     * @var string $controller name of controller, have default value
     * @var string $action name of action, have default value
     * @var array $params optional assoc array with some variables, empty by default
     * @return string url with two get params and some optional variables
     */ 
    public static function url(string $controller = self::DEFAULT_CONTROLLER, string $action = self::DEFAULT_ACTION, array $params = []) : string
    {
        $getParams = '';
        foreach($params as $param => $value){
            $getParams .= $param . '=' . $value . '&';
        }
        return '/?controller=' . strtolower($controller) . '&action=' . strtolower($action) . '&' . $getParams;
    }
    /**
     * Calls header() with Location
     * @var string $url url to redirect, null by default
     */
    public static function redirect(string $url = null) : never
    {
        header('Location: ' . $url ?? '/');
    }
}