<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use Miniature\Calendar;
use Miniature\Database as DB;
use Miniature\Users as Login;

$login = new Login();

$username = (isset($_SESSION['id'])) ? $login->username($_SESSION['id']) : null;

if (!$username) {
    header("Location: game.php");
    exit();
} 

if ($username) {
    $status = $login->checkSecurity($_SESSION['id']);
}
//echo $username . ' ' . $status . "<br>";



/*
 * Calendar code
 */
$monthly = new Calendar();

$monthly->phpDate();
$calendar = $monthly->generateCalendar($basename);

$conn = DB::getInstance();
$pdo = $conn->getConnection();

function create($data, $pdo) {
    $query = 'INSERT INTO trivia_questions (user_id, hidden, question, answer1, answer2, answer3, answer4, correct, category, play_date) '
            . 'VALUES (:user_id, :hidden, :question, :answer1, :answer2, :answer3, :answer4, :correct, :category, NOW())';

    $stmt = $pdo->prepare($query);
    $result = $stmt->execute([
        ':user_id' => 1,
        ':hidden' => 'yes',
        ':question' => $data['question'],
        ':answer1' => $data['answer1'],
        ':answer2' => $data['answer2'],
        ':answer3' => $data['answer3'],
        ':answer4' => $data['answer4'],
        ':correct' => $data['correct'],
        ':category' => $data['category']
    ]);
    return $result;
}

$data = [];

$submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if (isset($submit) && $submit === 'submit') {
    $data['question'] = filter_input(INPUT_POST, 'question', FILTER_DEFAULT);
    $data['answer1'] = filter_input(INPUT_POST, 'answer1', FILTER_SANITIZE_SPECIAL_CHARS);
    $data['answer2'] = filter_input(INPUT_POST, 'answer2', FILTER_SANITIZE_SPECIAL_CHARS);
    $data['answer3'] = filter_input(INPUT_POST, 'answer3', FILTER_SANITIZE_SPECIAL_CHARS);
    $data['answer4'] = filter_input(INPUT_POST, 'answer4', FILTER_SANITIZE_SPECIAL_CHARS);
    $data['correct'] = filter_input(INPUT_POST, 'correct', FILTER_SANITIZE_SPECIAL_CHARS);
    $data['category'] = 'photography';
    $result = create($data, $pdo);
    if ($result) {
        header("Location: game.php");
        exit;
    }
}

include_once 'assets/includes/header.inc.php';
?>
<div class="content">
    <main class="main-area">
        <section class="">
            <form id="addTriviaQA" action="addQuiz.php" method="post">
                <fieldset>
                    <legend id="legend">Add Trivia Question(s)</legend>
                    <input id="id" type="hidden" name="id" value="0">
                    <input type="hidden" name="user_id" value="">
                    <textarea id="addQuestion" name="question" tabindex="2" placeholder="Add question here..." autofocus></textarea>
                    <label for="addAnswer1">Answer 1</label>
                    <input id="addAnswer1" type="text" name="answer1" value="" tabindex="3">
                    <label for="addAnswer2">Answer 2</label>
                    <input id="addAnswer2" type="text" name="answer2" value="" tabindex="4">
                    <label for="addAnswer3">Answer 3</label>
                    <input id="addAnswer3" type="text" name="answer3" value="" tabindex="5">
                    <label for="addAnswer4">Answer 4</label>
                    <input id="addAnswer4" type="text" name="answer4" value="" tabindex="6">   
                    <label for="addCorrect">Answer</label>
                    <input id="addCorrect" type="text" name="correct" value="" tabindex="7">
                    <input id="submitBtn" type="submit" name="submit" value="submit" tabindex="8">
                </fieldset>


            </form> 
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
    </div><!-- .sidebar -->
</div><!-- .content -->    

<?php
require_once 'assets/includes/footer.inc.php';
