<?php

namespace Itb;

use Itb\bib\BibRepository;
use Itb\ref\RefRepository;
use Itb\tag\TagRepository;

class RefController
{
    private $app;
    private $loginController;
    private $tagRepository;

    public function __construct(WebApplication $app)
    {
        $this->app = $app;
        $this->loginController = new LoginController($app);
        $this->tagRepository = new TagRepository();
    }

    public function processProposedRefAction()
    {
        $refAuthor = filter_input(INPUT_POST, 'refAuthor', FILTER_SANITIZE_STRING);
        $refTitle = filter_input(INPUT_POST, 'refTitle', FILTER_SANITIZE_STRING);
        $refYear = filter_input(INPUT_POST, 'refYear', FILTER_SANITIZE_STRING);
        $refPublisher = filter_input(INPUT_POST, 'refPublisher', FILTER_SANITIZE_STRING);
        $refPlaceOfPublication = filter_input(INPUT_POST, 'refPlaceOfPublication', FILTER_SANITIZE_STRING);
        $refSummary = filter_input(INPUT_POST, 'refSummary', FILTER_SANITIZE_STRING);
        $chosenTags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);//from book

        $role = $this->loginController->roleFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();
        $isLoggedIn = $this->loginController->isLoggedInFromSession();

        /*
         * creates ref in database, giving creator an id of 0 if not logged in
         */
        if($isLoggedIn == false){
            $collegeId = 0;
        }
        $creatorId = $collegeId;
        $proposedRefRepository = new RefRepository();
        $proposedRefRepository->createRef
            ($refAuthor, $refTitle, $refYear, $refPublisher, $refPlaceOfPublication,
            $refSummary, $creatorId);

        /*
         * Information placed into reftags database
         * Done after ref has been created so as to return id of new ref
         */
        //get highest number from id in ref
        //add chosen tags into reftags database
        $refRepository = new RefRepository();
        $idOfCreatedRef = $refRepository->getIdOfCreatedRef();
        $idOfRef = $idOfCreatedRef[0];
        $refRepository->insertIntoReftags($idOfRef, $chosenTags);

        $collegeId = $this->loginController->collegeIdFromSession();
        $isLoggedIn = $this->loginController->isLoggedInFromSession();
        $message = 'Your reference has been submitted';

        $proposedTagRepository = new TagRepository();
        $tags = $proposedTagRepository->getTags();

        $template = 'proposeRef';
        $argsArray = [
            'role' => $role,
            'tags' => $tags,
            'collegeId' => $collegeId,
            'message' => $message,
            'isLoggedIn' => $isLoggedIn
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function viewRefs()
    {
        $message = '';
        $role = $this->loginController->roleFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();
        $refRepository = new RefRepository();
        $allRefs = $refRepository->getAll();
        foreach($allRefs as $ref){
            if($ref->creatorid == '0'){
                $ref->creatorid = 'publicly added';
            }
        }
        $tagRepository = new TagRepository();
        $tags = $tagRepository->getTags();

        $template = 'studentLecturer/viewRefs';
        $argsArray = [
            'message' => $message,
            'role' => $role,
            'tags' => $tags,
            'allRefs' => $allRefs,
            'collegeId' => $collegeId
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function viewRefDetails($id)
    {
        $role = $this->loginController->roleFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();
        $refRepository = new RefRepository();
        $ref = $refRepository->getOneById($id);
        $tagsForRef = $refRepository->getTagsForRef($id);
        $bibRepository = new BibRepository();
        $bibForRef = $bibRepository->getBibsForRef($id);

        $template = 'studentLecturer/refDetails';
        $argsArray = [
            'bibForRef' => $bibForRef,
            'tagsForRef' => $tagsForRef,
            'role' => $role,
            'ref' => $ref,
            'collegeId' => $collegeId
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function searchRefsByTags()
    {
        $message ='';
        $role = $this->loginController->roleFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();
        $referenceIds = $this->getRefsIdFromDatabase();
        $tagRepository = new TagRepository();
        $tags = $tagRepository->getTags();
        /*
         * if no tags were chosen
         */
        if($referenceIds == 0){
            $message = 'no tags were chosen';
            $template = 'studentLecturer/viewRefs';
            $refArray = null;
            $argsArray = [
                'message' => $message,
                'role' => $role,
                'allRefs' => $refArray,
                'tags' => $tags,
                'collegeId' => $collegeId
            ];
            return $this->app['twig']->render($template . '.html.twig', $argsArray);
        }

        /*
         * get ref details from ids in $referenceIds
         */
        $refArray = array();
        $refArray = $this->getRefsDetailsFromIds($referenceIds);

        $template = 'studentLecturer/viewRefs';
        $argsArray = [
            'message' => $message,
            'role' => $role,
            'allRefs' => $refArray,
            'tags' => $tags,
            'collegeId' => $collegeId
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function getRefsIdFromDatabase()
    {
        $chosenTags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
        if(!isset($chosenTags)){
            return 0;
        }
        $referenceId = array();

        if(count($chosenTags) == 1){
            $referenceId = RefRepository::searchBySingleTag($chosenTags);
        }
        elseif(count($chosenTags) == 2){
            $referenceId = RefRepository::searchByTwoTags($chosenTags);
        }
        elseIf(count($chosenTags) == 3){
            $referenceId = RefRepository::searchByThreeTags($chosenTags);
        }
        else{
            $referenceId = 0;
        }

        return $referenceId;
    }

    public function getRefsDetailsFromIds($referenceIds)
    {
        $refRepository = new RefRepository();
        $refArray = array();
        foreach($referenceIds as $key=>$id){
            $refArray[$key] = $refRepository->getOneById($id);
        }
        return $refArray;
    }

    public function searchRefsByFreeText()
    {
        $message ='';
        $role = $this->loginController->roleFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();
        $tagRepository = new TagRepository();
        $tags = $tagRepository->getTags();
        $freeText = filter_input(INPUT_POST, 'freeText', FILTER_SANITIZE_STRING);
        $refRepository = new RefRepository();
        $columnName = 'summary';
        $ref = $refRepository->searchByColumn($columnName, $freeText);

        $template = 'studentLecturer/viewRefs';
        $argsArray = [
            'message' => $message,
            'role' => $role,
            'allRefs' => $ref,
            'tags' => $tags,
            'collegeId' => $collegeId
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }
}
