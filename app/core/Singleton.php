<?php

namespace app\core;

trait Singleton
{
    /**
     * Instance of singleton class
     */
    private static $instance;
    /**
     * Check, if instance of this class dont exists create it, if not - do nothing, ultimately return instance
     * @return static instance of this class 
     */
    public static function getInstance(): static
    {
        if(!self::$instance){
            self::$instance = new static();
        }
        return self::$instance;
    }
    /**
     * Class creation methods, needed for Singleton pattern
     */
    private function __construct(){}
    private function __clone(){}
    public function __wakeup()
    {
        exit('Singleton');
    }
}