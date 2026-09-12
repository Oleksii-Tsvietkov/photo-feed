<?php

namespace app\controllers;

use app\core\Route;
use app\models\User;

class Authorization extends AbstractController    // ToDo: create log out
{
    const LOGIN_ERROR = 'The login information you entered is incorrect.';
    /**
     * Name of login page
     */
    const LOGIN_PAGE = 'authorization';
    /**
     * Initializes property and sets to parrents construct name of layout page
     */
    public function __construct()
    {
        $this->model = new User();
        parent::__construct(self::LOGIN_PAGE);
    }
    /**
     * Calls render method that show authorization page
     */
    public function index(array $params = []) : void
    {
        $data = [
            'title' => 'Welcome',
            'templateName' => 'login-section',
        ];

        if($params !== []){
            $data += $params;    // ToDo: check
        }
        $this->view->render('login_index', $data);
    }
    /*public function login1() : void    
    {
        $identity = filter_input(INPUT_POST, 'identity');
        $pass = filter_input(INPUT_POST, 'pass');

        $errorMessage = $this->validation($identity);
        if($errorMessage !== ''){
            $errorMessage = 'Login' . $errorMessage;
        }else{
            $errorMessage = $this->validation($pass, 8, 20, true);
            if($errorMessage !== ''){
                $errorMessage = 'Password' . $errorMessage;
            }else{
                $result = $this->model->find($identity, $pass);
                if(!is_null($result)){
                    session_start();
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user'] = $result;    
                    Route::redirect(Route::url());
                    exit();
                }else{
                    $errorMessage = self::LOGIN_ERROR;
                }
            }
        } 
        $this->index([
            'errorMessage' => $errorMessage,
            'identity' => htmlspecialchars($identity, ENT_QUOTES, 'UTF-8'),
            'pass' => htmlspecialchars($pass, ENT_QUOTES, 'UTF-8'),
        ]);
        exit();
    }*/
    public function login() : void    // if authorized user come her - log out him?
    {
        $identity = filter_input(INPUT_POST, 'identity');
        $pass = filter_input(INPUT_POST, 'pass');
        try{
            $error = $this->validation($identity);
            if(!is_null($error)){
                throw new \InvalidArgumentException('Login' . $error);
            }

            $error = $this->validation($pass, 8, 20, true);
            if(!is_null($error)){
                throw new \InvalidArgumentException('Password' . $error);
            }

            $result = $this->model->find($identity, $pass);
            if(is_null($result)){
                throw new \InvalidArgumentException(self::LOGIN_ERROR);
            }

            session_start();
            $_SESSION['logged_in'] = true;
            $_SESSION['user'] = $result;    
            Route::redirect(Route::url());
        }catch (\InvalidArgumentException $e){
            $this->index([
                'errorMessage' => $e->getMessage(),
                'identity' => htmlspecialchars($identity, ENT_QUOTES, 'UTF-8'),
                'pass' => htmlspecialchars($pass, ENT_QUOTES, 'UTF-8'),
            ]);
        }finally{
            exit();
        }
    }
    public function registration() : void
    {
        //$this->checkMethod();    // fix that problem
        // ToDo: maybe check if login
        session_start();
        if(isset($_SESSION['logged_in'])){

        }
        $this->view->render('login_registration', [
            'title' => 'Registration',
        ]); 
    }
    public function store() : void
    {

    }
    private function checkMethod() // type?
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            throw new \app\exceptions\NotAllowedException();
        }
    }
    /**
     * Validates value, searches error and return it if find
     * @param $value value for validation
     * @param int $min optional min value for check, min int by default
     * @param int $max optional max value for check, max int by default
     * @param bool $isNumber boolean flag for special check password
     * @return string error message or empty string
     */
    private function validation(&$value, int $min = PHP_INT_MIN, int $max = PHP_INT_MAX, bool $isNumber = false) : ?string
    {
        trim($value);
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