<?php

require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use Miniature\Database as DB;
use Miniature\Users as Login;

$login = new Login();

$username = (isset($_SESSION['id'])) ? $login->username($_SESSION['id']) : null;

if (!$username) {
    header("Location: game.php");
    exit();
} 

$conn = DB::getInstance();
$pdo = $conn->getConnection();

function retrieveData($cat, $pdo) {

    $query = "SELECT * FROM trivia_questions WHERE category=:category ORDER BY id ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':category' => $cat]);

    $data = $stmt->fetchAll(\PDO::FETCH_OBJ);
    return $data;
}

/* Makes it so we don't have to decode the json coming from javascript */
header('Content-type: application/json');


$category = htmlspecialchars($_GET['category']);


$data = retrieveData($category, $pdo);
output($data);

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
