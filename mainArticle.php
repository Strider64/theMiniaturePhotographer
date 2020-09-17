<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use Miniature\Calendar;
use Miniature\Users as Login;
use Miniature\CMS;
use Miniature\ProcessImage as Process;
use Miniature\Resize;
use Miniature\Linkify;

$monthly = new Calendar();

$monthly->phpDate();

$calendar = $monthly->generateCalendar($basename);


$linkify = new Linkify();

$cms = new CMS();

$id = (htmlspecialchars($_GET['page']) ?? NULL);

if ($id) {
    $entry = $cms->page($id);
    $content = $linkify->linkify($entry->content);
}

include_once 'assets/includes/header.inc.php';

?>


<div class="content">
    <main class="main-area">
        <section class="page">
            <?php
            echo '<article class="fullpage">';
            echo '<div class="page-image" href="#" data-id="' . $entry->id . '">';
            echo '<picture class="thumbnail">';
            echo '<img src="' . $entry->image . '" alt="' . $entry->heading . '">';
            echo '</picture>';
            echo '<div class="page-content">';
            echo '<h2>' . $entry->heading . '<span class="subheading">by ' . $entry->author . ' on ' . $entry->date_added . '</span></h2>';
            echo '<p>' . nl2br($content) . '</p>';
            echo '</div><!-- .page-content -->';
            echo '</div>';
            echo '</article><!-- .card -->';
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

<?php
require_once 'assets/includes/footer.inc.php';

