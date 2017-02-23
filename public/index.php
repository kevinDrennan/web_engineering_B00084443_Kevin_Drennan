<?php

require_once __DIR__ . "/../app/setup.php";

use Itb\MainController;

$mainController = new MainController();

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

switch ($action){

    //shop page
    case 'additionIndex':
        $html = $mainController->additionIndex_action($twig);
        break;

    //home page
    case 'subtractionIndex':
        $html = $mainController->subtractionIndex_action($twig);
        break;

    //contact us page
    case 'shop':
        $html = $mainController->shop_action($twig);
        break;

    //contact us page
    case 'clock':
        $html = $mainController->clock_action($twig);
        break;

    case 'additionPage':
        $addNum = filter_input(INPUT_GET, 'addNum', FILTER_SANITIZE_STRING);
        var_dump($addNum);
        $html = $mainController->additionPage_action($twig);
        break;

    case 'index':
    default:
        $html = $mainController->index_action($twig);
}

print $html;
