<?php
$temp = ['about' => 'About Page', 'activate' => 'Activation Page', 'addQuiz' => 'Add Question', 'contact' => 'Contact Page', 'edit' => 'Edit Page', 'editQuiz' => 'Edit Quiz', 'game' => 'Photography Quiz', 'index' => 'The Miniature Photographer', 'mainArticle' => 'Full Document', 'register' => 'Registration', 'success' => 'Successful Registration', 'photo' => 'Photography'];

if (array_key_exists($pageName, $temp)) {
    $title = $temp[$pageName];
} else {
    $title = "Blank";
}
?>

<!DOCTYPE html>
<html lang="en-US">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="The Miniature Photography Website by John Pepp">
        <title><?= $title; ?></title>
        <link rel="shortcut icon" href="favicon.ico">

        <link rel="stylesheet" href="assets/css/stylesheet.css" type="text/css" media="all">

        <script src="https://kit.fontawesome.com/a8b40cbd7a.js" crossorigin="anonymous"></script>
        <?php if ($pageName === 'game') { ?>
            <script type="text/javascript"src="assets/js/game.js" defer></script>
        <?php } ?>
    </head>

    <body>
        <div id="pictureBox" class="shade">

            <div id="picture">
                <div class="play">
                    <button class="controls" id="pause">Play</button>
                </div>



                <img id="pictureELE" src="assets/images/img-1600483630.jpg" alt="Big Screen Picture">


                <div class="exifInfo">
                    <p id="exifData"></p>
                </div>  
                <div class="exitBtn">
                    <a id="exitBtn" class="btn" href="#">&#8592; Exit</a>
                </div>
            </div>
            <div class="prevSlide">
                <a id="preSlide" href="#">&#8592; Prev</a>
            </div>
            <div class="nextSlide">
                <a id="nextSlide" href="#">Next &#8594;</a>  
            </div>

        </div>
        <a class="skip-link screen-reader-text" href="#content">Skip to content</a>
        <div class="outer-wrap">

            <header id="gameMasthead" class="masthead">
                <?php require_once 'assets/includes/calendar.inc.php'; ?>
                <div class="sidebar-switcher">
                    Select layout: <a href="#" class="sidebar-left-toggle"><i class="fas fa-align-left"></i><span class="screen-reader-text">Move sidebar to the left</span></a> <a href="#" class="sidebar-right-toggle"><i class="fas fa-align-right"></i><span class="screen-reader-text">Move sidebar to the right</span></a><a href="#" class="hide-sidebar-toggle"><i class="fas fa-arrow-circle-right"></i><span class="screen-reader-text">Remove sidebar</span></a>
                </div><!-- .sidebar-switcher -->
                <?php
                $url = 'index.php';
                if ($basename === 'index.php') {
                    echo $pagination->pageLinks($url);
                }
                ?>
                <div class="centered">

                    <div class="site-branding">
                        <img class="logo" src="assets/images/img-header-002.png" alt="LOGO">

                    </div>
                </div><!-- .centered -->

            </header><!-- .masthead -->

        </div>

        <?php require_once 'assets/includes/navigation.inc.php'; ?>