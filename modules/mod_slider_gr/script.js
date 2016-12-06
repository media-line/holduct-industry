jQuery(document).ready(function () {
    function modSliderGrHandler () {
        jQuery('.mod-slider-gr').each(function () {
            jQuery(this).find('.js-slick-slider').slick({  
                autoplay: true,
                arrows: false,
                slidesToShow: 1,
                infinity: true,
                autoplaySpeed: 0,
                cssEase: 'linear',
                speed: 20000,
            });
        });

        jQuery('.js-dot').click(function () {
            jQuery(this).closest('.mod-slider-gr').find('.js-slick-slider').slick('slickGoTo', jQuery(this).index());
            jQuery('.js-dot').removeClass('active');
            jQuery(this).addClass('active');
        });
    }

    modSliderGrHandler ();
});
