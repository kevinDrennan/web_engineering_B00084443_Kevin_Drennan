<?php

namespace Itb;

use Itb\bib\Bib;
use Itb\bib\BibRepository;
use Itb\ref\RefRepository;
use Itb\tag\TagRepository;

class BibController
{
    private $app;
    private $loginController;

    public function __construct(WebApplication $app)
    {
        $this->app = $app;
        $this->loginController = new LoginController($app);
    }

    public function viewBibs()
    {
        $message = '';
        $role = $this->loginController->roleFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();
        $bibRepository = new BibRepository();
        $allBibs = $bibRepository->getAll();

        $template = 'bib/viewBibs';
        $argsArray = [
            'message' => $message,
            'role' => $role,
            'allBibs' => $allBibs,
            'collegeId' => $collegeId
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function viewBibDetails($id)
    {
        $role = $this->loginController->roleFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();
        $bibRepository = new bibRepository();
        $bib = $bibRepository->getOneById($id);
        $refsForBib = $bibRepository->getRefsForBib($id);

        $template = 'bib/bibDetails';
        $argsArray = [
            'refsForBib' => $refsForBib,
            'role' => $role,
            'bib' => $bib,
            'collegeId' => $collegeId
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function proposeBibPage()
    {
        $role = $this->loginController->roleFromSession();
        $isLoggedIn = $this->loginController->isLoggedInFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();
        $refRepository = new RefRepository();
        $allRefs = $refRepository->getAll();
        $message = '';
        $template = 'bib/proposeBib';
        $argsArray = [
            'role' => $role,
            'allRefs' => $allRefs,
            'collegeId' => $collegeId,
            'message' => $message,
            'isLoggedIn' => $isLoggedIn
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function saveBib()
    {
        $message = '';
        $title = filter_input(INPUT_POST, 'bibTitle', FILTER_SANITIZE_STRING);
        $summary = filter_input(INPUT_POST, 'bibSummary', FILTER_SANITIZE_STRING);

        $role = $this->loginController->roleFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();
        $isLoggedIn = $this->loginController->isLoggedInFromSession();

        /*
         * creates ref in database, giving creator an id of 0 if not logged in
         */
        if($isLoggedIn == false){
            $collegeId = 0;
        }
        $accepted = 0;
        $creatorId = $collegeId;
        $bibRepository = new BibRepository();
        $bibRepository->createBib($title, $summary, $creatorId, $accepted);
        $refRepository = new RefRepository();
        $allRefs = $refRepository->getAll();
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

    public function showUsersBibs($id)
    {
        $message = '';
        $role = $this->loginController->roleFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();

        $bibRepository = new BibRepository();
        $usersBibs = $bibRepository->getBibsOfUser($collegeId);

        $template = 'bib/usersBibs';
        $argsArray = [
            'refId' => $id,
            'message' => $message,
            'role' => $role,
            'usersBibs' => $usersBibs,
            'collegeId' => $collegeId
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function addRefToBib($bibId, $refId)
    {
        $message = '';
        $role = $this->loginController->roleFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();
        $bibRepository = new BibRepository();
        $bibRepository->insertIntoBibrefs($bibId, $refId);
        $app = $this->app;
        $refRepository = new RefRepository();
        $allRefs = $refRepository->getAll();
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

    public function modifyUsersBibsIndex()
    {
        $message = '';
        $role = $this->loginController->roleFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();

        $bibRepository = new BibRepository();
        $usersBibs = $bibRepository->getBibsOfUser($collegeId);
        $usersBibs = $this->getBibStatus($usersBibs);

        $template = 'bib/modifyUsersBibsIndex';
        $argsArray = [
            'message' => $message,
            'role' => $role,
            'usersBibs' => $usersBibs,
            'collegeId' => $collegeId
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function getBibStatus($bibArray)
    {
        foreach($bibArray as $key=> $bib){
            if($bib->accepted == 0) {
                $bib->accepted = 'private';
            }
                elseif($bib->accepted == 1) {
                    $bib->accepted = 'public';
                }
                elseif($bib->accepted == 2){
                $bib->accepted = 'published';
            }
        }
        return $bibArray;
    }

    public function modifyUserBib($id)
    {
        $message = '';
        $role = $this->loginController->roleFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();
        $bibRepository = new bibRepository();
        $bib = $bibRepository->getOneById($id);
        $refsForBib = $bibRepository->getRefsForBib($id);

        $template = 'bib/modifyUserBib';
        $argsArray = [
            'message' => $message,
            'refsForBib' => $refsForBib,
            'role' => $role,
            'bib' => $bib,
            'collegeId' => $collegeId
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function updateBib($id)
    {
        $role = $this->loginController->roleFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();

        $title = filter_input(INPUT_POST, 'bibTitle', FILTER_SANITIZE_STRING);
        $accepted = filter_input(INPUT_POST, 'accepted', FILTER_SANITIZE_STRING);
        $summary = filter_input(INPUT_POST, 'bibSummary', FILTER_SANITIZE_STRING);
        $bib = Bib::getOneById($id);
        $bib->title = $title;
        $bib->summary = $summary;
        $bib->accepted = $accepted;

        Bib::update($bib);
        $bibRepository = new BibRepository();
        $usersBibs = $bibRepository->getBibsOfUser($collegeId);
        $usersBibs = $this->getBibStatus($usersBibs);

        $message = 'This record has been updated';
        $template = 'bib/modifyUsersBibsIndex';
        $argsArray = [
            'role' =>$role,
            'message' => $message,
            'usersBibs' => $usersBibs,
            'collegeId' => $collegeId
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function deleteRefFromBib($refId, $bibId)
    {
        $role = $this->loginController->roleFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();

        $bibRepository = new BibRepository();
        $bibRepository->delteRefFromBibFunction($refId, $bibId);
        $bib = $bibRepository->getOneById($bibId);
        $refsForBib = $bibRepository->getRefsForBib($bibId);

        $message = 'This ref has been deleted';
        $template = 'bib/modifyUserBib';
        $argsArray = [
            'role' =>$role,
            'bib' => $bib,
            'message' => $message,
            'refsForBib' => $refsForBib,
            'collegeId' => $collegeId
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function publishBib($id)
    {
        $bib = Bib::getOneById($id);
        $bib->accepted = 2;
        Bib::update($bib);

        $message = 'Bib has been published';
        $role = $this->loginController->roleFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();
        $bibRepository = new BibRepository();
        $allBibs = $bibRepository->getAll();

        $template = 'bib/viewBibs';
        $argsArray = [
            'message' => $message,
            'role' => $role,
            'allBibs' => $allBibs,
            'collegeId' => $collegeId
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function lecturerViewBibToModify($id)
    {
        $message = '';
        $role = $this->loginController->roleFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();
        $bibRepository = new bibRepository();
        $bib = $bibRepository->getOneById($id);
        $refsForBib = $bibRepository->getRefsForBib($id);

        $template = 'bib/lecturerEditTitleSummary';
        $argsArray = [
            'message' => $message,
            'refsForBib' => $refsForBib,
            'role' => $role,
            'bib' => $bib,
            'collegeId' => $collegeId
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function publishModifiedBib($id)
    {
        $role = $this->loginController->roleFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();

        $title = filter_input(INPUT_POST, 'bibTitle', FILTER_SANITIZE_STRING);
        $summary = filter_input(INPUT_POST, 'bibSummary', FILTER_SANITIZE_STRING);
        $bib = Bib::getOneById($id);
        $bib->title = $title;
        $bib->summary = $summary;
        $bib->accepted = 2;

        Bib::update($bib);
        $bibRepository = new BibRepository();
        $allBibs = $bibRepository->getAll();

        $message = 'This record has been updated';
        $template = 'bib/viewBibs';
        $argsArray = [
            'role' =>$role,
            'message' => $message,
            'allBibs' => $allBibs,
            'collegeId' => $collegeId
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }
}