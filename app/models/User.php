<?php

namespace app\models;

class User extends AbstractModel
{
    /**
     * Name of table in Data Base
     */
    private const TABLE_NAME = "users";

    public function find(string $identity, string $pass) : ?array
    {
        $user = $this->getuser($identity);
        if($user !== [] && password_verify($pass, $user[0]["password"])){
            return $user[0];
        }
        return null;
    }
    
    public function getUser(string $identity) : array
    {
        $user = [];
        $query = $this->db->prepare("SELECT * FROM users u WHERE u.login=? OR u.email=?;");

        $query->bind_param("ss", $identity, $identity);
        if($query->execute()){
            $result = $query->get_result();
            $user = $result->fetch_all(MYSQLI_ASSOC);
        }
        return $user;
    }
}