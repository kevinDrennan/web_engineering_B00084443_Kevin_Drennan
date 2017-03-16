<?php

/*
 *
 *
 * answer not getting through from additionPage
 *
 *
 *
 */

namespace Itb;

require_once __DIR__ . "/../app/setup.php";

class MainController
{
    public function index_action(\Twig_Environment $twig)
    {
        $template = 'index';
        $argsArray = [];
        return $twig->render($template . '.html.twig', $argsArray);
    }

    public function additionIndex_action(\Twig_Environment $twig)
    {
        $template = 'additionIndex';
        $numArray = range(1,12);
        $argsArray = ['numArray' => $numArray];
        return $twig->render($template . '.html.twig', $argsArray);
    }

    public function subtraction_action(\Twig_Environment$twig)
    {
        $template = 'subtraction';
        $argsArray = [];
        return $twig->render($template . '.html.twig', $argsArray);
    }

    public function shop_action(\Twig_Environment $twig)
    {
        $template = 'shop';
        $argsArray = [];
        return $twig->render($template . '.html.twig', $argsArray);
    }

    public function clock_action(\Twig_Environment $twig)
    {
        $template = 'clock';
        $argsArray = [];
        return $twig->render($template . '.html.twig', $argsArray);
    }

    /*public function additionPage_action(\Twig_Environment $twig)
    {
        $template = 'additionPage';
        // below also in addition controller function
        //$answer = filter_input(INPUT_GET, 'answer', FILTER_SANITIZE_STRING);
        $addNum = filter_input(INPUT_GET, 'addNum', FILTER_SANITIZE_STRING);

        $numArray = array();
        $additionController = new \Itb\AdditionController($addNum);
        //$answer = (int)$answer;
        if (empty($answer)) {
            //$additionController = new \Itb\AdditionController($addNum);
            $numArray = $additionController->createAndShuffleArray();
            var_dump('testAnswerIsEmpty');
        }
        /*elseif(!empty($answer)){
            var_dump('TestAnswer!Empty');
        }

        $arrayRandNumScore = $additionController->additionLayout($numArray);
        //var_dump( "score&ranNumInMCAddPageAction =" . $arrayRandNumScore);

        $argsArray = [

            'addNum' => $addNum,
            'arrayRandNumScore' => $arrayRandNumScore
        ];
        return $twig->render($template . '.html.twig', $argsArray);
    }*/



    public function nextAddQuestion(\Twig_Environment $twig, array $numArray, int $addNum)
    {

        $this->$addNum=$addNum;
        $template = 'additionPage';

        $argsArray = [
            'addNum' => $addNum,
        ];
        return $twig->render($template . '.html.twig', $argsArray);
    }
}