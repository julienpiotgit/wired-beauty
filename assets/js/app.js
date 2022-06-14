/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../app.css';

// start the Stimulus application
import '../bootstrap';
import AOS from 'aos/dist/aos';
import $ from 'jquery';

AOS.init();


var confer_window = $(window);

confer_window.on('load', function () {
    $('#preloader').fadeOut('1000', function () {
        $(this).remove();
    });
});

$(document).ready(function() {
    $('li.active').removeClass('active');
    $('a[href="' + location.pathname + '"]').closest('li').addClass('active');
});

document.addEventListener("DOMContentLoaded", function(){
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            document.getElementById('navbar_top').classList.add('scrolling');
            // // add padding top to show content behind navbar
            // var navbar_height = document.querySelector('.navbar').offsetHeight;
            // document.body.style.paddingTop = navbar_height + 'px';
        } else {
            document.getElementById('navbar_top').classList.remove('scrolling');
            // remove padding top from body
            document.body.style.paddingTop = '0';
        }
    });
});
