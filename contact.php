<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use Miniature\Calendar;
use Miniature\Database as DB;
use Miniature\sendMail;

/*
 * Calendar code
 */
$monthly = new Calendar();

$monthly->phpDate();
$calendar = $monthly->generateCalendar($basename);

$username = \NULL;
$success = "Contact Form";
$token = $_SESSION['token'];
$db = DB::getInstance();
$pdo = $db->getConnection();

/*
 * Fallback if user disables Javascript
 */
$submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if (isset($submit) && $submit === 'submit') {
    $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (!empty($token)) {
        if (hash_equals($_SESSION['token'], $token)) {
            /* The Following to get response back from Google recaptcha */
            $url = "https://www.google.com/recaptcha/api/siteverify";

            $remoteServer = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_URL);
            $response = file_get_contents($url . "?secret=" . PRIVATE_KEY . "&response=" . \htmlspecialchars($_POST['g-recaptcha-response']) . "&remoteip=" . $remoteServer);
            $recaptcha_data = json_decode($response);
            /* The actual check of the recaptcha */
            if (isset($recaptcha_data->success) && $recaptcha_data->success === TRUE) {
                $send = new sendMail();
                $data['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $data['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $data['phone'] = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $data['website'] = filter_input(INPUT_POST, 'website', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $data['reason'] = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $data['comments'] = filter_input(INPUT_POST, 'comments', FILTER_DEFAULT);
                $send->sendFrom([$data['email'] => $data['name']]);
                $send->sendTo(['jrpepp@pepster.com', 'pepster@pepster.com' => 'John Pepp']);
                $content = $data['phone'] . "\n" . $data['website'] . "\n" . $data['reason'] . "\n" . $data['comments'];
                $send->content($content);
                $send->subject('A email from The Miniature Photographer');
                $result = $send->sendEmail();
                if ($result) {
                    
                }
            } else {
                $success = "You're not a human!"; // Not on a production server:
            }
        } else {
            // Log this as a warning and keep an eye on these attempts
        }
    }
}
$server_name = filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL);


include_once 'assets/includes/header.inc.php';
?>
<div class="content">
    <main class="main-area">
        <form id="contact" name="contact" action="contact.php" method="post"  autocomplete="on">
            <div id="message">
                <h2 id="notice">Form Notification</h2>
                <a  id="messageSuccess" href="index.php" title="Home Page">Home</a>
            </div>
            <fieldset>
                <legend>Contact Form</legend>
                <input id="token" type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                <label class="labelstyle" for="name" accesskey="U">Name</label>
                <input name="name" type="text" id="name" tabindex="1" autofocus required="required" />

                <label class="labelstyle" for="email" accesskey="E">Email</label>
                <input name="email" type="email" id="email" tabindex="2" required="required" />

                <label class="labelstyle" for="phone" accesskey="P" >Phone <small>(optional)</small></label>
                <input name="phone" type="tel" id="phone" tabindex="3">

                <label class="labelstyle" for="web" accesskey="W">Website <small>(optional)</small></label>
                <input name="website" type="text"  id="web" tabindex="4">

                <div id="radio-toolbar">
                    <input type="radio" id="radioMessage" name="reason" value="message" checked>
                    <label for="radioMessage">message</label>

                    <input type="radio" id="radioOrder" name="reason" value="order">
                    <label for="radioOrder">order</label>

                    <input type="radio" id="radioStatus" name="reason" value="status">
                    <label for="radioStatus">status</label> 
                </div>
                <p>&nbsp;</p>
                <label class="textareaLabel" for="comments">Comments Length:<span id="length"></span></label>
                <textarea name="comments" id="comments" spellcheck="true" tabindex="6" required="required"></textarea> 
                <?php if (filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL) == "localhost") { ?>
                    <div id="recaptcha" class="g-recaptcha" data-sitekey="6LcR8OQUAAAAAG1qLKJal22tLlpW4loJ7CIcfrlX" data-callback="correctCaptcha"></div>

                <?php } else { ?>
                    <!-- Use a data callback function that Google provides -->
                    <div id="recaptcha" class="g-recaptcha" data-sitekey="6LdXNpAUAAAAAMwtslAEqbi9CU3sviuv2imYbQfe" data-callback="correctCaptcha"></div>
                <?php } ?>
                <input id="submitForm" type="submit" name="submit" value="submit" tabindex="7" data-response="">
            </fieldset>
        </form>
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