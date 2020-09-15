'use strict';
(function () {

    const d = document;

    d.addEventListener('click', function (event) {
        if (!event.target.classList.contains('dropdown-toggle')) {
            return;
        }
        event.preventDefault();
        if (event.target.classList.contains('dropdown-toggle')) {
            event.target.parentNode.nextElementSibling.classList.toggle('toggled-on');

            event.target.classList.toggle('toggle-on');
            if (event.target.classList.contains('toggle-on')) {
                event.target.querySelector('span').textContent = "Collapse Child Menu";
                event.target.setAttribute('aria-expanded', true);
            } else {
                event.target.querySelector('span').textContent = "Expand child menu";
                event.target.setAttribute('aria-expanded', false);
            }
        }
    }, false);

})();
