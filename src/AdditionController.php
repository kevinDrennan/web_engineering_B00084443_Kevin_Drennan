<?php

/*
 * obtains $addNum from additionIndex page as constructor
 * additionLayout() calls createShuffleArray() which returns 1 to 12 shuffled.
 * LOOP begins
 * $randomNum no chosen from $numArray to be returned to additionPage
 * User enters $answer and presses submit, returns $answer
 * checks whether, $addNum + $randomNum = $answer
 * if correct, score = score + 1.
 * array begins again until end
 * LOOP ENDS
 */
namespace Itb;


class AdditionController
{
    private $score = 0;
    private $addNum;
    private $answer;

    function __construct(int $addNum)
    {
        $this->addNum = $addNum;
    }

    function additionLayout($numArray)
    {
        global $score;
        //              TEST
        var_dump("ScoreBeginningOfAddLayoutFunc = $score");

        if (!empty($answer)) {
            if($this->checkAnswerCorrect()==true)
            {
                $score = $score + 1;
                var_dump($score);
            }
        }
        $randomNum = $this->getRandomNumber($numArray);

        //          TEST
        var_dump("randomNum inAddLayout = $randomNum");

        $returnArray = array();
        $returnArray['randomNum'] = $randomNum;
        $returnArray['score'] = $score;
        return($returnArray);
    }

    function createAndShuffleArray()
    {
        $numArray = range(1,12);
        shuffle($numArray);
        return $numArray;
    }

    function getRandomNumber($numArray)
    {
        foreach($numArray as $randomNum)
        {
            return $randomNum;
        }
    }


    function getUserAnswer()
    {
        $answer = filter_input(INPUT_GET, 'answer', FILTER_SANITIZE_NUMBER_INT);
        $answer = (int)$answer;
        return $answer;
    }

    function checkAnswerCorrect()
    {
        $addNum = filter_input(INPUT_GET, 'addNum', FILTER_SANITIZE_NUMBER_INT);
        $randomNum = filter_input(INPUT_GET, 'randomNum', FILTER_SANITIZE_NUMBER_INT);
        global $addNum;
        $this->addNum = $addNum;
        $answer = $this->getUserAnswer();
        if($answer = $addNum + $randomNum)
        {
            return true;
        }
        else
        {
            return false;
        }

    }
}