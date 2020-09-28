<?php
require_once 'assets/config/config.php';
$todays_data = new \DateTime("now", new \DateTimeZone("America/Detroit"));

$day_of_week = $todays_data->format('N');

$max_questions = 2;

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

/*
 * Read Questions & Answers in from the Database Table Named 'trivia_questions'
 */

function readData($pdo, $day_of_week, $max_questions) {

    $query = "SELECT * FROM trivia_questions WHERE hidden=:hidden AND day_of_week=:day_of_week LIMIT :max_questions";
    $stmt = $pdo->prepare($query);


    $stmt->execute([':hidden' => 'no', ':day_of_week' => $day_of_week, ':max_questions' => $max_questions]);

    $result = $stmt->fetchALL(PDO::FETCH_ASSOC);

    return $result;
}

/* Makes it so we don't have to decode the json coming from Javascript */
header('Content-type: application/json');

/*
 * Get Category from the FETCH statment from javascript
 */
$category = htmlspecialchars($_GET['category']);


if (isset($category)) { // Get rid of $api_key if not using:
        
    /*
     * Call the readData Function
     */
    $data = readData($pdo, $day_of_week, $max_questions);

    $mData = []; // Temporary Array Placeholder:
    $answers = []; // Answer Columns from Table Array:
    $finished = []; // Finished Results:
    $index = 0; // Index for answers array:
    $indexArray = 0; // Index for database table array:

    /*
     * Put database table in proper array format in order that
     * JSON will work properly.
     */
    foreach ($data as $qdata) {

        foreach ($qdata as $key => $value) {

            switch ($key) {

                case 'answer1':
                    $answers['answers'][$index] = $value;
                    break;
                case 'answer2':
                    $answers['answers'][$index + 1] = $value;
                    break;
                case 'answer3':
                    $answers['answers'][$index + 2] = $value;
                    break;
                case 'answer4':
                    $answers['answers'][$index + 3] = $value;
                    break;
            }
        } // foreach inner

        /*
         * No Longer needed, but it wouldn't hurt if not unset
         */
        unset($qdata['answer1']);
        unset($qdata['answer2']);
        unset($qdata['answer3']);
        unset($qdata['answer4']);

        $finished = array_merge($qdata, $answers);
        $mData[$indexArray] = $finished;
        $indexArray++;
    }

    output($mData); // Send properly formatted array back to javascript:
}

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
