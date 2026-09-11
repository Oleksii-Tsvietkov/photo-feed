<?php

namespace app\controllers;

class Authorization extends AbstractController
{
    /**
     * Name of login page
     */
    const LOGIN_PAGE = 'authorization';
    /**
     * Sets to parrents construct name of layout page
     */
    public function __construct()
    {
        parent::__construct(self::LOGIN_PAGE);
    }
    /**
     * Calls render method that show authorization page
     */
    public function index() : void
    {
        // if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        //     // ToDo: process
        // }
        // ToDo: maybe check if login
        $this->view->render('login_index', [
            'title' => 'Welcome',
        ]);
    }
    public function login() : void
    {

    }
    public function registration() : void
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            // ToDo: process
        }
        // ToDo: maybe check if login
        $this->view->render('login_registration', [
            'title' => 'Registration',
        ]); 
    }

}