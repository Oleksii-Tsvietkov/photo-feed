<?php

namespace app\models;

use app\core\Singleton;

abstract class AbstractModel    // ToDo: add singletone
{
    /**
     * Use trait with singletone pattern and set alias
     */
    use Singleton {
        Singleton::getInstance as getInstanceSingleton;
    }
    /**
     * Object of class mysqli
     */
    protected $db;
    /**
     * Gets instance of this class from getInstance(), if db connection not exists trying to create it, throw error if connection error happen, ultimately returns instance of this class
     * @param string $name name of data base to db connect
     * @param string $host name of host to db connect 
     * @param string $user user login to db connect
     * @param string $pass user pass to db connect
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