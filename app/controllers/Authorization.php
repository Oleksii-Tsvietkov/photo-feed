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
     * Name of default page
     */
    const DEFAULT_PAGE = 'authorization_index';
    /**
     * Name of default page
     */
    const REGISTRATION_PAGE = 'authorization_registration';
    /**
     * Name of default template
     */
    const DEFAULT_TEMPLATE = 'authorization_section';
    /**
     * Initializes property and sets to parrents construct name of layout page
     */
    public static function getInstance() : static    // ToDo: check
    {
        $instance = parent::getInstance();
        if(!isset($instance->model)){
            $instance->model = User::getInstance();
        }
        return $instance;
    }
    /**
     * Checks if whether the user is logged in, retrieves params, adds to them title and template names and calls method to render login page with those params
     */
    public function index(array $params = []) : void
    {
        $this->checkLogin(true);
        
        $params['title'] = 'Welcome';   
        $params['templateName'] = self::DEFAULT_TEMPLATE;   

        $this->view->render(self::DEFAULT_PAGE, $params);
    }
    /**
     * Checks method being used and whether the user is logged in, receives user values from post, validates it and trying to find that user in db table, if find - login that user and redirect to default page, if error happen - return to login page with inputed values and error message
     */
    public function login() : void
    {
        $this->checkMethod();
        $this->checkLogin(true);
        
        $identity = filter_input(INPUT_POST, 'identity');
        $pass = filter_input(INPUT_POST, 'pass');
        
        try{
            $identity = $this->validateInputedValue($identity, 'Login');
            $pass = $this->validateInputedValue($pass, 'Password', PASS_MIN, PASS_MAX, true);
            
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
        }finally{
            exit();
        }
    }
    /**
     * Checks if whether the user is logged in, retrieves params, adds to them title name and calls method to render registration page with those params
     */
    public function registration(array $params = []) : void
    {
        $this->checkLogin(true);

        $params['title'] = 'Registration';
        $this->view->render(self::REGISTRATION_PAGE, $params); 
    }
    /**
     * Checks method being used and whether the user is logged in, receives user values from post, validates it, checks if unique and trying to add user in db table, if error happen return to registration page with inputed values and error message, in event of success login new user and redirect to default page
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
            $pass = $this->validateInputedValue($pass, 'Password', PASS_MIN, PASS_MAX, true);
            if($pass !== trim($passConf)){    // because trim() used in validateInputedValue()
                throw new \InvalidArgumentException(self::PASSWORD_ERROR);
            }
            $email = $this->validateInputedValue($email, 'Email', EMAIL_MIN, EMAIL_MAX);
            $login = $this->validateInputedValue($login, 'Login', LOGIN_MIN, LOGIN_MAX);
            
            $this->checkUnique($email, self::EMAIL_ERROR);
            $this->checkUnique($login, self::USERNAME_ERROR);

            $result = $this->model->add($email, $login, $pass);
            if(is_null($result)){
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
        }finally {
            exit();
        }
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
     * Login or logout user, depending on inputed values
     * @param ?array $user array of user data or null
     * @param bool $login boolean flag, true if login, false if logout
     */
    private function loginUser(?array $user, bool $login) : void
    {
        session_start();
        $_SESSION[LOGIN_FLAG] = $login;
        $_SESSION['user'] = $user;    // ToDo: add only certain data

        Route::redirect(Route::url());
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
}