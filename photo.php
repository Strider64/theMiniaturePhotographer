<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use Miniature\Calendar;
use Miniature\Users as Login;
use Miniature\Gallery;

ini_set('memory_limit', '256M');
$login = new Login();

/*
 * Calendar code
 */
$monthly = new Calendar();

$monthly->phpDate();
$calendar = $monthly->generateCalendar($basename);

$username = (isset($_SESSION['id'])) ? $login->username($_SESSION['id']) : null;

define('IMAGE_WIDTH', 2048);
define('IMAGE_HEIGHT', 1365);

$gallery = new Gallery();

$upload = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if ($upload && $upload === 'upload') {
    $data['user_id'] = $_SESSION['id'];
    $data['author'] = $username;
    $data['category'] = htmlspecialchars($_POST['category']);
    if (is_array($_FILES)) {

        $large = $_FILES['file']['tmp_name'];
        $thumb = $_FILES['file']['tmp_name'];

        /*
         * Extract the EXIF data out of the image (if it has any)
         */
        $exif_data = exif_read_data($large);
        if ($exif_data['Model']) {
            $data['Model'] = "Sony " . $exif_data['Model'];
            $data['ExposureTime'] = $exif_data['ExposureTime'] . "s";
            $data['Aperture'] = $exif_data['COMPUTED']['ApertureFNumber'];
            $data['ISO'] = "ISO " . $exif_data['ISOSpeedRatings'];
            $data['FocalLength'] = $exif_data['FocalLengthIn35mmFilm'] . "mm";
        }

        function imageResize($imageSrc, $imageWidth, $imageHeight, $newImageWidth = IMAGE_WIDTH, $newImageHeight = IMAGE_HEIGHT) {
            $newImageLayer = imagecreatetruecolor($newImageWidth, $newImageHeight);
            imagecopyresampled($newImageLayer, $imageSrc, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $imageWidth, $imageHeight);

            return $newImageLayer;
        }

        function myFunction($uploadedFile, $dirPath = "assets/large/", $preEXT = 'img-', $newImageWidth = IMAGE_WIDTH, $newImageHeight = IMAGE_HEIGHT) {
            $sourceProperties = getimagesize($uploadedFile);
            $newFileName = time();

            global $data;

            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $imageType = $sourceProperties[2];
            //echo '$uploadedFile' . $uploadedFile . ' $dirPath ' . $dirPath . "<br>";
            if ($dirPath == "assets/large/") {
                $data['path'] = $dirPath . $preEXT . $newFileName . '.' . $ext;
            } else {
                $data['thumb_path'] = $dirPath . $preEXT . $newFileName . '.' . $ext;
            }

            switch ($imageType) {


                case IMAGETYPE_PNG:
                    $imageSrc = imagecreatefrompng($uploadedFile);
                    $tmp = imageResize($imageSrc, $sourceProperties[0], $sourceProperties[1], $newImageWidth, $newImageHeight);
                    imagepng($tmp, $dirPath . $preEXT . $newFileName . '.' . $ext);
                    break;

                case IMAGETYPE_JPEG:
                    $imageSrc = imagecreatefromjpeg($uploadedFile);

                    $tmp = imageResize($imageSrc, $sourceProperties[0], $sourceProperties[1], $newImageWidth, $newImageHeight);

                    imagejpeg($tmp, $dirPath . $preEXT . $newFileName . '.' . $ext);
                    break;

                case IMAGETYPE_GIF:
                    $imageSrc = imagecreatefromgif($uploadedFile);
                    $tmp = imageResize($imageSrc, $sourceProperties[0], $sourceProperties[1], $newImageWidth, $newImageHeight);
                    imagegif($tmp, $dirPath . $preEXT . $newFileName . '.' . $ext);
                    break;

                default:
                    echo "Invalid Image type.";
                    exit;
                    break;
            }

            return true;
        }

        $result = myFunction($large);
        if ($result) {
            $saveStatus = myFunction($thumb, 'assets/thumbnails/', 'thumb-', 600, 400);
            if ($saveStatus) {
                //echo "<pre>" . print_r($data, 1) . "</pre>";
                $gallery->create($data);
            }
        }
    } // END OF $_FILES
} // Submit



$photos = $gallery->read();
//echo "<pre>" . print_r($photos, 1) . "</pre>";

include_once 'assets/includes/header.inc.php';
?>
<div class="content">
    <main class="main-area">
        <div id="gallery" class="frame" data-total="<?php echo count($photos); ?>">
            <?php
            $x = 1;
            foreach ($photos as $photo) {
                $cameraInfo = (($photo->Model) ? $photo->Model . ' --- ' . $photo->FocalLength . ' ' . $photo->Aperture . ' ' . $photo->ISO . ' ' . $photo->ExposureTime : null);
                echo '<a class="image' . $x . '" href="' . $photo->path . '" title="' . $cameraInfo . '"><img class="box" src="' . $photo->thumb_path . '" alt="' . $photo->category . '">' . '</a>' . "\n";
                $x += 1;
            }
            ?>
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
//        $index = 0;
//        echo '<ul id="slides">';
//        foreach ($photos as $photo) {
//            if ($index === 0) {
//                echo '<li class="slide showing"><img src="' . $photo->thumb_path . '" alt=""></li>';
//            } else {
//                echo '<li class="slide"><img src="' . $photo->thumb_path . '" alt=""></li>';
//            }
//            $index += 1;
//        }
//        echo '</ul>';