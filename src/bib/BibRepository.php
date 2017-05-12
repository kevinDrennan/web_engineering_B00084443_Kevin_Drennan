<?php

namespace Itb\bib;

use Mattsmithdev\PdoCrudRepo\DatabaseTableRepository;
use Mattsmithdev\PdoCrudRepo\DatabaseManager;
use Itb\ref\refRepository;
use Itb\LoginController;

class BibRepository extends DatabaseTableRepository
{
    function __construct()
    {
        $namespace = 'Itb\bib';
        $classNameForDbRecords = 'Bib';
        $tableName = 'bibs';
        parent::__construct($namespace, $classNameForDbRecords, $tableName);
    }

    public function getRefsForBib($id)
    {
        $db = new DatabaseManager();
        $connection = $db->getDbh();
        $refRepository = new RefRepository;

        $sql = 'SELECT refId FROM bibrefs WHERE bibId = :id';
        $statement = $connection->prepare($sql);
        $statement->bindParam(':id', $id, \PDO::PARAM_STR);
        $statement->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
        $statement->execute();
        $refIDs = $statement->fetchAll(\PDO::FETCH_COLUMN);

        $refArray = array();
        foreach($refIDs as $key => $id){
            $refArray[$key] = $refRepository->getOneById($id);
        }
        return $refArray;
    }

    public function createBib($title, $summary, $creatorId, $accepted)
    {
        $bib = new bib();
        $bib->setTitle($title);
        $bib->setSummary($summary);
        $bib->setCreatorId($creatorId);
        $bib->setAccepted($accepted);
        DatabaseTableRepository::create($bib);
    }

    public function getBibsOfUser($collegeId)
    {
        $db = new DatabaseManager();
        $connection = $db->getDbh();
        $usersBibs = array();

        $sql = 'SELECT * FROM bibs WHERE creatorId = :collegeId';
        $statement = $connection->prepare($sql);
        $statement->bindParam(':collegeId', $collegeId, \PDO::PARAM_STR);
        $statement->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
        $statement->execute();
        $usersBibs = $statement->fetchAll(\PDO::FETCH_OBJ);

        return $usersBibs;
    }

    public function insertIntoBibrefs($bibId, $refId)
    {
        $db = new DatabaseManager();
        $connection = $db->getDbh();

            $sql = 'INSERT INTO bibrefs (bibId, refId)  VALUES (:bibId, :refId)';
            $statement = $connection->prepare($sql);
            $statement->bindParam(':bibId', $bibId, \PDO::PARAM_STR);
            $statement->bindParam(':refId', $refId, \PDO::PARAM_STR);
            $statement->execute();
    }

    public function delteRefFromBibFunction($refId, $bibId)
    {
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        $sql = 'DELETE FROM bibrefs WHERE bibId = :bibId AND refId = :refId';
        $statement = $connection->prepare($sql);
        $statement->bindParam(':bibId', $bibId, \PDO::PARAM_STR);
        $statement->bindParam(':refId', $refId, \PDO::PARAM_STR);
        $statement->execute();
    }

    public function getBibsForRef($id)
    {
        $db = new DatabaseManager();
        $connection = $db->getDbh();
        $bibRepository = new BibRepository();

        $sql = 'SELECT bibId FROM bibrefs WHERE refId = :id';
        $statement = $connection->prepare($sql);
        $statement->bindParam(':id', $id, \PDO::PARAM_STR);
        $statement->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
        $statement->execute();
        $bibIDs = $statement->fetchAll(\PDO::FETCH_COLUMN);

        $bibArray = array();
        foreach($bibIDs as $key => $id){
            $bibArray[$key] = $bibRepository->getOneById($id);
        }
        return $bibArray;
    }

}
