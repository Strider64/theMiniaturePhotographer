<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use Miniature\Calendar;
//use Miniature\Users as Login;
use Miniature\CMS;
//use Miniature\ProcessImage as Process;
//use Miniature\Resize;
use Miniature\Linkify;

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

$monthly = new Calendar();

$monthly->phpDate();

$calendar = $monthly->generateCalendar($basename);


$linkify = new Linkify();

$cms = new CMS();

/*
 * If user is updating blog or home page.
 */
$change = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if (isset($change) && $change === 'change') {
    $data['id'] = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $data['heading'] = filter_input(INPUT_POST, 'heading', FILTER_DEFAULT);
    $data['content'] = filter_input(INPUT_POST, 'content', FILTER_DEFAULT);
    $data['post'] = filter_input(INPUT_POST, 'activePost', FILTER_DEFAULT);

    $result = $cms->update($data);
    if ($result) {
        header("Location: index.php");
        exit();
    }
}

$page = (htmlspecialchars($_GET['page']) ?? false);

if ($page) {
    $entry = $cms->page($page);
    //$content = $linkify->linkify($entry->content);
} else {
    header("Location: index.php");
    exit();
}

include_once 'assets/includes/header.inc.php';

?>


<div class="content">
    <main class="main-area">
        <section class="page">
            <?php
            echo '<form class="editForm" action="edit.php" method="post" enctype="multipart/form-data">' . "\n";
            echo '<fieldset>' . "\n";
            echo '<legend>Edit Article</legend>' . "\n";
            echo '<input type="hidden" name="id" value="' . $entry->id . '">' . "\n";
            echo '<label class="heading" for="headingInput">Heading</label>' . "\n";
            echo '<input class="headingInput" type="text" name="heading" value="' . $entry->heading . '" tabindex="1" required autofocus>' . "\n";
            echo '<label class="text" for="content">Content</label>' . "\n";
            echo '<textarea id="content" name="content" tabindex="2">' . $entry->content . '</textarea>' . "\n";
            echo '<input class="menuExit" type="submit" name="submit" value="change">' . "\n";
            echo '</fieldset>' . "\n";
            echo '</form>' . "\n";
            ?>
        </section>

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

