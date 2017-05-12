<?php

namespace Itb;

use Itb\tag\TagRepository;
use Itb\ref\RefRepository;

class MainController
{
    private $loginController;

    private $app;

    public function __construct(WebApplication $app)
    {
        $this->app = $app;
        $this->loginController = new LoginController($app);
    }

    public function indexAction()
    {
        $role = $this->loginController->roleFromSession();
        $isLoggedIn = $this->loginController->isLoggedInFromSession();
        if($isLoggedIn) {
            $collegeId = $this->loginController->collegeIdFromSession();
        }
        else{
            $collegeId = 'guest';
        }

        $template = 'index';
        $argsArray = [
            'collegeId' => $collegeId,
            'role' => $role,
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function studentLecturerIndexAction()
    {
        $role = $this->loginController->roleFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();

        $message = '';
        $template = 'studentLecturer/studentLecturerIndex';
        $argsArray = [
            'collegeId' => $collegeId,
            'message' => $message,
            'role' => $role
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function loginAction()
    {
        $isLoggedIn = $this->loginController->isLoggedInFromSession();
        $role = $this->loginController->roleFromSession();

        if($isLoggedIn) {
            $collegeId = $this->loginController->collegeIdFromSession();
        }
        else{
            $collegeId = 'guest';
        }

        $message = '';
        $template = 'loginForm';
        $argsArray = [
            'collegeId' => $collegeId,
            'message' => $message,
            'role' => $role
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function registerAction()
    {
        $role = $this->loginController->roleFromSession();
        $isLoggedIn = $this->loginController->isLoggedInFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();

        $message = '';
        $template = 'admin/register';
        $argsArray = [
            'role' => $role,
            'collegeId' => $collegeId,
            'message' => $message
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function proposeTagAction()
    {
        $role = $this->loginController->roleFromSession();
        $isLoggedIn = $this->loginController->isLoggedInFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();

        $message = '';
        $template = 'proposeTag';

        $argsArray = [
            'role' => $role,
            'collegeId' => $collegeId,
            'message' => $message,
            'isLoggedIn' => $isLoggedIn
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function proposeRefAction()
    {
        $role = $this->loginController->roleFromSession();
        $isLoggedIn = $this->loginController->isLoggedInFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();
        $proposedTagRepository = new TagRepository();
        $tags = $proposedTagRepository->getTags();
        $message = '';
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

    public function viewPersonDetails($id)
    {
        if($id == 0){
            $message = 'user was not logged in when creating ref';
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
        $userRepository = new UserRepository();
        $user = $userRepository->getOneByCollegeId($id);
        $role = $this->loginController->roleFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();
        $template = 'personDetails';
        $argsArray = [
            'role' => $role,
            'user' => $user,
            'collegeId' => $collegeId,
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }
}