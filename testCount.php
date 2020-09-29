<?php

require_once 'assets/config/config.php';

/*
 * Database Connection 
 */
$db_options = [
    /* important! use actual prepared statements (default: emulate prepared statements) */
    PDO::ATTR_EMULATE_PREPARES => false
    /* throw exceptions on errors (default: stay silent) */
    , PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    /* fetch associative arrays (default: mixed arrays)    */
    , PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];
$pdo = new PDO('mysql:host=' . DATABASE_HOST . ';dbname=' . DATABASE_NAME . ';charset=utf8', DATABASE_USERNAME, DATABASE_PASSWORD, $db_options);

function countAll($pdo) {
    $stmt = $pdo->prepare("SELECT count(*) FROM trivia_questions WHERE hidden = :hidden");
    $stmt->execute([':hidden' => 'no']);
    $count = $stmt->fetchColumn();
    return $count;
}

$todays_day = new \DateTime("now", new \DateTimeZone("America/Detroit"));


$days = [];
$max = 10;
$total = countAll($pdo);

$day_of_week = $todays_day->format('w');
$max_offset = floor($total/$max) * $max;
$count = 0;

for ($x=0; $x <= 6; $x++) {
    $days[$x] = $count * $max;
    if (($count * $max) < $max_offset) {
        $count += 1;
    } else {
        $count = 0;
    }
}

$questionOFFSET = $days[$day_of_week];

echo $questionOFFSET . "<br>";