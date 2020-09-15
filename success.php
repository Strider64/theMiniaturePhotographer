<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use Miniature\Calendar;

/*
 * Calendar code
 */
$monthly = new Calendar();

$monthly->phpDate();
$calendar = $monthly->generateCalendar($basename);

include_once 'assets/includes/header.inc.php';
?>
<div class="content">
    <main class="main-area">
        <h1>Thank You for Registering!</h1>
        <p>An email has been sent to you to activate your account, please check your spam or junk folder if you haven't received the activation code. </p>
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