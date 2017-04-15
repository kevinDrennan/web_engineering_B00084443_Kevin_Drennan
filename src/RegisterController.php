<?php


namespace Itb;


class RegisterController
{
    public $errors = array();
    private $app;

    public function __construct(WebApplication $app)
    {
        $this->app = $app;
        $this->loginController = new LoginController($app);
    }

    public function registerUserAction()
    {
        $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
        $surname = filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_STRING);
        $collegeId = filter_input(INPUT_POST, 'collegeId', FILTER_SANITIZE_STRING);
        $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $password_repeat = filter_input(INPUT_POST, 'password_repeat', FILTER_SANITIZE_STRING);
        $message = '';

        $userCollegeId = $this->loginController->collegeIdFromSession();

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if($password != $password_repeat){
            $message = 'The two passwords must be the same';
            $template = 'register';
            $username = 'Guest';
            $argsArray = [
                'message' => $message,
                'collegeId' => $userCollegeId
            ];
            return $this->app['twig']->render($template . '.html.twig', $argsArray);
        }

        User::createUser($firstName, $surname, $collegeId, $role, $email, $hashedPassword);
        $template = 'admin/adminIndex';
        $argsArray = [
            'message' => $message,
            'collegeId' => $userCollegeId
        ];

        return $this->app['twig']->render($template . '.html.twig', $argsArray);
    }
}