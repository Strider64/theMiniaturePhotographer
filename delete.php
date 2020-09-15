<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use Miniature\CMS;

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

$cms = new CMS();

$id = htmlspecialchars($_GET['id'] ?? null);
if ($id) {
    /*
     * Delete the image and the record from the database table journal
     */

//    $image_path = $cms->readImagePath($id);
//    $thumb_path = $cms->readThumbPath($id);
//
//    unlink($image_path);
//    unlink($thumb_path);
    
    $result = $cms->delete($id);
    if ($result) {
        header("Location: index.php");
        exit();
    } 
} 


