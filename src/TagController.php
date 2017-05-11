<?php

namespace Itb;

use Itb\tag\TagRepository;

class TagController
{
    private $app;

    public function __construct(WebApplication $app)
    {
        $this->app = $app;
        $this->loginController = new LoginController($app);
    }

    public function processProposedTagAction()
    {
        $proposedTag = filter_input(INPUT_POST, 'proposedTag', FILTER_SANITIZE_STRING);
        $tagDescription = filter_input(INPUT_POST, 'tagDescription', FILTER_SANITIZE_STRING);
        $collegeId = $this->loginController->collegeIdFromSession();
        $isLoggedIn = $this->loginController->isLoggedInFromSession();

        if($isLoggedIn == false){
            $collegeId = 0;
        }
        $creatorId = $collegeId;
        $TagRepository = new TagRepository();
        $TagRepository->createTag($proposedTag, $tagDescription, $creatorId);

        $collegeId = $this->loginController->collegeIdFromSession();
        $isLoggedIn = $this->loginController->isLoggedInFromSession();
        $message = 'Your tag has been submitted';

        $template = 'proposeTag';
        $argsArray = [
            'collegeId' => $collegeId,
            'message' => $message,
            'isLoggedIn' => $isLoggedIn
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function viewProposedTagsAction()
    {
        /*
         * $array returns index 0 = template, index 1 = tag objects
         */
        $array = $this->getVariablesForViewProposedTags();
        $template = $array[0];
        $argsArray = $array[1];

        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    /*
     * increases voteCount and returns to view proposed tags page
     * voteCount increased by 1 for public, 5 for student/lecturer
     */
    public function upVoteTagAction($id)
    {
        $isLoggedIn = $this->loginController->isLoggedInFromSession();

        $voteValue = 1;
        if($isLoggedIn){
            $voteValue = 5;
        }

        $proposedTagRepository = new TagRepository();
        $proposedTagRepository->voteUpTag($id, $voteValue);

        $array = $this->getVariablesForViewProposedTags();
        $template = $array[0];
        $argsArray = $array[1];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function downVoteTagAction($id)
    {
        $isLoggedIn = $this->loginController->isLoggedInFromSession();

        $voteValue = 1;
        if($isLoggedIn){
            $voteValue = 5;
        }
        $tagRepository = new TagRepository();
        $tagRepository->voteDownTag($id, $voteValue);

        $array = $this->getVariablesForViewProposedTags();
        $template = $array[0];
        $argsArray = $array[1];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function getVariablesForViewProposedTags()
    {
        $proposedTagRepository = new TagRepository();
        $proposedTags = $proposedTagRepository->returnAllTags();

        $collegeId = $this->loginController->collegeIdFromSession();

        $template = 'viewProposedTags';
        $argsArray = [
            'collegeId' => $collegeId,
            'proposedTags' => $proposedTags
        ];

        $array = array($template, $argsArray);
        return $array;
    }
}