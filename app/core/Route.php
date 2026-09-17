<?php

namespace app\core;

class Route
{
    /**
     * Name of default controller
     */
    const DEFAULT_CONTROLLER = 'index';
    /**
     * Name of authorization controller
     */
    const AUTHORIZATION_CONTROLLER = 'authorization';
    /**
     * Name of default action
     */
    const DEFAULT_ACTION = 'index';
    /**
     * Calls certain method from certain class depending on get params if it exists, else - return error code,
     * if get params not set - use default values
     */
    public static function init() : void
    {
        $controllerName = self::DEFAULT_CONTROLLER;
        $actionName = self::DEFAULT_ACTION;
        if(isset($_GET['controller'])){
            $controllerName = strtolower($_GET['controller']);
        }
        $controllerName = self::checkLogin($controllerName);
        if(isset($_GET['action'])){
            $actionName = strtolower($_GET['action']);
        }
        $controllerClass = 'app\controllers\\' . ucfirst($controllerName);
        if(!class_exists($controllerClass)){
            self::notFound();
        }
        $controller = $controllerClass::getInstance();
        
        if(!method_exists($controller, $actionName)){
            self::notFound();
        }
        self::caller($controller, $actionName); 
    }
    /**
     * Receives controller of certain class and action name, calls this action from this controller
     * @param AbstractController $controller object of AbstractController or heritor class
     * @param string $action name of method
     */
    private static function caller(\app\controllers\AbstractController $controller, string $action) : void
    {
        $controller->$action();
    }
    /**
     * Checks if user logged in
     * @v
     */
    private static function checkLogin(string $controller) : string
    {
        session_start();
        if(!isset($_SESSION[LOGIN_FLAG]) || !$_SESSION[LOGIN_FLAG]){    // ToDo: check
            $controller = self::AUTHORIZATION_CONTROLLER;
        }
        return $controller;
    }
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
            $getParams .= '&' . $param . '=' . $value;    // ToDo: & - was changed, check
        }
        return '/?controller=' . strtolower($controller) . '&action=' . strtolower($action) . '&' . $getParams;
    }
    /**
     * Calls header() with Location
     * @var string $url url to redirect, null by default
     */
    public static function redirect(string $url = null)// : never   // must not have return type
    {
        header('Location: ' . $url ?? '/');
    }
    /**
     * Returns error code 404 
     */
    public static function notFound() : never
    {
        http_response_code(404);
        exit();
    }
    /**
     * Returns error code 402 
     */
    public static function unprocessableEntity() : never
    {
        http_response_code(422);
        exit();
    }
}