<?php

require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

//use Miniature\Trivia;
//
//$trivia = new Trivia();
//
//$trivia::updateYear();
//$data = $trivia::read();
//
//echo "<pre>" . print_r($data, 1) . "</pre>";

$total = 23;
$max = 10;
$max_offset = floor($total / $max) * $max;

echo $max_offset . "<br>";
echo $total % $max;
echo "<br>";
$new_offset = $total - ($max - ($total % $max));

echo $new_offset ."<br>";




