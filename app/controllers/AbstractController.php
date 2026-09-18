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
    protected function checkMethod(bool $method = true) : void    //ToDo: change description
    {
        if($method){
            if($_SERVER['REQUEST_METHOD'] !== 'POST'){
                throw new \app\exceptions\NotAllowedException();
            }
        }else{
            if($_SERVER['REQUEST_METHOD'] !== 'GET'){
                throw new \app\exceptions\NotAllowedException();
            }
        }
        
    }
    /**
     * Validates value, if find error - throw exception with him
     * @param string|int $value value to validate, changed by trim(), strtolower(), 
     * @param string $type name of value, need to exception message
     * @param int $min min value to validation(), int_min by default
     * @param int $min max value to validation(), int_max by default
     * @param bool $isNumber boolean flag for validation()
     * @return string|int if exception not thrown - returns inputed value after change and validation
     */
    protected function validateInputedValue(string|int $value, string $type, int $min = PHP_INT_MIN, int $max = PHP_INT_MAX, bool $isNumber = false) : string|int
    {
        $value = trim($value);
        if($type !== 'Password'){
            $value = strtolower($value);
        }

        $error = $this->validation($value, $min, $max, $isNumber);
        if(!is_null($error)){
            throw new \InvalidArgumentException($type . $error);
        }
        return $value;
    }
    /**
     * Validates value, searches error and return it if find
     * @param string|int $value value for validation
     * @param int $min optional min value for check, min int by default
     * @param int $max optional max value for check, max int by default
     * @param bool $isNumber boolean flag for special check password
     * @return string error message or empty string
     */
    protected function validation(string|int &$value, int $min, int $max, bool $isNumber = false) : ?string
    {
        $error = null;
        if(!isset($value)){
            $error = ' is no value';
        }else if(is_null($value)){
            $error = ' is null';    
        }else if(empty($value)){ 
            $error = ' is empty or null';
        }else if($value == 0){
            $error = ' is zero value';
        }else if(!$isNumber && is_numeric($value)){
            $error = ' is not text';
        }else{
            $length = strlen($value);
            if($length < $min){
                $error =  " is to short, min length $min";
            }else if($length > $max){
                $error = " is to long, max length $max";
            }
        }
        return $error;
    }
}