<?php

namespace app\controllers;

use app\core\Route;
use app\models\User;

class Authorization extends AbstractController    // ToDo: create log out
{
    /**
     * Error message, showing if password or login incorrect
     */
    const LOGIN_ERROR = 'The login information you entered is incorrect.';
    /**
     * Error message, showing if passwords not match
     */
    const PASSWORD_ERROR = 'Passwords do not match.';
    /**
     * Error message, showing if username is already taken
     */
    const USERNAME_ERROR = 'This username is already been taken. Please enter another username';    // ToDo: this message must be showing near with login input
    /**
     * Error message, showing if account with this email already exists
     */
    const EMAIL_ERROR = 'An account with this email already exists. Please enter another email.';    // ToDo: this message must be showing near with email input
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
     * Checks if whether the user is logged in, retrieves params, adds to them title and template names and calls method to render login page with those params
     */
    public function index(array $params = []) : void    // ToDo: add view password
    {
        $this->checkLogin();

        $params['title'] = 'Welcome';   
        $params['templateName'] = 'login-section';   

        $this->view->render('login_index', $params);
    }
    /**
     * Checks method being used and whether the user is logged in, receives user values from post, validates it and trying to find that user in db table, if find - login that user and redirect to default page, if error happen - return to login page with inputed values and error message
     */
    public function login() : void
    {
        $this->checkMethod();
        $this->checkLogin();
        
        $identity = filter_input(INPUT_POST, 'identity');
        $pass = filter_input(INPUT_POST, 'pass');
        
        try{
            $this->checkInputedValue($identity, 'Login');
            $this->checkInputedValue($pass, 'Password', PASS_MIN, PASS_MAX, true);
            
            $result = $this->model->find($identity, $pass);
            if(is_null($result)){
                throw new \InvalidArgumentException(self::LOGIN_ERROR);
            }
            $this->loginUser($result);    
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
    /**
     * Logs the user in and redirect to default page
     */
    private function loginUser(array $user) : void
    {
        session_start();
        $_SESSION[LOGIN_FLAG] = true;
        $_SESSION['user'] = $user;

        Route::redirect(Route::url());
    }
    /**
     * Checks if whether the user is logged in, retrieves params, adds to them title name and calls method to render registration page with those params
     */
    public function registration(array $params = []) : void
    {
        $this->checkLogin();

        $params['title'] = 'Registration';
        $this->view->render('login_registration', $params); 
    }
    /**
     * Checks method being used and whether the user is logged in, receives user values from post, validates it, checks if unique and trying to add user in db table, if error happen return to registration page with inputed values and error message, in event of success login new user and redirect to default page
     */
    public function store() : void
    {
        $this->checkMethod();
        $this->checkLogin(); 

        $email = filter_input(INPUT_POST, 'email');
        $login = filter_input(INPUT_POST, 'login');
        $pass = filter_input(INPUT_POST, 'pass');
        $passConf = filter_input(INPUT_POST, 'pass-conf');

        try{
            $this->checkInputedValue($pass, 'Password', PASS_MIN, PASS_MAX, true);
            if($pass !== trim($passConf)){    // because trim() used in checkInputedValue()
                throw new \InvalidArgumentException(self::PASSWORD_ERROR);
            }
            $this->checkInputedValue($email, 'Email', EMAIL_MIN, EMAIL_MAX);
            $this->checkInputedValue($login, 'Login', LOGIN_MIN, LOGIN_MAX);
            
            $this->checkUnique($email, self::EMAIL_ERROR);
            $this->checkUnique($login, self::USERNAME_ERROR);

            $result = $this->model->add($email, $login, $pass);
            if(is_null($result)){
                throw new \InvalidArgumentException(self::LOGIN_ERROR);
            }
            
            $this->loginUser($this->model->getUser($login));   
        }catch (\InvalidArgumentException $e){
            $this->registration([
                'errorMessage' => $e->getMessage(),
                'email' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
                'login' => htmlspecialchars($login, ENT_QUOTES, 'UTF-8'),
                'pass' => htmlspecialchars($pass, ENT_QUOTES, 'UTF-8'),
                'passConf' => htmlspecialchars($passConf, ENT_QUOTES, 'UTF-8'),
            ]);
        }finally {
            exit();
        }
    }
    /**
     * Checks if value unique in database table, if not - throw exception with message
     * @param string|int $value value to be checked, it can be login or email of user
     * @param string $message, certain message for inputed value
     */
    private function checkUnique(string|int $value, string $message) : void
    {
        $result = $this->model->getUser($value); 
        if(!empty($result)){
            throw new \InvalidArgumentException($message);
        }
    }
    /**
     * Checks if using post method, if not - throw custom exception
     */
    private function checkMethod() : void
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            throw new \app\exceptions\NotAllowedException();
        }
    }
    /**
     * Checks if user already loggin, if so - redirect to default page
     */
    private function checkLogin() : void
    {
        session_start();
        if(isset($_SESSION[LOGIN_FLAG])){
            Route::redirect(Route::url());
            exit();
        }
    }
    /**
     * Validates value, if find error - throw exception with him
     * @param string|int $value value to validate, passed by reference to be changed by trim()
     * @param string $type name of value, need to exception message
     * @param int $min min value to validation(), int_min by default
     * @param int $min max value to validation(), int_max by default
     * @param bool $isNumber boolean flag for validation()
     */
    private function checkInputedValue(string|int &$value, string $type, int $min = PHP_INT_MIN, int $max = PHP_INT_MAX, bool $isNumber = false) : void
    {
        $value = trim($value);

        $error = $this->validation($value, $min, $max, $isNumber);
        if(!is_null($error)){
            throw new \InvalidArgumentException($type . $error);
        }
    }
    /**
     * Validates value, searches error and return it if find
     * @param string|int $value value for validation
     * @param int $min optional min value for check, min int by default
     * @param int $max optional max value for check, max int by default
     * @param bool $isNumber boolean flag for special check password
     * @return string error message or empty string
     */
    private function validation(string|int &$value, int $min, int $max, bool $isNumber = false) : ?string
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