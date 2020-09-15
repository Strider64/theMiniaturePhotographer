<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use Miniature\Users;
use Miniature\Calendar;

/*
 * Calendar code
 */
$monthly = new Calendar();

$monthly->phpDate();
$calendar = $monthly->generateCalendar($basename);

$trigger = false;

$login = new Users();
$confirmation = filter_input(INPUT_GET, 'confirmation', FILTER_SANITIZE_SPECIAL_CHARS);
$submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if (isset($submit) && $submit === 'enter') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);


    $result = $login->activate($username, $password, $status);
    if ($result) {
        $trigger = true;
    }
}

include_once 'assets/includes/header.inc.php';
?>


<div class="content">
    <main class="main-area">
        <?php if (!$trigger) { ?>
            <form id="activationForm" class="login" action="activate.php" method="post">
                <input type="hidden" name="status" value="<?= $confirmation ?>">
                <label class="textStyle" for="username">Username</label>
                <input id="username" type="text" name="username" value="" tabindex="1" autofocus="">
                <label class="textStyle" for="password">Password</label>
                <input id="password" type="password" name="password" tabindex="2">
                <input id="submit" type="submit" name="submit" value="enter" tabindex="3">
            </form>
        <?php } ?>
        <div class="textMessageBox">
            <?php if ($trigger) { ?>
                <h1>Thank You for Registering and Activating!</h1>
                <p>By activating your account with enable Top Score Boards, difficulty levels, playing options and many more features!</p>
                <a class="btn3" title="Home Page" href="index.php">Home</a>
            <?php } else { ?>
                <h1>Please Login to activate your account!</h1>
                <p>In order to fully enjoy The Miniature Photographer please login in to activate your account. Just a reminder that I will never sell you email address to any 3rd party.</p>
            <?php } ?>
        </div>
    </main>

    <div class="sidebar">
        <div class="squish-container">
            <h3>Social Media</h3>
            <nav class="social-media">
                <ul>
                    <li><a href="https://www.facebook.com/Pepster64/"><i class="fab fa-facebook-square"></i>Facebook</a></li>
                    <li><a href="https://twitter.com/Strider64"><i class="fab fa-twitter"></i>Twitter</a></li>
                    <li><a href="https://www.linkedin.com/in/johnpepp/"><i class="fab fa-linkedin-in"></i>LinkedIn</a></li>
                    <li><a href="https://www.flickr.com/photos/pepster/sets/72157704634851262/"><i class="fab fa-flickr"></i>Flickr</a></li>
                </ul>
            </nav>
        </div>
    </div><!-- .sidebar -->
</div><!-- .content -->    

<footer class="footer-area">
    <p>&copy;2020 The Miniature Photographer</p>
</footer>

</div><!-- .outer-wrap -->

<script type="text/javascript" src="assets/js/new-sidebar-switcher.js"></script>

</body>

</html>