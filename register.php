<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use Miniature\Calendar;
use Miniature\Database as DB;
use Miniature\Users;
use Miniature\sendMail;

$db = DB::getInstance();
$pdo = $db->getConnection();

$monthly = new Calendar();

$monthly->phpDate();

$calendar = $monthly->generateCalendar($basename);

$register = new Users();

function confirmationNumber() {
    $status = bin2hex(random_bytes(32));
    return $status;
}

function duplicateUsername($username, $pdo) {
    $query = "SELECT 1 FROM users WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row) {
        return true; // userName is in database table
    }
}

$submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


if (isset($submit) && $submit === 'enter') {

    $data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);

    $username = trim($data['username']);

    $statusUsername = duplicateUsername($username, $pdo);

    if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $emailStatus = true;
    } else {
        $emailStatus = false;
        $errEmail = "Email Address is not a valid email address";
    }

    if (!$statusUsername && $emailStatus) {
        
        $sendMail = new sendMail();
        $data['status'] = confirmationNumber();

        if (filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL) == "localhost") {
            $comments = 'Here is you confirmation link: http://localhost/mainsite_08182020/activate.php?confirmation=' . $data['status'];
        } else {
            $comments = 'Here is you confirmation link: https://www.miniaturephotographer.com/activate.php?confirmation=' . $data['status'];
        }
        
        $sendMail->subject('Thank for Registering at The Miniature Photographer!');
        $sendMail->sendTo([$data['email'] => $data['username']]);
        $sendMail->sendFrom();
        $sendMail->content($comments);
        
        
        if ($data['password'] === $data['repeatPassword']) {
            $status = $sendMail->sendEmail();
            if ($status) {
                $result = $register->register($data);
                if ($result) {
                    unset($data); // Delete User's Data:
                    header("Location: success.php");
                    exit;
                } else {
                    $message = "Invalid Email Entry";
                }
            }
        } else {
            $errPassword = "Passwords did not match, please re-enter";
        }
    }
}

include_once 'assets/includes/header.inc.php';
?>


<div class="content">
    <main class="main-area">
        <form class="registerForm" action="" method="post" autocomplete="on">

            <h1><?php echo (isset($message)) ? $message : 'Register'; ?></h1>
            <p><?php echo (isset($errPassword)) ? $errPassword : "Please fill in this form to create an account."; ?></p>
            <hr>

            <label for="fullName"><b>Full Name</b></label>
            <input id="fullNamae" type="text" placeholder="Enter Full Name" name="data[fullName]" autofocus required>

            <label for="username"><b>Username <span class="unavailable"> - Not Available, please choose a different one.</span></b></label>
            <input id="username" type="text" placeholder="<?php echo (isset($statusUsername) && $statusUsername) ? "Username is not available, please re-enter!" : "Enter Username"; ?>" name="data[username]" value="<?php echo (isset($data['username'])) ? $data['username'] : null; ?>" required>

            <label for="email"><?php echo (isset($errEmail)) ? $errEmail : "<b>Email</b>"; ?></label>
            <input type="email" placeholder="Enter Email" name="data[email]" value="<?php echo (isset($data['email'])) ? $data['email'] : null; ?>" required>

            <label for="psw"><b>Password <span class="recommendation">recommendation at least (8 characters long, 1 uppercasse letter, 1 number, and 1 special character)</span></b></label>
            <input id="password" type="password" placeholder="Enter Password" name="data[password]" required>

            <label for="psw-repeat"><b>Repeat Password</b></label>
            <input type="password" placeholder="Repeat Password" name="data[repeatPassword]" required>
            <hr>

            <p>By creating an account you agree to our <a href="termsPolicy.php">Terms & Privacy</a>.</p>
            <input type="submit" name="submit" value="enter" class="registerbtn">


            <div class="signin">
                <p>Already have an account? <a href="index.php">Sign in</a>.</p>
            </div>
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

<footer class="footer-area">
    <p>&copy;2020 The Miniature Photographer</p>
</footer>

</div><!-- .outer-wrap -->

<script type="text/javascript" src="assets/js/new-sidebar-switcher.js"></script>

</body>

</html>

