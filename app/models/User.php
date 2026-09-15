<?php

namespace app\models;

class User extends AbstractModel
{
    /**
     * Name of table in Data Base
     */
    private const TABLE_NAME = "users";
    /**
     * Search user by identity, if find compare passwords, if passwords identical returns user
     * @param string $identity login or email of user
     * @param string $pass user password with hash
     * @return ?array returns user array if find and if passwords identical, else returns null
     */
    public function find(string $identity, string $pass) : ?array
    {
        $user = $this->getuser($identity);
        if(!empty($user) && password_verify($pass, $user["password"])){
            return $user;
        }
        return null;
    }
    /**
     * Search user in database table by identity, if find - return him
     * @param string $identity login or email of user
     * @return array returns user array if find him, empty array if query error happen or null if user not find 
     */
    public function getUser(string $identity) : ?array
    {
        $user = [];

        $query = $this->db->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE login = ? OR email = ?;");
        if($query->bind_param("ss", $identity, $identity)){
            if($query->execute()){
                $result = $query->get_result();
                $user = $result->fetch_assoc();    //ToDo: if find users more than one?
            }
        }

        return $user;  
    }
    /**
     * Added user to database table, hash inputed password, return true if user added successfully.
     * @param string $email user email
     * @param string $login user login
     * @param string $pass user password
     * @return bool return true if  if user added successfully, else - false
     */
    public function add(string $email, string $login, string $pass): bool
    {
        $passHash = password_hash($pass, PASSWORD_BCRYPT);

        $query = $this->db->prepare("INSERT INTO " . self::TABLE_NAME . "(email, login, password) VALUES(?, ?, ?);");
        if($query){
            if($query->bind_param("sss", $email, $login, $passHash)){
                return $query->execute();    
            }
        }

        return false;
    }
}