<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";
// Use both for compatibility with all browsers
// and all versions of PHP.
session_unset();
session_destroy();
header('Location: index.php');
exit();
