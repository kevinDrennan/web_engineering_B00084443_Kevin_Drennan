<?php

namespace Itb;

use Itb\ref\RefRepository;
use Itb\tag\TagRepository;

class RefController
{
    private $app;

    public function __construct(WebApplication $app)
    {
        $this->app = $app;
        $this->loginController = new LoginController($app);
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
            'tags' => $tags,
            'collegeId' => $collegeId,
            'message' => $message,
            'isLoggedIn' => $isLoggedIn
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }
}