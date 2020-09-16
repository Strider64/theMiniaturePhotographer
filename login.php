<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use Miniature\Users as Login;

$pageLoc = htmlspecialchars($_GET['pageLoc'] ?? "index.php");

$login = new Login;

/*
 * Login code
 */
$submit = (htmlspecialchars($_POST['submit'] ?? null));

if ($submit === 'Login') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id = $login->read($username, $password);
    if ($id) {
        header('Location: ' . $pageLoc);
        exit();
    } else {
        header('Location: index.php');
        exit();
    }
    
}