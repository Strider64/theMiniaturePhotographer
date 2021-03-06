/*
 *  The Chalkboard Quiz 5.60 using FETCH/JSON
 *  by John Pepp
 *  Started: January 14, 2020
 *  Revised: September 30, 2020 @ 1:30pm
 */

'use strict';
(function () {
    /* Convert RGBa to HEX  */
    const rgba2hex = (orig) => {
        var a,
                rgb = orig.replace(/\s/g, '').match(/^rgba?\((\d+),(\d+),(\d+),?([^,\s)]+)?/i),
                alpha = (rgb && rgb[4] || "").trim(),
                hex = rgb ?
                (rgb[1] | 1 << 8).toString(16).slice(1) +
                (rgb[2] | 1 << 8).toString(16).slice(1) +
                (rgb[3] | 1 << 8).toString(16).slice(1) : orig;

        if (alpha !== "") {
            a = alpha;
        } else {
            a = "01";
        }
        // multiply before convert to HEX
        a = ((a * 255) | 1 << 8).toString(16).slice(1);
        hex = hex + a;

        return hex;
    };

    const myColor = (colorcode) => {
        var hexColor = rgba2hex(colorcode);
        return '#' + hexColor;
    };

    /*
     * Constants & Variables Initialization Section.
     */
    const myGreen = myColor("rgba(29, 100, 31, 0.70)"); /* Green with 70% transparency */
    const myRed = myColor("rgba(255, 51, 51, 0.70)"); /* Red with 70% transparency */
//const myRed = myColor("rgba(84, 0, 30, 0.70)"); /* Red with 70% transparency */

    const quizUrl = 'qdatabase.php?'; // PHP database script 
    const d = document; // Shorten docoment function::

    const photographyBtn = d.querySelector('#photography');

    const gameTitle = d.querySelector('.gameTitle');
    const buttonContainer = d.querySelector('#buttonContainer');
    const question = d.querySelector('#question');
    const next = d.querySelector('#next');
    const points = 100;
    const scoreText = d.querySelector('#score');
    const percent = d.querySelector('#percent');
    const dSec = 20; // Countdown Clock for questions:

    var gameIndex = 0,
            gameData = null, // Array of Objects (id, questions and answers):
            timer = null,
            score = 0,
            total = 0,
            answeredRight = 0,
            answeredWrong = 0,
            totalQuestions = 0,
            choose = d.querySelector('#selectCat'),
            failedLoad = false,
            username = d.querySelector('.displayMessage').getAttribute('data-username'),
            hs_table = {},
            saveRecord = null;



    var responseAns = {};

    const buttons = d.querySelectorAll(".answerButton");
    const mainGame = d.querySelector('#mainGame');
    next.style.display = "none";

    /*
     * Start and Stop Functions for Countdown Timer For Triva Game
     */
    const startTimer = (dSec) => {
        var seconds = dSec;
        const userAnswer = 5, correct = 1;
        const newClock = d.querySelector('#clock');

        const currentQuestion = d.querySelector('#currentQuestion');
        const totalQ = d.querySelector('#totalQuestions');
        currentQuestion.textContent = (gameIndex + 1) + " out of ";
        totalQ.textContent = totalQuestions + " questions";

        newClock.style['color'] = '#C63637';
        newClock.textContent = ((seconds < 10) ? `0${seconds}` : seconds);
        const countdown = () => {
            if (seconds === 0) {
                clearTimeout(timer);
                newClock.style['color'] = myRed;
                newClock.textContent = "00";

                scoringFcn(userAnswer, correct);
                highlightFCN(userAnswer, correct);
                calcPercent(answeredRight, total);
                disableListeners();
                if ((gameIndex + 1) === totalQuestions) {
                    next.textContent = 'results';
                }
                next.style.display = "block";
                next.addEventListener('click', removeQuiz, false);
            } else {
                newClock.textContent = ((seconds < 10) ? `0${seconds}` : seconds);
                seconds--;
            }
        };




        timer = setInterval(countdown, 1000);

    };

    const stopTimer = () => {
        clearInterval(timer);
    };

    /* Highlight correct or wrong answers */
    const highlightFCN = (userAnswer, correct) => {
        const highlights = d.querySelectorAll('.answerButton');
        highlights.forEach(answer => {

            /*
             * Highlight Answers Function
             * User answered correctly
             */
            if (userAnswer === correct && userAnswer === parseInt(answer.getAttribute('data-correct'))) {
                answer.style["background-color"] = myGreen;
            }

            /*
             * User ansered incorrectly
             */
            if (userAnswer !== correct && userAnswer === parseInt(answer.getAttribute('data-correct'))) {
                answer.style['background-color'] = myRed;
            }
            if (userAnswer !== correct && correct === parseInt(answer.getAttribute('data-correct'))) {
                answer.style['background-color'] = myGreen;
            }

            /*
             * User let timer run out
             */
            if (userAnswer === 5) {
                answer.style['background-color'] = myRed;
            }
        });
    };

    /* Disable Listeners, so users can't click on answer buttons */
    const disableListeners = () => {
        const myButtons = d.querySelectorAll('.answerButton');
        myButtons.forEach(answer => {
            answer.removeEventListener('click', clickHandler, false);
        });
    };

    /* Calculate Percent */
    const calcPercent = (correct, total) => {
        var average = (correct / total) * 100;
        percent.textContent = average.toFixed(0) + " percent";
    };

    /* Figure out Score */
    const scoringFcn = (userAnswer, correct) => {
        if (userAnswer === correct) {
            score += points;
            answeredRight++;
            scoreText.textContent = `Score ${score} Points`;
        } else {
            score = score - (points / 2);
            answeredWrong++;
            scoreText.textContent = `Score ${score} Points`;
        }
        total++;
    };

    /* Handle General Errors in Fetch */
    const handleErrors = function (response) {
        if (!response.ok) {
            throw (response.status + ' : ' + response.statusText);
        }
        return response.json();
    };

    /* Success function utilizing FETCH */
    const checkUISuccess = function (parsedData) {
        //console.log(parsedData);
        var correct = parseInt(parsedData.correct);
        var userAnswer = parseInt(d.querySelector('#headerStyle').getAttribute('data-user'));
        scoringFcn(userAnswer, correct);
        calcPercent(answeredRight, total);
        highlightFCN(userAnswer, correct);

        disableListeners();
        next.style.display = "block";
        next.addEventListener('click', removeQuiz, false);
    };

    /* If Database Table fails to load then hard code the correct answers */
    const checkUIError = function (error) {
        console.log("Database Table did not load", error);
        switch (gameData[gameIndex].id) {
            case 1:
                var correct = gameData[gameIndex].correct;
                break;
            case 55:
                var correct = gameData[gameIndex].correct;
                break;
            case 9:
                var correct = gameData[gameIndex].correct;
        }
        var userAnswer = parseInt(d.querySelector('#headerStyle').getAttribute('data-user'));
        scoringFcn(userAnswer, correct);
        calcPercent(answeredRight, total);
        highlightFCN(userAnswer, correct);

        disableListeners();
        next.addEventListener('click', removeQuiz, false);

    };

    /* create FETCH request for check answers */
    const checkRequest = function (url, succeed, fail) {
        fetch(url, {
            method: 'POST', // or 'PUT'
            body: JSON.stringify(responseAns)

        })
                .then((response) => handleErrors(response))
                .then((data) => succeed(data))
                .catch((error) => fail(error));
    };

    /* User has made selection */
    const clickHandler = (e) => {
        const userAnswer = parseInt(e.target.getAttribute('data-correct'));
        responseAns.id = parseInt(gameData[gameIndex].id); // { id: integer } 
        const checkUrl = "check.php";
        stopTimer();
        if ((gameIndex + 1) === totalQuestions) {
            next.textContent = 'results';
        }
        checkRequest(checkUrl, checkUISuccess, checkUIError);
        d.querySelector('#headerStyle').setAttribute('data-user', userAnswer);
    };

    /* Remove answers from Screen */
    const removeAnswers = () => {
        let element = d.querySelector('#buttonContainer');
        while (element.firstChild) {
            element.removeChild(element.firstChild);
        }
    };

    /* Clear High Score  & remove HS Table */
    const removeHighScores = () => {
        let element = d.querySelector('.anchor');
        while (element.firstChild) {
            element.removeChild(element.firstChild);
        }

    }

    /*
     * Create and Display High Score Table
     */
    const displayHSTable = (info) => {
        info.forEach((value, index) => {
            var anchor = d.querySelector('.anchor');
            var trElement = anchor.appendChild(d.createElement('tr'));
            if ((index + 1) % 2 == 0) {
                trElement.className = 'active-row';
            }
            var tdPlayer = trElement.appendChild(d.createElement('td'));
            var tdPoints = trElement.appendChild(d.createElement('td'));
            tdPlayer.appendChild(d.createTextNode(value.player));
            tdPoints.appendChild(d.createTextNode(value.score));
        });
    }

    /* Save User Data to hs_table */
    const saveHSTableSuccess = function (info) {

        if (info) {
            removeHighScores();
            createHSTable('retrieveHighScore.php', retrieveHSTableUISuccess, retrieveHSTableUIError);
        }

    };

    /* If Database Table fails to save data in mysql table */
    const saveHSTableUIError = function (error) {
        console.log("Database Table did not load", error);
    };


    /* create FETCH request */
    const saveHSTableRequest = (saveUrl, succeed, fail) => {
        fetch(saveUrl, {
            method: 'POST', // or 'PUT'
            body: JSON.stringify(hs_table)
        })
                .then((response) => handleErrors(response))
                .then((data) => succeed(data))
                .catch((error) => fail(error));
    };

    /* retrieve User Data from hs_table */
    const retrieveHSTableUISuccess = function (info) {
        displayHSTable(info);

    };

    /* If Database Table fails to save data in mysql table */
    const retrieveHSTableUIError = function (error) {
        console.log("Database Table did not load", error);
    };

    /* Create High Score Data using fetch */
    const createHSTable = (retrieveUrl, succeed, fail) => {
        var max = 5; // Maximum Records to Be Displayed
        var maxium = {};
        maxium.max_limit = max;

        fetch(retrieveUrl, {
            method: 'POST', // or 'PUT'
            body: JSON.stringify(maxium)
        })
                .then((response) => handleErrors(response))
                .then((data) => succeed(data))
                .catch((error) => fail(error));
    };

    createHSTable('retrieveHighScore.php', retrieveHSTableUISuccess, retrieveHSTableUIError);


    const scoreboard = () => {
        var totalScore = d.querySelector('.totalScore');

        const hideGame = d.querySelector('#quiz');
        hideGame.style.display = "none";
        d.querySelector('#scoreboard').style.display = "table";
        totalScore.textContent = score;
        d.querySelector('.username').textContent = username;
        d.querySelector('.answeredRight').textContent = answeredRight;
        d.querySelector('.totalQuestions').textContent = totalQuestions;
        hs_table.player = username;
        hs_table.score = score;
        hs_table.correct = answeredRight;
        hs_table.totalQuestions = totalQuestions;
        saveHSTableRequest('hs_table.php', saveHSTableSuccess, saveHSTableUIError);
        question.textContent = 'Game Over';
    }
    /* Remove Question & Answers */
    const removeQuiz = () => {
        removeAnswers(); // Call removeAnswers FCN:
        next.style.display = "none";
        next.removeEventListener('click', removeQuiz, false);
        gameIndex++;

        if (gameIndex < totalQuestions) {
            createQuiz(gameData[gameIndex]); // Recreate the Quiz Display:
        } else {

            scoreboard();
        }
    };

    /* Populate Question, Create Answer Buttons */
    const createQuiz = (gameData) => {

        startTimer(dSec);

        question.textContent = gameData.question;

        /*
         * Create Buttons then insert answers into buttons that were
         * create. 
         */
        gameData.answers.forEach((value, index) => {
            /*
             * Don't Show Answers that have a Blank Field
             */

            var gameButton = buttonContainer.appendChild(d.createElement('button'));
            gameButton.id = 'answer' + (index + 1);
            gameButton.className = 'answerButton';
            gameButton.setAttribute('data-correct', (index + 1));
            gameButton.addEventListener('click', clickHandler, false);
            if (value !== "") {
                gameButton.appendChild(d.createTextNode(value));
            } else {
                gameButton.appendChild(d.createTextNode(" "));
                gameButton.style.pointerEvents = "none";
            }
        });
    };

    /* Success function utilizing FETCH */
    const quizUISuccess = (parsedData) => {
        mainGame.style.display = 'block';
        gameData = parsedData;
        //gameData = parsedData.sort(() => Math.random() - .5); // randomize questions:     
        totalQuestions = parseInt(gameData.length);
        createQuiz(gameData[gameIndex]);

    };

    /* If Database Table fails to load then answer a few hard coded Q&A */
    const quizUIError = (error) => {
        console.log("Database Table did not load", error);
        console.log(failedLoad);
        gameData = [
            {
                id: 1,
                question: "What actor from the movie \"Dead Poets Society\" plays Dr. James Wilson on the TV show ",
                correct: 2,
                category: "movie",
                answers: ["Ethan Hawke", "Robert Sean Leonard", "James Waterston"]

            },
            {
                id: 55,
                question: "Who has won the most Oscars for Best Actress?",
                correct: 2,
                category: "movie",
                answers: ["Meryl Streep", "Katharine Hepburn", "Audrey Hepburn", "Jane Fonda"]
            },
            {
                id: 9,
                question: "Who played Jor-El in \"Superman (1978)\"?",
                correct: 4,
                category: "movie",
                answers: ["Glenn Ford", "Ned Beatty", "Christopher Reed", "Marlon Brando"]
            }
        ];

        /* Display HTML Game Display and create Quiz */
        mainGame.style.display = 'block';

        createQuiz(gameData[gameIndex]);
    };

    /* create FETCH request */
    const createRequest = (url, succeed, fail) => {
        fetch(url)
                .then((response) => handleErrors(response))
                .then((data) => succeed(data))
                .catch((error) => fail(error));
    };

    /*
     * Start Game by Category
     */
    const selectCat = function (category) {

        const requestUrl = `${quizUrl}category=${category}`;

        createRequest(requestUrl, quizUISuccess, quizUIError);

    };


    d.querySelector('.main').scrollIntoView();

    const startGame = (e) => {
        e.preventDefault();
        selectCat('photography');
        d.querySelector('#startBtn').style.display = 'none';
        d.querySelector('.displayStatus').style.display = 'none';
        d.querySelector('#quiz').style.display = 'block';
    };

    d.querySelector('#customBtn').addEventListener('click', startGame, false);
    d.querySelector('#gameNav').style.display = "none";
    d.querySelector('#gameMasthead').style.display = "none";
    d.querySelector('.main-area').style.padding = "0px";

})();