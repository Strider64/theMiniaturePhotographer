<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use Miniature\Calendar;
use Miniature\Users as Login;
use Miniature\Trivia;

$trivia = new Trivia();

$clearDate = $trivia->clearTable();

/*
 * Calendar code
 */
$monthly = new Calendar();

$monthly->phpDate();
$calendar = $monthly->generateCalendar($basename);

$login = new Login();

$username = (isset($_SESSION['id'])) ? $login->username($_SESSION['id']) : null;

if ($username) {
    $status = $login->checkSecurity($_SESSION['id']);
    $displayMessage = "Welcome, " . $username . "!<br>";
}

include_once 'assets/includes/header.inc.php';
?>
<div id="topOfGame" class="content">
    <main class="main-area">
        <section class="main">
            <div class="displayStatus">
                <h4 class="displayMessage" data-username="<?= ($username ?? 'Guest') ?>"><?= ($displayMessage ?? 'You are playing as a Guest!'); ?></h4>
            </div>
            <div id="scoreboard" class="finalResults">
                <div id="totals">
                    <H2><span class="username"></span>'s Stats</H2>
                    <p>Total Score <span class="totalScore"></span> Points</p>
                    <p>Total Answered Right was <span class="answeredRight"></span> out of <span class="totalQuestions"></span> questions</p>
                </div>
            </div>
            <div id="quiz">


                <div class="triviaContainer" data-key="<?php echo $_SESSION['api_key']; ?>" data-records=" ">             
                    <div id="mainGame">
                        <div id="headerStyle" data-user="">
                            <h2>Time Left: <span id="clock"></span><span id="currentQuestion"></span><span id="totalQuestions"></span></h2>
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



        <article class="addTriviaInfo">
            <h2>Add a Photography Trivia Question</h2>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody class="anchor">

                </tbody>
            </table>

            <p>I have developed a photography trivia question game that lets people learn photography while having fun. I am sprucing up the game in order to bring even more fun to the game. The winner of after each day will be able to add a photography trivia question to the database table. The question and answers probably will not be posted right away in order for the question to be approved and/or edited. The only prize is getting top honors on a daily top high score listing on this website, plus the knowledge of being top for that day in knowing photography.</p>
        </article>

        <?php
        if (isset($status) && $status === 'sysop') {
            echo '<a class="qBtn" href="addQuiz.php" title="Add Photography Trivia Question">Add Question</a>';
            echo '<a class="qBtn" href="editQuiz.php" title="Edit Photography Trivia Question">Edit Question</a>';
            echo '<a class="btn3" href="logout.php?pageLoc=game.php">Log Off</a>';
        } elseif (isset ($status) && $status === 'member') {
            echo '<a class="btn3" href="logout.php?pageLoc=game.php">Log Off</a>';
        } else {
            echo '<div class="login">';
            echo '<h1>Login to Web App</h1>';
            echo '<form method="post" action="login.php?pageLoc=game.php">';
            echo '<input type="text" name="username" value="" placeholder="Username">';
            echo '<input type="password" name="password" value="" placeholder="Password">';
            echo'<input type="submit" name="submit" value="Login">';
            echo '</form>';
            echo '</div>';
            echo '<a class="btn1" href="register.php">register?</a>';
        } // End Security Status if
        ?>
    </div><!-- .sidebar -->
</div><!-- .content -->    

<?php
require_once 'assets/includes/footer.inc.php';
