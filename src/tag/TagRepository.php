<?php
/**
 * Created by PhpStorm.
 * User: LU
 * Date: 06/04/2017
 * Time: 14:50
 */

namespace Itb\tag;

use Itb\UserRepository;
use Mattsmithdev\PdoCrudRepo\DatabaseTable;
use Mattsmithdev\PdoCrudRepo\DatabaseTableRepository;
use Mattsmithdev\PdoCrudRepo\DatabaseManager;

class TagRepository extends DatabaseTableRepository
{
    function __construct()
    {
        $namespace = 'Itb\tag';
        $classNameForDbRecords = 'Tag';
        $tableName = 'tags';
        parent::__construct($namespace, $classNameForDbRecords, $tableName);
    }

    public function createTag($proposedTag, $tagDescription, $creatorId)
    {
        $tag = new Tag();
        $tag->setTagName($proposedTag);
        $tag->setTagDescription($tagDescription);
        $tag->setCreatorId($creatorId);
        $tag->setVoteCount(0);
        $tag->setAccepted(0);
        DatabaseTableRepository::create($tag);
    }

    public function returnAllTags()
    {
        $proposedTag = DatabaseTableRepository::getAll();
        return $proposedTag;
    }

    public function voteUpTag($id, $voteValue)
    {
        $proposedTag = $this->getOneById($id);
        $voteCount = $proposedTag->voteCount;
        $voteCount = $voteCount + $voteValue;
        $proposedTag->voteCount = $voteCount;
        $this->update($proposedTag);

        return;
    }

    public function voteDownTag($id, $voteValue)
    {
        $proposedTag = $this->getOneById($id);
        $voteCount = $proposedTag->voteCount;
        $voteCount = $voteCount - $voteValue;
        $proposedTag->voteCount = $voteCount;
        $this->update($proposedTag);

        return;
    }

    function getTags()
    {
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        $accepted = 1;

        $sql = 'SELECT accepted, id, tagName FROM tags WHERE accepted=:accepted';
        $statement = $connection->prepare($sql);
        $statement->bindParam(':accepted', $accepted, \PDO::PARAM_STR);
        //$statement->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
        $statement->execute();
        /*$objects = $statement->fetchAll();*/

        $tags = $statement->fetchAll();

        return $tags;
    }
}