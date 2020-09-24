<footer class="footer-area">
    <p>&copy;2020 The Miniature Photographer</p>
</footer>

</div><!-- .outer-wrap -->

<script type="text/javascript" src="assets/js/new-sidebar-switcher.js"></script>
<?php if ($pageName === 'contact') { ?>
    <script src="assets/js/contact.js" async defer></script>
    <!-- Fetch the g-response using a callback function -->
    <script>
        var correctCaptcha = function (response) {
            document.querySelector('#submitForm').setAttribute('data-response', response);
        };
    </script>
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>
<?php } ?>
<?php if ($pageName === 'photo') { ?>
    <script src="assets/js/myLightBox.js"></script>
<?php } ?>
<?php if ($pageName === 'editQuiz') { ?>
    <script src="assets/js/edit.js"></script>
<?php } ?>
<!--<script src = "assets/js/slideshow.js"></script>-->
</body>

</html>