<?php

namespace Itb;

class AdminController
{
    private $app;
    private $loginController;


    public function __construct(WebApplication $app)
    {
        $this->app = $app;
        $this->loginController = new LoginController($app);
    }

    public function adminViewAction()
    {
        $userrole = $this->loginController->roleFromSession();
        $userCollegeId = $this->loginController->collegeIdFromSession();
        $collegeId = filter_input(INPUT_POST, 'collegeId', FILTER_SANITIZE_STRING);
        $user = UserRepository::getOneByCollegeId($collegeId);

        /*
         * Error if Collegeid Empty or no match in database
         */

        if($collegeId == null){
            $template = 'admin/adminIndex';
            $message = 'Please enter college ID';
            $argsArray = [
                'role' => $userrole,
                'user' => $user,
                'collegeId' => $userCollegeId,
                'message' => $message
            ];
            return $this->app['twig']->render($template . '.html.twig', $argsArray);
        }

        elseif($user == null){
            $template = 'admin/adminIndex';
            $message = 'No match for the college ID entered';
            $argsArray = [
                'role' => $userrole,
                'user' => $user,
                'collegeId' => $userCollegeId,
                'message' => $message
            ];
            return $this->app['twig']->render($template . '.html.twig', $argsArray);
        }

        /*
         * set role to description rather than numeric from database
         */
        $role = $user->role;
        $role = $this->getRole($role);
        $user->role = $role;

        $template = 'admin/adminView';
        $argsArray = [
            'role' => $userrole,
            'user' => $user,
            'collegeId' => $userCollegeId,
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function getRole($role)
    {
        if($role == 1){
            $role = 'student';
        }
        elseif($role == 2){
            $role = 'lecturer';
        }
        elseif($role == 3){
        $role = 'admin';
    }
        return $role;
    }

    public function returnToAdminHome()
    {
            $collegeId = $this->loginController->collegeIdFromSession();
            $template = 'admin/adminIndex';
            $message = '';
            $argsArray = [
                'collegeId' => $collegeId,
                'message' => $message
            ];
            return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function deleteUser($id)
    {
        $deleteSuccess=User::delete($id);

        if($deleteSuccess=true) {
            $collegeId = $this->loginController->collegeIdFromSession();
            $template = 'admin/adminIndex';
            $message = 'this person has been deleted';
            $argsArray = [
                'collegeId' => $collegeId,
                'message' => $message
            ];
            return $this->app['twig']->render($template . '.html.twig', $argsArray);
        }

        else{
            error();
        }
    }

    public function registerAction()
    {
        $userrole = $this->loginController->roleFromSession();
        $isLoggedIn = $this->loginController->isLoggedInFromSession();
        $collegeId = $this->loginController->collegeIdFromSession();

        $message = '';
        $template = 'admin/register';
        $argsArray = [
            'role' =>$userrole,
            'collegeId' => $collegeId,
            'message' => $message
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function updateUserAction($id)
    {
        $userrole = $this->loginController->roleFromSession();
        $userCollegeId = $this->loginController->collegeIdFromSession();
        //$collegeId = filter_input(INPUT_POST, 'collegeId', FILTER_SANITIZE_STRING);
        $user = User::getOneById($id);
        /*
         * set role to description rather than numeric from database
         */

        $template = 'admin/adminUpdateUser';
        $argsArray = [
            'role' =>$userrole,
            'user' => $user,
            'collegeId' => $userCollegeId
        ];
        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }

    public function updateUser($id)
    {
        $userrole = $this->loginController->roleFromSession();
        $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
        $surname = filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_STRING);
        $collegeId = filter_input(INPUT_POST, 'collegeId', FILTER_SANITIZE_STRING);
        $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        $user = User::getOneById($id);
        $user->firstName = $firstName;
        $user->surname = $surname;
        $user->collegeId = $collegeId;
        $user->role = $role;
        $user->email = $email;

        $updateSuccess = User::update($user);
        $userCollegeId = $this->loginController->collegeIdFromSession();

        if($updateSuccess == true){
            $message = 'This record has been updated';
            $template = 'admin/adminIndex';
            $argsArray = [
                'role' =>$userrole,
                'message' => $message,
                'user' => $user,
                'collegeId' => $userCollegeId
            ];
            return $this->app['twig']->render($template . '.html.twig', $argsArray);
        }
        else{
            error();
        }

    }
}