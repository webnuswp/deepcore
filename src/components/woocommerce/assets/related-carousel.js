(function ($) {
    $(document).ready(function () {
        $(".deep-woo-related-carousel-wrap").owlCarousel({
            items: 5,
            autoPlay: true,
            pagination: true,
            dots: true,
            navigationText: ["", ""],
            margin: 30,
            responsive: {
                0: {
                    items: 1,
                },
                600: {
                    items: 3,
                },
                960: {
                    items: 4,
                },
                1400: {
                    items: 5,
                }
            }
        });
    });
})(jQuery);