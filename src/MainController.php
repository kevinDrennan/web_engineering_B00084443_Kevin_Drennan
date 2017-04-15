<?php

namespace Itb;

use Itb\tag\TagRepository;

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
        $isLoggedIn = $this->loginController->isLoggedInFromSession();
        if($isLoggedIn) {
            $collegeId = $this->loginController->collegeIdFromSession();
        }
        else{
            $collegeId = 'guest';
        }

        $template = 'index';
        $argsArray = [
            'collegeId' => $collegeId
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function loginAction()
    {
        $isLoggedIn = $this->loginController->isLoggedInFromSession();

        if($isLoggedIn) {
            $collegeId = $this->loginController->collegeIdFromSession();
        }
        else{
            $collegeId = 'guest';
        }

        $template = 'loginForm';
        $argsArray = [
            'collegeId' => $collegeId
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function registerAction()
    {
        $isLoggedIn = $this->loginController->isLoggedInFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();

        $message = '';
        $template = 'admin/register';
        $argsArray = [
            'collegeId' => $collegeId,
            'message' => $message
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function proposeTagAction()
    {
        $isLoggedIn = $this->loginController->isLoggedInFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();

        $message = '';
        $template = 'proposeTag';

        $argsArray = [
            'collegeId' => $collegeId,
            'message' => $message,
            'isLoggedIn' => $isLoggedIn
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function proposeRefAction()
    {
        $isLoggedIn = $this->loginController->isLoggedInFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();
        $proposedTagRepository = new TagRepository();
        $tags = $proposedTagRepository->getTags();
        $message = '';
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