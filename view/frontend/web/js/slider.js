define([
    'jquery',
    'slick',
], function($) {
    'use strict';

    return function(config, element) {
        $(element).slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 10000, // 10 seconds
        });
    };
});
