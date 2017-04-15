<?php

namespace Itb;

use Mattsmithdev\PdoCrudRepo\DatabaseManager;
use Mattsmithdev\PdoCrudRepo\DatabaseTable;

class User extends DatabaseTable
{
    const ROLE_USER = 1;
    const ROLE_LECTURER = 2;
    const ROLE_ADMIN = 3;

    private $id;
    public $firstName;
    public $surname;
    private $password;
    public $role;
    public $email;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $role
     */

    /**
     * hash the password before storing ...
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $this->password = $hashedPassword;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * return success (or not) of attempting to find matching username/password in the repo
     * @param $collegeId
     * @param $password
     *
     * @return bool
     */
    public static function canFindMatchingCollegeIdAndPassword($collegeId, $password)
    {
        $user = User::getOneByCollegeId($collegeId);

        // if no record has this username, return FALSE
        if(null == $user){
            return false;
        }

        // hashed correct password
        $hashedStoredPassword = $user->getPassword();

        // return whether or not hash of input password matches stored hash
        return password_verify($password, $hashedStoredPassword);
    }

    /**
     *
     * @param $collegeId
     *
     * @return mixed|null
     */
    public static function getOneByCollegeId($collegeId)
    {
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        $sql = 'SELECT * FROM users WHERE collegeId=:collegeId';
        $statement = $connection->prepare($sql);
        $statement->bindParam(':collegeId', $collegeId, \PDO::PARAM_STR);
        $statement->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
        $statement->execute();

        if ($object = $statement->fetch()) {
            return $object;
        } else {
            return null;
        }
    }

    public static function createUser($firstName, $surname, $collegeId, $role, $email, $password)
    {
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        $sql = "INSERT INTO users (firstName, surname, collegeId, role, email, password)
            VALUES ('" . $firstName . "', '" . $surname . "', '" . $collegeId . "', '" . $role . "', '" . $email . "', '" . $password . "');";
        $statement = $connection->query($sql);
    }

    /*public static function getOneByCollegeId($collegeId)
    {
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        $sql = 'SELECT role FROM users WHERE collegeId=:collegeId';
        $statement = $connection->prepare($sql);
        $statement->bindParam(':collegeId', $collegeId, \PDO::PARAM_STR);
        $statement->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
        $statement->execute();

        if ($object = $statement->fetch()) {
            return $object;
        } else {
            return null;
        }
    }*/
 }