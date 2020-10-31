<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use Miniature\Calendar;
use Miniature\Users as Login;
use Miniature\Trivia;
$trivia = new Trivia();

$clearDate = $trivia->clearTable();

//PHP array containing forenames.
$names = [
    'Sir',
    'King',
    'Buzz',
    'Willy'
];

shuffle($names);

//PHP array containing surnames.
$surnames = [
    'Lancelot',
    'Arthur',
    'Lightyear',
    'Wanka'
];

shuffle($surnames);

//Generate a random forename.
$random_name = $names[mt_rand(0, sizeof($names) - 1)];

//Generate a random surname.
$random_surname = $surnames[mt_rand(0, sizeof($surnames) - 1)];

$rNum = random_int(100, 999);

$generatedName = $random_name . ' ' . $random_surname . $rNum;

$_SESSION['generatedName'] = $generatedName;
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
}

$displayMessage = "You are playing as " . $generatedName . "!<br>";

?>
<!DOCTYPE html>

<html>
    <head lang="en">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Photo trivia</title>
        <link rel="stylesheet" href="assets/css/game.css" type="text/css" media="all">
        <script src="https://kit.fontawesome.com/a8b40cbd7a.js" crossorigin="anonymous"></script>
        <script type="text/javascript"src="assets/js/game.js" defer></script>
    </head>
    <body>

    </body>
</html>
