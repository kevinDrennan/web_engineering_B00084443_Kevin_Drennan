<?php

namespace Itb;

use Itb;


class LoginController
{
    private $app;

    public function __construct(WebApplication $app)
    {
        $this->app = $app;
    }

    public function logoutAction()
    {
        unset($_SESSION['collegeId']);

        $isLoggedIn = $this->isLoggedInFromSession();

        if($isLoggedIn) {
            $collegeId = $this->collegeIdFromSession();
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

    public function processLoginAction()
    {
        $isLoggedIn = false;
        $message = '';
        $collegeId = filter_input(INPUT_POST, 'collegeId', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        // search for user with collegeId in repository
        $isLoggedIn = User::canFindMatchingCollegeIdAndPassword($collegeId, $password);
        $user = UserRepository::getOneByCollegeId($collegeId);
        if(isset($user)){
            $role = $user->role;
        }

        if ($isLoggedIn == false) {
            $message = 'bad username or password, please try again';
            $template = 'loginForm';
            $collegeId = 'guest';
            $argsArray = [
                'collegeId' => $collegeId,
                'message' => $message
            ];
            return $this->app['twig']->render($template . '.html.twig', $argsArray);
        }
        $_SESSION['role'] = $role;
        $_SESSION['collegeId'] = $collegeId;

        if ($role == 3 && $isLoggedIn == true) {
            $template = 'admin/adminIndex';
            $argsArray = [
                'collegeId' => $collegeId,
                'message' => $message
            ];
            return $this->app['twig']->render($template . '.html.twig', $argsArray);
        }

        $template = 'studentLecturer/studentLecturerIndex';
        $argsArray = [
            'collegeId' => $collegeId,
            'role' => $role
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function isLoggedInFromSession()
    {
        $isLoggedIn = false;

        if (isset($_SESSION['collegeId'])) {
            $isLoggedIn = true;
        }

        return $isLoggedIn;
    }

    public function collegeIdFromSession()
    {
        $collegeId = 'Guest';

        if (isset($_SESSION['collegeId'])) {
            $collegeId = $_SESSION['collegeId'];
        }

        return $collegeId;
    }

    public function roleFromSession()
    {
        $role = 0;

        if (isset($_SESSION['role']))  {
            $role = $_SESSION['role'];
        }
        return $role;
    }


}