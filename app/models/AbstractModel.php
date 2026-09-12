<?php

namespace app\models;

abstract class AbstractModel
{
    /**
     * Object of class mysqli
     */
    protected $db;
    /**
     * Create connection with mysqli
     * @param string $name name of data base
     * @param string $host name of host
     * @param string $user user login to db connect
     * @param string $pass user pass to db connect
     */
    public function __construct(string $name = DB_NAME, string $host = DB_HOST, string $user = DB_USER, string $pass = DB_PASS)
    {
        $this->db = new \mysqli($host, $user, $pass, $name);    // without \ class not found
        if($this->db->connect_errno){
            throw new app\exceptions\ConnectionException('no db connection: ' . $db->connect_error);    // modify
        }
    }
}