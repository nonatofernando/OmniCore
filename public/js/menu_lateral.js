$(document).ready(function () {
    const path = window.location.pathname;

    $(".sidebar-nav a").removeClass("active");

    let currentLink = $(`.sidebar-nav a[href="${path}"]`);

    if (path === "/" || path === "") {
        currentLink = $('.sidebar-nav a[href="/"]');
    }

    currentLink.addClass("active");
});
