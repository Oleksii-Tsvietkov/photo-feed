<?php

namespace app\controllers;

class Authorization extends AbstractController
{
    
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
    public function index() : void
    {
        $this->view->render('login_index', [
            'title' => 'Welcome',
            'templateName' => 'login-section',
        ]);
    }
    public function login() : void
    {
        $this->checkMethod();

        $identity = filter_input(INPUT_POST, 'identity');
        $pass = filter_input(INPUT_POST, 'pass');

        if($this->model->find($identity, $pass)){

        }else{

        }
        
    }
    public function registration() : void
    {
        $this->checkMethod();
        // ToDo: maybe check if login
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
            throw new app\exceptions\NotAllowedException();
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
    private function validation($value, int $min = PHP_INT_MIN, int $max = PHP_INT_MAX, bool $isNumber = false) : string
    {
        $error = '';
        if(!isset($value)) {
            $error = 'is no value';
        }else if(is_null($value)){
            $error = 'is null';    
        }else if(empty($value)){ 
            $error = 'is empty or null';
        }else if($value == 0){
            $error = 'is zero value';
        }else if(!$isNumber && is_numeric($value)){
            $error = 'is not text';
        }else{
            $length = strlen($value);
            if($length < $min){
                $error =  "is to short, min length $min";
            }else if($length > $max){
                $error = "is to long, max length $max";
            }
        }

        return $error;
    }
}