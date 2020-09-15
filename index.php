<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use Miniature\Calendar;
use Miniature\Users as Login;
use Miniature\CMS;
use Miniature\Pagination;
use Miniature\ProcessImage as Process;
use Miniature\Resize;
use Miniature\Linkify;

$linkify = new Linkify();
$login = new Login;
$journal = new CMS();

$index = 0;

/*
 * Pagination Code
 */
$current_page = htmlspecialchars($_GET['page'] ?? 1); // Current Page Location:
$per_page = 3; // Total articles per page
$total_count = $journal::countAll(); // Totoal articles in database table:

$pagination = new Pagination($current_page, $per_page, $total_count);

/*
 * Calendar code
 */
$monthly = new Calendar();

$monthly->phpDate();
$calendar = $monthly->generateCalendar($basename);

$cms = new CMS();

$username = (isset($_SESSION['id'])) ? $login->username($_SESSION['id']) : null;

if ($username) {
    $status = $login->checkSecurity($_SESSION['id']);
}

$insert = (htmlspecialchars($_POST['submit'] ?? null));
/*
 * New Comment Data Entry code
 */

if ($insert) {

    $data['user_id'] = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    $data['author'] = $username;
    $data['page'] = 'index.php';
    $data['heading'] = filter_input(INPUT_POST, 'heading', FILTER_DEFAULT);
    $data['content'] = filter_input(INPUT_POST, 'content', FILTER_DEFAULT);
    $data['post'] = 'on';

    if (isset($_FILES) && $_FILES['file']['error'] !== 4) {
        $file = $_FILES['file']; // Assign image data to $file array:

        $imgObject = new Process($file, 'photos-');

        $check['image_status'] = $imgObject->processImage();
        $check['file_type'] = $imgObject->checkFileType();
        $check['file_ext'] = $imgObject->checkFileExt();

        /*
         * Extract the EXIF data out of the image (if it has any)
         */
        $exif_data = exif_read_data($imgObject->saveIMG());
        if ($exif_data['Model']) {
            $data['Model'] = "Sony " . $exif_data['Model'];
            $data['ExposureTime'] = $exif_data['ExposureTime'] . "s";
            $data['Aperture'] = $exif_data['COMPUTED']['ApertureFNumber'];
            $data['ISO'] = "ISO " . $exif_data['ISOSpeedRatings'];
            $data['FocalLength'] = $exif_data['FocalLengthIn35mmFilm'] . "mm";
        }

        if (in_array(TRUE, $check)) {
            $errMsg = "There's something wrong with the image file!<b>";
        } else {
            $data['image'] = $imgObject->saveIMG();
            $imageResizeType = $imgObject->checkOrientation();
            // *** 1)  Create a new instance of class Resize:
            $resizePic = new Resize($data['image']);
            // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
            $resizePic->resizeImage(800, 533, $imageResizeType);
            // *** 3) Save image to directory:
            $resizePic->saveImage($data['image'], 100);
        }

        $result = $cms->create($data);
        if ($result) {
            header("Location: index.php");
            exit();
        }
    } // End of $_FILES If Statement:
} // End of if statement:

/*
 * Read in current page entries 
 */
$blog = $pagination->readPage();

$threeColumn = false;

if (count($blog) % 3 == 0) {
    $threeColumn = true;
}
include_once 'assets/includes/header.inc.php';
?>


<div class="content">
    <main class="main-area">

        <?php echo ($threeColumn) ? '<section class="cardsThreeColumn">' : '<section class="cards">'; ?>
        <?php
        foreach ($blog as $entry) {
            if ($threeColumn) {
                echo '<article class="cardThreeColumn">';
            } else {
                echo '<article class="card">';
            }
            echo '<div class="card-image" href="#" data-id="' . $entry->id . '">';
            echo '<picture class="thumbnail">';
            echo '<img src="' . $entry->image . '" alt="' . $entry->heading . '">';
            echo '</picture>';
            echo '<div class="card-content">';
            echo '<h2>' . $entry->heading . '<span class="subheading">by ' . $entry->author . ' on ' . $entry->date_added . '</span></h2>';
            $content = $linkify->linkify($entry->content);
            echo '<p>' . nl2br($cms->getIntro($content, 1500, $entry->id)) . '</p>';
            echo '</div><!-- .card-content -->';

            if (isset($_SESSION['id']) && ($_SESSION['id'] === $entry->user_id || $status === 'sysop')) {
                echo '<a class="btn2" href="edit.php?page=' . $entry->id . '">Edit</a>' . "\n";
                echo '<a class="btn2" href="delete.php?id=' . $entry->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');">Delete</a>';
            }

            echo '</div>';
            echo '</article><!-- .card -->';
        }
        ?>
        </section><!-- .cards -->

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

            <form class="cms-editor" action="index.php" method="post" enctype="multipart/form-data">
                <fieldset id="mainEntry">
                    <legend>Content Management System Form</legend>
                    <input type="hidden" name="user_id" value="<?= $_SESSION['id']; ?>">
                    <input type="hidden" name="action" value="upload">
                    <input class="image-upload" type="file" name="file">

                    <label class="heading" for="heading">Heading</label>
                    <input class="heading" type="text" name="heading" value="" tabindex="1" required autofocus>
                    <label class="text" for="content">Content</label>
                    <textarea id="content" name="content" tabindex="2"></textarea>
                    <input class="menuExit" type="submit" name="submit" value="enter">
                </fieldset>
            </form>

            <a class="btn3" href="logout.php">Log Off</a>
        <?php } else { ?>
            <div class="login">
                <h1>Login to Web App</h1>
                <form method="post" action="login.php">
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