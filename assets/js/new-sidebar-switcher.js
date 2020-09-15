'use strict';
(function () {
    const d = document;
    const leftSidebarToggle = d.querySelector('.sidebar-left-toggle');
    const rightSidebarToggle = d.querySelector('.sidebar-right-toggle');
    const hideSidebarToggle = d.querySelector('.hide-sidebar-toggle');
    const sidebar = d.querySelector('.sidebar');
    const content = d.querySelector('.content');

    
    leftSidebarToggle.addEventListener('click', function () {
        if (!(sidebar.classList.contains('sidebar-left'))) {
            sidebar.classList.add('sidebar-left');
        }
    }, false);

    rightSidebarToggle.addEventListener('click', function () {
        if (sidebar.classList.contains('sidebar-left')) {
            sidebar.classList.remove('sidebar-left');
        }
    }, false);


    hideSidebarToggle.addEventListener('click', function () {
        if (!(sidebar.classList.contains('hide'))) {
            sidebar.classList.add('hide');
        } else {
            sidebar.classList.remove('hide');
        }
    }, false);


    
})();