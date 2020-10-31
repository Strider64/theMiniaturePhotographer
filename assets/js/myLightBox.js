'use strict';

const myLightBox = () => {
    const d = document;
    const shade = d.querySelector('.shade'); // Shade Box
    const total = d.querySelector('#gallery').getAttribute('data-total'); // Total Images to Display
    const prevBtn = d.querySelector('#preSlide');
    const nextBtn = d.querySelector('#nextSlide');
    const exitBtn = d.querySelector('#exitBtn');
    var count = 0;
    var picture = d.querySelector('#pictureELE'); // Large Picture in Shade Box:
    var exif = d.querySelector('.exifInfo');
    var addInfo = d.querySelector('#exifData'); // EXIF data from Camera:

    /* Exit myLightBox */
    const exitPicture = () => {
        shade.style.display = "none";
    };

    const hideExtra = () => {
        addInfo.style.display = 'none';
        exif.style.display = "none";
    };

    const displayExtra = () => {
        addInfo.style.display = 'block';
        exif.style.display = "block";

    };



    /* Display Shade Box */
    const displayPicture = (image, exifData) => {
        shade.style.display = "block";

        picture.setAttribute('src', image);
        addInfo.textContent = exifData;

    };

    picture.addEventListener('mouseover', displayExtra, false);
    shade.addEventListener('mouseout', hideExtra, false);
    const prevPic = (e) => {
        e.preventDefault();
        if (parseInt(count) > 1) {
            count = parseInt(count) - 1;
        } else {
            count = parseInt(total);
        }
        const image = d.querySelector('#image' + count).getAttribute('href');
        const exif = d.querySelector('#image' + count).getAttribute('title');

        displayPicture(image, exif);
    };

    const nextPic = (e) => {
        e.preventDefault();
        console.log('count', count);
        //console.log(picture);
        if (count <= parseInt(total) - 1) {
            count = parseInt(count) + 1;
        } else {
            count = 1;
        }
        const image = d.querySelector('#image' + count).getAttribute('href');
        const exif = d.querySelector('#image' + count).getAttribute('title');

        displayPicture(image, exif);

    };

    /* Display User's Selection in Shade Box */
    const startLightBox = (e) => {
        console.log(e.target.classList);

        if (e.target.getAttribute('id') === 'pictureELE') {
            exitPicture();
        }
        if (e.target.classList.contains('box')) {
            e.preventDefault();
        } else {
            return;
        }


        var image = e.target.parentNode.getAttribute("href"); // Grab Current Large Image:
        var exifData = e.target.parentNode.getAttribute('title'); // Grab Current EXIF data:

        displayPicture(image, exifData);
        count = 1;
        console.log('count', count);
        prevBtn.addEventListener('click', prevPic, false);
        nextBtn.addEventListener('click', nextPic, false);
    };

    d.addEventListener('click', startLightBox, false);
};

myLightBox(); // Start myLightBox: