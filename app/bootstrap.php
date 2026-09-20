<?php

include_once '../app' . DIRECTORY_SEPARATOR . 'config.php';

spl_autoload_register(function($className){
    $classPath = '../' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
    if(file_exists($classPath)){
        include_once $classPath;
        return true;
    }
    return false;
});

try{
    \app\core\Route::init();
}catch (\app\exceptions\NotAllowedException $e){
    http_response_code($e->getCode());
    exit();
}catch (\app\exceptions\ConnectionException $e){
    http_response_code($e->getCode());
    exit();
}
