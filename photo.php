<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use Miniature\Calendar;
use Miniature\Users as Login;


$login = new Login();

/*
 * Calendar code
 */
$monthly = new Calendar();

$monthly->phpDate();
$calendar = $monthly->generateCalendar($basename);



$username = (isset($_SESSION['id'])) ? $login->username($_SESSION['id']) : null;



include_once 'assets/includes/header.inc.php';
?>
<div class="content">
    <main class="main-area">

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
        <?php if ($username) { ?>  
            <form class="photoGallery" action="photo.php" method="post" enctype="multipart/form-data">
                <fieldset id="mainEntry">
                    <legend>Upload Image</legend>
                    <input class="uploadImage" type="file" name="file">
                    <input class="uploadBtn" type="submit" name="submit" value="upload">
                </fieldset>
            </form>
            <a class="btn3" href="logout.php?pageLoc=photo.php">Log Off</a>
        <?php } else { ?>
            <div class="login">
                <h1>Login to Web App</h1>
                <form method="post" action="login.php?pageLoc=photo.php">
                    <input type="text" name="username" value="" placeholder="Username">
                    <input type="password" name="password" value="" placeholder="Password">
                    <input type="submit" name="submit" value="Login">
                </form>                        
            </div>
            <a class="btn1" href="register.php">register?</a>

        <?php } ?>
    </div><!-- .sidebar -->
</div><!-- .content -->    

<footer class="footer-area">
    <p>&copy;2020 The Miniature Photographer</p>
</footer>

</div><!-- .outer-wrap -->

<script type="text/javascript" src="assets/js/new-sidebar-switcher.js"></script>

</body>

</html>
