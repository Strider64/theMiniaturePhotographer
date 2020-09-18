<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use Miniature\Calendar;
use Miniature\Users as Login;
use Miniature\ProcessImage as Process;
use Miniature\Resize;
use Miniature\Gallery;

$login = new Login();

/*
 * Calendar code
 */
$monthly = new Calendar();

$monthly->phpDate();
$calendar = $monthly->generateCalendar($basename);

$username = (isset($_SESSION['id'])) ? $login->username($_SESSION['id']) : null;

$gallery = new Gallery();

$upload = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if ($upload && $upload === 'upload') {
    $data['user_id'] = $_SESSION['id'];
    $data['author'] = $username;
    $data['category'] = htmlspecialchars($_POST['category']);
    if (isset($_FILES) && $_FILES['file']['error'] !== 4) {
        
        $file = $_FILES['file']; // Assign image data to $file array:
        $thumb_path = $_FILES['file'];

        $imgObject = new Process($file, 'gallery-photos-');

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
            $data['path'] = $imgObject->saveIMG();
            
            // *** 1)  Create a new instance of class Resize:
            $resizePic = new Resize($data['path']);
            // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
            $resizePic->resizeImage(1433, 956, 'exact');
            // *** 3) Save image to directory:
            $resizePic->saveImage($data['path'], 100);
        }

        $thumbObject = new Process($thumb_path, 'gallery-photos-', false);
        $check['image_status'] = $thumbObject->processImage();
        $check['file_type'] = $thumbObject->checkFileType();
        $check['file_ext'] = $thumbObject->checkFileExt();
        
        if (in_array(TRUE, $check)) {
            $errMsg = "There's something wrong with the image file!<b>";
        } else {
            $data['thumb_path'] = $thumbObject->saveIMG();
            
            copy($data['path'], $data['thumb_path']);
            
            // *** 1)  Create a new instance of class Resize:
            $resizePic = new Resize($data['thumb_path']);
            // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
            $resizePic->resizeImage(600, 400, 'exact');
            // *** 3) Save image to directory:
            $resizePic->saveImage($data['thumb_path'], 100);
            /*
             * Save all the data from the form to the database table: cms
             */
            $result = $gallery->create($data);
            if ($result) {
                header('Location: photo.php');
                exit();
            }
        }
    } // End of $_FILES If Statement:
}

$photos = $gallery->read();
//echo "<pre>" . print_r($photos, 1) . "</pre>";

include_once 'assets/includes/header.inc.php';
?>
<div class="content">
    <main class="main-area">
        <?php
        $index = 0;
        echo '<ul id="slides">';
        foreach ($photos as $photo) {
            if ($index === 0) {
                echo '<li class="slide showing"><img src="' . $photo->thumb_path . '" alt=""></li>';
            } else {
                echo '<li class="slide"><img src="' . $photo->thumb_path . '" alt=""></li>';
            }
            $index += 1;
        }
        echo '</ul>';
        ?>
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
                    <input type="hidden" name="action" value="upload">
                    <input class="uploadImage" type="file" name="file">

                    <select class="category" name="category">
                        <option value="wildlife">Wildlife</option>
                        <option value="lego">LEGO</option>
                        <option value="portraits-landscapes">Portraits / Landscapes</option>
                        <option value="other">Other</option>
                    </select>   
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

<?php
require_once 'assets/includes/footer.inc.php';
