<?php

namespace Itb;

use Mattsmithdev\PdoCrudRepo\DatabaseTableRepository;
use Mattsmithdev\PdoCrudRepo\DatabaseManager;

class UserRepository extends DatabaseTableRepository
{
    function __construct()
    {
        $namespace = 'Itb';
        $classNameForDbRecords = 'User';
        $tableName = 'users';
        parent::__construct($namespace, $classNameForDbRecords, $tableName);
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

    /**
     *
     * @param $collegeId
     *
     * @return mixed|null
     */
    public static function getRoleFromCollegeId($collegeId)
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
    }

    public static function deleteUser($id)
    {
        $dataTableRepository = new DatabaseTableRepository();
        $dataTableRepository->delete($id);
    }
}