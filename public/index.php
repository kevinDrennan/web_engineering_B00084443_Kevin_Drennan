<?php

require_once __DIR__ . "/../app/setup.php";

use Itb\MainController;

$mainController = new MainController();

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

switch ($action){

    //shop page
    case 'additionIndex':
        $html = $mainController->additionIndexAction($twig);
        break;

    //home page
   /* case 'subtractionIndex':
        $html = $mainController->subtractionIndexAction($twig);
        break;  */

    //contact us page
    case 'shop':
        $html = $mainController->shopAction($twig);
        break;

    //contact us page
    case 'clock':
        $html = $mainController->clockAction($twig);
        break;

    /*case 'additionTest':
        var_dump($action);
        $html = $mainController->additionTestAction($twig);
        break;*/

    case 'index':
    default:
        $html = $mainController->indexAction($twig);
}

print $html;
