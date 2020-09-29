<?php

require_once 'assets/config/config.php';
$todays_data = new \DateTime("now", new \DateTimeZone("America/Detroit"));

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

/* Makes it so we don't have to decode the json coming from javascript */
header('Content-type: application/json');

/*
 * The below must be used in order for the json to be decoded properly.
 */
$maxium = json_decode(file_get_contents('php://input'), true);



$day_of_year = $todays_data->format('z');

$query = 'SELECT * FROM hs_table ORDER BY score DESC LIMIT :maxium';
$stmt = $pdo->prepare($query);
$stmt->execute([':maxium' => (int) $maxium['max_limit']]);
$output = $stmt->fetchAll(PDO::FETCH_OBJ);
output($output);

/*
 * Throw error if something is wrong
 */

function errorOutput($output, $code = 500) {
    http_response_code($code);
    echo json_encode($output);
}

///*
// * If everything validates OK then send success message to Ajax / JavaScript
// */

/*
 * After converting data array to JSON send back to javascript using
 * this function.
 */
function output($output) {
    http_response_code(200);
    echo json_encode($output);
}
