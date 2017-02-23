<?php

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

    public function additionPage_action(\Twig_Environment $twig)
    {
        $template = 'additionPage';
        //$addNum = $_GET['addNum'];
        //var_dump($addNum);
        $argsArray = [];
        //$argsArray = ['addNum' => $addNum];
        return $twig->render($template . '.html.twig', $argsArray);
    }
}