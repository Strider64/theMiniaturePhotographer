<?php

require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use Miniature\Trivia;

$trivia = new Trivia();

$trivia->resetPlaydate();
$data = $trivia::read();

echo "<pre>" . print_r($data, 1) . "</pre>";

