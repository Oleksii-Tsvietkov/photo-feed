<?php

namespace app\controllers;

use app\models\User;
use app\core\Route;

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
     * Name of authorization page
     */
    const DEFAULT_PAGE = 'authorization_index';
    /**
     * Name of registration page
     */
    const REGISTRATION_PAGE = 'authorization_registration';
    /**
     * Name of authorization template
     */
    const DEFAULT_TEMPLATE = 'authorization_section';
    /**
     * Returns instance of this class from parent method, initializes property model if it not initialized
     * @param static returns instance of this class
     */
    public static function getInstance() : static    
    {
        $instance = parent::getInstance();
        if(!isset($instance->model)){
            $instance->model = User::getInstance();
        }
        return $instance;
    }
    /**
     * Checks if whether the user is logged in, retrieves params, adds to them title and template names and calls method to render login page with those params
     * @param array $params optional params to be added to render method, empty array by default
     */
    public function index(array $params = []) : void
    {
        $this->checkLogin(true);
        
        $params += [
            'title' => 'Welcome',
            'templateName' => self::DEFAULT_TEMPLATE,
        ];
        $this->view->render(self::DEFAULT_PAGE, $params);
    }
    /**
     * Checks method being used and whether the user is logged in, receives user values from post, validates it and trying to find that user in database table, if find - login that user and redirect to default page, if error occurs - return to login page with inputtedvalues and error message
     */
    public function login() : void
    {
        $this->checkMethod();
        $this->checkLogin(true);
        
        $identity = filter_input(INPUT_POST, 'identity');
        $pass = filter_input(INPUT_POST, 'pass');
        
        try{
            $identity = $this->validateInputedValue($identity, 'Login', true);
            $pass = $this->validateInputedValue($pass, 'Password', false, PASS_MIN, PASS_MAX, true);
            
            $result = $this->model->find($identity, $pass);
            if(is_null($result)){
                throw new \InvalidArgumentException(self::LOGIN_ERROR);
            }
            $this->loginUser($result, true);    
        }catch (\InvalidArgumentException $e){
            $this->index([
                'errorMessage' => $e->getMessage(),
                'identity' => htmlspecialchars($identity, ENT_QUOTES, 'UTF-8'),
                'pass' => htmlspecialchars($pass, ENT_QUOTES, 'UTF-8'),
            ]);
        }
        exit();
    }
    /**
     * Checks if whether the user is logged in, retrieves params, adds to them title name and calls method to render registration page with those params
     * @param array $params optional params to be added to render method, empty array by default
     */
    public function registration(array $params = []) : void
    {
        $this->checkLogin(true);

        $params += [
            'title' => 'Registration',
        ];
        $this->view->render(self::REGISTRATION_PAGE, $params); 
    }
    /**
     * Checks method being used and whether the user is logged in, receives user values from POST, validates it, checks if unique and trying to add user in db table, if error happen return to registration page with inputtedvalues and error message, in event of success login new user and redirect to default page
     */
    public function store() : void
    {
        $this->checkMethod();
        $this->checkLogin(true); 

        $email = filter_input(INPUT_POST, 'email');
        $login = filter_input(INPUT_POST, 'login');
        $pass = filter_input(INPUT_POST, 'pass');
        $passConf = filter_input(INPUT_POST, 'pass-conf');

        try{
            $pass = $this->validateInputedValue($pass, 'Password', false, PASS_MIN, PASS_MAX, true);
            if($pass !== trim($passConf)){    // because trim() used in validateInputedValue()
                throw new \InvalidArgumentException(self::PASSWORD_ERROR);
            }
            $email = $this->validateInputedValue($email, 'Email', true, EMAIL_MIN, EMAIL_MAX);
            $login = $this->validateInputedValue($login, 'Login', true, LOGIN_MIN, LOGIN_MAX);
            
            $this->checkUnique($email, self::EMAIL_ERROR);
            $this->checkUnique($login, self::USERNAME_ERROR);
            
            if(!$this->model->add($email, $login, $pass)){
                throw new \InvalidArgumentException(self::LOGIN_ERROR);
            }
            $this->loginUser($this->model->getUser($login), true);   
        }catch (\InvalidArgumentException $e){
            $this->registration([
                'errorMessage' => $e->getMessage(),
                'email' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
                'login' => htmlspecialchars($login, ENT_QUOTES, 'UTF-8'),
                'pass' => htmlspecialchars($pass, ENT_QUOTES, 'UTF-8'),
                'passConf' => htmlspecialchars($passConf, ENT_QUOTES, 'UTF-8'),
            ]);
        }
        exit();
    }
    /**
     * Checks whether the user is logged in, if so logout him, ultimately redirect to default page
     */
    public function logout() : void
    {
        $this->checkLogin(false);

        $this->loginUser(null, false);
    }
    /**
     * Login or logout user, depending on inputtedvalues, finally redirect do default page
     * @param ?array $user array of user data or null
     * @param bool $login boolean flag, true if login, false if logout
     */
    private function loginUser(?array $user, bool $login) : void
    {
        session_start();
        $_SESSION[LOGIN_FLAG] = $login;
        if($user != null){
            $_SESSION['user'] = [
                'id' => $user['id'],
                'login' => $user['login'],
                'email' => $user['email'],
                'image' => $user['image'],
            ];
        }else{
            unset($_SESSION['user']);
        }

        Route::redirect(Route::url());
    }
    /**
     * Checks if value unique in database table, if not - throw exception with message
     * @param string $value value to be checked, can be login or email of user
     * @param string $message certain message to be added in thrown exception
     */
    private function checkUnique(string $value, string $message) : void
    {
        $result = $this->model->getUser($value); 
        if(!empty($result)){
            throw new \InvalidArgumentException($message);
        }
    }
}