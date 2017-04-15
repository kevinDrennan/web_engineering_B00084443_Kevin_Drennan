<?php

namespace Itb\ref;

use Mattsmithdev\PdoCrudRepo\DatabaseTableRepository;
use Mattsmithdev\PdoCrudRepo\DatabaseManager;

class refRepository extends DatabaseTableRepository
{
    function __construct()
    {
        $namespace = 'Itb\ref';
        $classNameForDbRecords = 'Ref';
        $tableName = 'refs';
        parent::__construct($namespace, $classNameForDbRecords, $tableName);
    }

    public function createRef($refAuthor, $refTitle, $refYear, $refPublisher,
                                      $refPlaceOfPublication, $refSummary, $creatorId)
    {
        $ref = new ref();
        $ref->setAuthor($refAuthor);
        $ref->setTitle($refTitle);
        $ref->setYear($refYear);
        $ref->setPublisher($refPublisher);
        $ref->setPlaceOfPublication($refPlaceOfPublication);
        $ref->setSummary($refSummary);
        $ref->setCreatorId($creatorId);
        $ref->setAccepted(0);
        DatabaseTableRepository::create($ref);
    }

    public function getIdOfCreatedRef()
    {
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        $sql = 'SELECT MAX(id) FROM refs';
        $statement = $connection->prepare($sql);
        $statement->execute();

        $id = $statement->fetch();

        return $id;
    }

    public function insertIntoReftags($idOfRef, $chosenTags)
    {
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        foreach($chosenTags as $tag) {
            $sql = 'INSERT INTO reftags (refid, tagid)  VALUES (:idOfRef, :tag)';
            $statement = $connection->prepare($sql);
            $statement->bindParam(':idOfRef', $idOfRef, \PDO::PARAM_STR);
            $statement->bindParam(':tag', $tag, \PDO::PARAM_STR);
            $statement->execute();
        }
    }
}