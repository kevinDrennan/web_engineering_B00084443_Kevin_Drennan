<?php

namespace Itb\ref;

use Itb\tag\TagRepository;
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

    public static function searchBySingleTag($chosenTag)
    {
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        $sql = 'SELECT refId FROM reftags WHERE tagId = :chosenTag';
        $statement = $connection->prepare($sql);
        $statement->bindParam(':chosenTag', $chosenTag[0], \PDO::PARAM_STR);
        $statement->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
        $statement->execute();
        $refId = $statement->fetchAll(\PDO::FETCH_COLUMN);
        var_dump('hi');
        return $refId;
    }

    public static function searchByTwoTags($chosenTag)
    {
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        $sql = 'SELECT M1.refId
           FROM reftags M1, reftags M2
            WHERE M1.refId = M2.refId
            AND M1.tagId = :chosenTag1
            AND M2.tagId = :chosenTag2;';
        $statement = $connection->prepare($sql);
        $statement->bindParam(':chosenTag1', $chosenTag[0], \PDO::PARAM_STR);
        $statement->bindParam(':chosenTag2', $chosenTag[1], \PDO::PARAM_STR);
        $statement->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
        $statement->execute();
        $refId = $statement->fetchAll(\PDO::FETCH_COLUMN);

        return $refId;
    }

    public static function searchByThreeTags($chosenTag)
    {
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        $sql = 'SELECT M1.refId
           FROM reftags M1, reftags M2, reftags M3
            WHERE M1.refId = M2.refId
            AND M1.refId = M3.refId
            AND M3.refId = M2.refId
            AND M1.tagId = :chosenTag1
            AND M2.tagId = :chosenTag2
            AND M3.tagId = :chosenTag3;';
        $statement = $connection->prepare($sql);
        $statement->bindParam(':chosenTag1', $chosenTag[0], \PDO::PARAM_STR);
        $statement->bindParam(':chosenTag2', $chosenTag[1], \PDO::PARAM_STR);
        $statement->bindParam(':chosenTag3', $chosenTag[2], \PDO::PARAM_STR);
        $statement->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
        $statement->execute();
        $refId = $statement->fetchAll(\PDO::FETCH_COLUMN);

        return $refId;
    }

    public function getTagsForRef($id)
    {
        $db = new DatabaseManager();
        $connection = $db->getDbh();
        $tagRepository = new TagRepository();
        var_dump('id');
        var_dump($id);

        $sql = 'SELECT tagId FROM reftags WHERE refId = :id';
        $statement = $connection->prepare($sql);
        $statement->bindParam(':id', $id, \PDO::PARAM_STR);
        $statement->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
        $statement->execute();
        $tagIDs = $statement->fetchAll(\PDO::FETCH_COLUMN);
        var_dump('tagIds');
        var_dump($tagIDs);

        $tagArray = array();
        foreach($tagIDs as $key => $id){
            $tagArray[$key] = $tagRepository->getOneById($id);
        }
        return $tagArray;
    }
}