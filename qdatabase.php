<?php

require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use Miniature\Trivia;

$trivia = new Trivia();

$todays_day = new \DateTime("now", new \DateTimeZone("America/Detroit"));


$days = [];
$max = 10;
$total = $trivia::countAll();
//$day_of_week = 0;
$day_of_week = $todays_day->format('w');
$max_offset = floor($total / $max) * $max;
$count = 0;

for ($x = 0; $x <= 6; $x++) {

    if (($count * $max) < $max_offset) {
        $days[$x] = $count * $max;
        $count += 1;
    } else {
        if (($total % $max) == 0) {
            $days[$x] = $count * $max;
        } else {
            $days[$x] = $total - $max;
        }

        $count = 0;
    }
}

$questionOFFSET = $days[$day_of_week];

/*
 * Read Questions & Answers in from the Database Table Named 'trivia_questions'
 */


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
    $data = $trivia::readData($max, $questionOFFSET);
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
