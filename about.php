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

        <article class="about">
            <h2>About Me<span class="subheading">by John Pepp on Saturday, February 1, 2020</span></h2>
            <a class="myLightBox" id="image1" href="assets/images/img-john-001.jpg" title="Owner - John Pepp" data-picture="1" data-exif="Sony ILCE-7RM3 --- 50mm f/8.0 ISO 100 1/320s"><img class="blogBox" src="assets/images/thumb-john-001.jpg" alt="Owner - John Pepp"></a>
            <hr>
            <p>I'm a Freelance Photographer and Web Designer/Developer that enjoys what he is doing. I do photoshoots, design and develop websites using the latest coding practices. I haven't done any weddings, but am wiling to be an assistant or 2nd photographer at a wedding. I am willing to do modeling sessions that can be done outside like Downtown Detroit or some other place that has a nice ambience to it. All models must be 18 years old or older, sign a model release and note I DON'T do nude photography. As a matter of fact the model can be female or male as they are pictures that will look good for your portfolio as well as mine. You will have all rights to use the images, but I also have to right to use the images for my website and other business pursuits. Though I will let you know if I plan on using the pictures for other pursuits and that part will be on the model release. I don't discuss prices on this website, but if you are interested just drop me a message via the Contact Page. I know we can work something out.</p>             
            <p>I earned an Associate Degree in Computer Graphics Technology in December 2010 from Schoolcraft College which is located in Livonia, Michigan. I continued taking college courses in Motion Graphics and Web Design after obtaining my degree. Self-taught myself PHP in order to learn a backend language in order create content management systems and other useful web applications. I am seriously getting into photography and videography with hopes of incorporating my LEGO hobby into it. I plan on starting my own podcast to cover a wide range of topics, but most of the topics will be based on technology, photography and videography. In conclusion I would like to dedicate this website and future endeavors to my mother, Mildred I. Pepp who past away on February 26, 2017</p>
        </article>

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
