<?php

namespace app\controllers;

use app\core\controllerable;
use app\core\View;
use app\core\Route;
use app\core\Singleton;

abstract class AbstractController implements controllerable
{
    /**
     * Use trait with singleton pattern and set alias
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
     * Gets instance of this class from method of singleton trait, if property view not set - initialize it, ultimately returns instance of this class
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
     * Checks if user already logged or not depending on bool flag 
     * @param bool $isLogin boolean flag, true by default, using to compare with session param
     */
    protected function checkLogin(bool $isLogin = true) : void
    {
        session_start();
        if(isset($_SESSION[LOGIN_FLAG]) && $_SESSION[LOGIN_FLAG] == $isLogin){
            Route::redirect(Route::url());
            exit();
        }
    }
    /**
     * Checks request method, checking method depends on boolean flag, if method incorrect throws custom exception
     * @param bool $method boolean flag, if true - checking POST, if false - GET, true by default
     */
    protected function checkMethod(bool $method = true) : void  
    {
        $checkingMethod = $method ? 'POST' : 'GET';
        if($_SERVER['REQUEST_METHOD'] !== $checkingMethod){
            throw new \app\exceptions\NotAllowedException();
        }
    }
    /**
     * Validates value, if finds error - throws exception with description
     * @param string|int $value value to validate, can be text or numeric 
     * @param string $type name of value, need to exception message
     * @param int $min min value to check length, int_min by default
     * @param int $min max value to check length, int_max by default
     * @param bool $isNumber boolean flag for validation() method
     * @return string|int if exception not thrown - returns inputtedvalue after change by trim(), strtolower()
     */
    protected function validateInputedValue(string|int $value, string $type, bool $toLower = false, int $min = PHP_INT_MIN, int $max = PHP_INT_MAX, bool $isNumber = false) : string|int
    {
        $value = trim($value);
        if($toLower){
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
     * @param string|int $value value for validation, can be text or numeric
     * @param int $min min value for check length
     * @param int $max max value for check length
     * @param bool $isNumber boolean flag for special check for number
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
    /**
     * Validates file, after changes file name and move it to new direction, if finds error - throws exception with description else - return old and new file paths
     * @param callable $path certain method to get new direction path
     * @param string $key file key from array $_FILES
     * @return array returns two values: old and new file paths
     */
    protected function validateFile(callable $path, string $key) : array    // ToDo: change method after add JS
    {
        if(!isset($_FILES[$key])){
            throw new \InvalidArgumentException('No file for upload');
        }
        $file = $_FILES[$key];
        if($file['error'] !== UPLOAD_ERR_OK){
            throw new \InvalidArgumentException(FILE_UPLOAD_ERRORS[$file['error']]);
        }
        if(!is_uploaded_file($_FILES[$key]['tmp_name'])){
            throw new \InvalidArgumentException('File was uploaded using a GET method');
        }
        if (!str_starts_with($file['type'], AVAILABLE_TYPE)){
            throw new \InvalidArgumentException('Not available file type')      ;
        }
        if($file['size'] > PHOTO_MAX_FILE_SIZE){
            throw new \InvalidArgumentException('File too large.');
        }
        $fileName = $this->mkNwName($file['name']);
        $filePath = $path($fileName);
        if(!move_uploaded_file($file['tmp_name'], $filePath)){
            throw new \InvalidArgumentException('Some problems during moving uploaded file');
        }
        return ['oldFileName' => $file['name'], 'fileName' => $fileName];
    }
    /**
     * Returns path to file from images directory
     * @param string $fileName name of file
     * @return string path of file
     */
    protected function getImagesDir(string $fileName) : string
    {
        return dirname(__DIR__) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $fileName;
    }
    /**
     * Returns new file name
     * @param string $fileName file name that be changed
     * @return string new file name with same extension
     */
    private function mkNwName(string $fileName): string
    {
        $ext = strrchr($fileName, '.');
        return uniqid() . $ext;
    }
}