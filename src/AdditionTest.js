
class AdditionTest {
    constructor(num) {
        this.num = num;
        console.log("in class num = " + num);

        this.getNum = function() {
            return num;
        }
    }
    }

    function shuffle(numArray) {
        var currentIndex = numArray.length, temporaryValue, randomIndex;
        while (0 !== currentIndex) {
            // Pick a remaining element...
            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex -= 1;
            // And swap it with the current element.
            temporaryValue = numArray[currentIndex];
            numArray[currentIndex] = numArray[randomIndex];
            numArray[randomIndex] = temporaryValue;
        }
        return numArray;
    }


        function validateAnswerAndNextQuestion(randNum, id) {
            checkAnswer(randNum, id);
            nextQuestion();
        }

        function checkAnswer(randNum, id) {
            var answer;
            switch (id) {
                case 1 :
                    answer = document.forms["Form1"]["answer1"].value;
                    break;
                case 2 :
                    answer = document.forms["Form2"]["answer2"].value;
                    break;
                case 3 :
                    answer = document.forms["Form3"]["answer3"].value;
                    break;
                case 4 :
                    answer = document.forms["Form4"]["answer4"].value;
                    break;
                case 5 :
                    answer = document.forms["Form5"]["answer5"].value;
                    break;
                case 6 :
                    answer = document.forms["Form6"]["answer6"].value;
                    break;
            }
            if (randNum == answer - 1) {
                score = score + 1;
                console.log("score = " + score);
                document.getElementById("tickDiv").style.display = "block";
                setTimeout(hideTick, 1000);
            }
            else {
                document.getElementById("incorrectAnswerDiv").style.display = "block";
                var incorrectMessage = document.getElementById("incorrectAnswerDiv");
                incorrectMessage.innerHTML =
                    "The Correct Answer is 1 + " + randNum + " = " + (1 + randNum);
                setTimeout(hideResult, 5000);
            }
        }

        function hideTick() {
            document.getElementById("tickDiv").style.display = "none";
        }

        function hideResult() {
            document.getElementById("incorrectAnswerDiv").style.display = "none";
        }

        function nextQuestion() {
            console.log("block i = " + block[i]);
            if (block[i] == "block6") {
                //document.write("<span style=\"font-size:x-large;text-align: center;padding-top: 50%\">" +
                //       "Well done your score is " + score + " out of 6</span>");
                document.getElementById("scoreDiv").style.display = "block";
                var scoreMessage = document.getElementById("scoreDiv");
                scoreMessage.innerHTML =
                    "Your Score Is " + score + " Out Of 6";
                //Returns to Home Page
            }
            else {
                document.getElementById(block[i]).style.display = "none";
                i++;
                document.getElementById(block[i]).style.display = "block";
                document.getElementById(block[i]).focus();
            }
        }


var score = 0;
var i = 0;                               //for next question function
var numArray = [1,2,3,4,5,6,7,8,9,10,11,12];
var randomArray = shuffle(numArray);
var block =
    ["block1", "block2", "block3",
        "block4", "block5", "block6"];

function runAdditionTest(num)
{
    console.log("num inRunAddTest =" + num);
    var addTest = new AdditionTest(num);
    console.log(addTest);
    document.write(num);

    function getNumb(num)
    {
        this.num = num;
        num = num +2;
        num = num -2;
        return num;
    }
}

function getNumbe()
{
    var nums = getNumb();
    nums = nums +2;
    nums = nums - 2;
    console.log(nums);
    return nums;

}

