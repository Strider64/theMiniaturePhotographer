<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use Miniature\Calendar;

/*
 * Calendar code
 */
$monthly = new Calendar();

$monthly->phpDate();
$calendar = $monthly->generateCalendar($basename);

include_once 'assets/includes/header.inc.php';
?>
<div class="content">
    <main class="main-area">
        <section class="main">

            <div id="quiz">

                <div id="gameTitle">
                    <h2 class="gameTitle">Trivia Game</h2>
                </div>
                <div class="triviaContainer" data-key="<?php echo $_SESSION['api_key']; ?>" data-records=" ">             
                    <div id="mainGame">
                        <div id="headerStyle" data-user="">
                            <h2>Time Left: <span id="clock"></span></h2>
                        </div>

                        <div id="triviaSection" data-correct="">
                            <div id="questionBox">
                                <h2 id="question">What is the Question?</h2>
                            </div>
                            <div id="buttonContainer"></div>
                        </div>

                        <div id="playerStats">
                            <h2 id="score">Score 0 Points</h2>
                            <h2 id="percent">100 percent</h2>
                        </div>
                        <div id="nextStyle">
                            <button id="next" class="nextBtn">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div class="sidebar">
        <div class="squish-container">
            <h3>Social Media</h3>
            <nav class="social-media">
                <ul>
                    <li><a href="https://www.facebook.com/Pepster64/"><i class="fab fa-facebook-square"></i>Facebook</a></li>
                    <li><a href="https://twitter.com/Strider64"><i class="fab fa-twitter"></i>Twitter</a></li>
                    <li><a href="https://www.linkedin.com/in/johnpepp/"><i class="fab fa-linkedin-in"></i>LinkedIn</a></li>
                    <li><a href="https://www.flickr.com/photos/pepster/sets/72157704634851262/"><i class="fab fa-flickr"></i>Flickr</a></li>
                </ul>
            </nav>
        </div>

        <table class="styled-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Points</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Dom</td>
                    <td>6000</td>
                </tr>
                <tr class="active-row">
                    <td>Melissa</td>
                    <td>5150</td>
                </tr>
                <tr>
                    <td>Judi</td>
                    <td>4000</td>
                </tr>
                <!-- and so on... -->
            </tbody>
        </table>

        <article class="addTriviaInfo">
            <h2>Add a Photography Trivia Question</h2>
            <p>I have developed a photography trivia question game that lets people learn photography while having fun. I am sprucing up the game in order to bring even more fun to the game. The winner of after each day will be able to add a photography trivia question to the database table. The question and answers probably will not be posted right away in order for the question to be approved and/or edited. The only prize is getting top honors on a daily top high score listing on this website, plus the knowledge of being top for that day in knowing photography.</p>
        </article>
        <a class="addQBtn" href="addQuiz.php" title="Add Photography Trivia Question">Add Question</a>
    </div><!-- .sidebar -->
</div><!-- .content -->    

<?php
require_once 'assets/includes/footer.inc.php';
