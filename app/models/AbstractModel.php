<?php

namespace app\models;

use app\core\Singleton;

abstract class AbstractModel
{
    /**
     * Use trait with singleton pattern and set alias
     */
    use Singleton {
        Singleton::getInstance as getInstanceSingleton;
    }
    /**
     * Object of class mysqli
     */
    protected $db;
    /**
     * Gets instance of this class from method of singleton trait, if database connection not exists trying to create it, throw error if connection error occurs, ultimately returns instance of this class
     * @param string $name name of database
     * @param string $host name of host
     * @param string $user user login
     * @param string $pass user pass
     * @return static instance of this class
     */
    public static function getInstance(string $name = DB_NAME, string $host = DB_HOST, string $user = DB_USER, string $pass = DB_PASS) : static
    {
        $instance = self::getInstanceSingleton();
        if (!isset($instance->db)) {
            $instance->db = new \mysqli($host, $user, $pass, $name);
        }
        if($instance->db->connect_errno){
            throw new \app\exceptions\ConnectionException($instance->db->connect_error);    // ToDo: modify
        }
        return $instance;
    }
}