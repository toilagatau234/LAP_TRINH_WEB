let userBox = document.querySelector('.header .user-box');

document.querySelector('#user-btn').onclick = () => {
    userBox.classList.toggle('active');
    navbar.classList.remove('active');
}

let navbar = document.querySelector('.header .navbar');

document.querySelector('#menu-btn').onclick = () => {
    navbar.classList.toggle('active');
    userBox.classList.remove('active');
}

window.onscroll = () => {
    userBox.classList.remove('active');
    navbar.classList.remove('active');

    if (window.scrollY > 60) {
        document.querySelector('.header').classList.add('active');
    } else {
        document.querySelector('.header').classList.remove('active');
    }
}

////////////////////////////////////////////////////////////////////
const scrollToTopButton = document.getElementById('fcircle');
const scrollFunction = () => {
    let y = window.scrollY;
    if (document.body.scrollTop > 25 || document.documentElement.scrollTop > 25) {
        scrollToTopButton.style.opacity = "1";
    } else {
        scrollToTopButton.style.opacity = "0";
    }
};
window.addEventListener("scroll", scrollFunction);

const scrollToTop = () => {
    const c = document.documentElement.scrollTop || document.body.scrollTop;
    if (c > 0) {
        const step = Math.max(120, Math.ceil(c * 1));
        const next = c - step;
        window.scrollTo(0, next > 0 ? next : 0);
        if (next > 0) window.requestAnimationFrame(scrollToTop);
    }
};
scrollToTopButton.onclick = function (e) {
    e.preventDefault();
    scrollToTop();
}
// ///////////////////////////////////////////
$(document).ready(function () {
    $(window).scroll(function () {
        if ($(this).scrollTop()) {
            $('header').addClass('sticky');
        } else {
            $('header').removeClass('sticky');
        }
    });
});