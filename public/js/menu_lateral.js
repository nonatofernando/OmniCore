$(document).ready(function () {
    const path = window.location.pathname;

    $(".sidebar-nav a").removeClass("active");

    let currentLink = $(`.sidebar-nav a[href="${path}"]`);

    if (path === "/" || path === "") {
        currentLink = $('.sidebar-nav a[href="/"]');
    }

    currentLink.addClass("active");

    $("#menu_toggle").on("click", function () {
        $(".sidebar").toggleClass("active");
        $("#overlay").toggleClass("active");
    });

    $("#overlay").on("click", function () {
        $(".sidebar").removeClass("active");
        $("#overlay").removeClass("active");
    });
});
