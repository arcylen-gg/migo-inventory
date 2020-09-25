(function($) {
    'use strict'; // Start of use strict

    $(window).on("load scroll", function() {

        /*------------------------------------------------------------------
        Loader
        ------------------------------------------------------------------*/
        $("#loader").fadeOut("fast");
        // map zooming 	 
        $('.google-map').on('click', function() {
            $('.google-map').find('iframe').css("pointer-events", "auto");
        });
        /*------------------------------------------------------------------
   		 Animation Numbers
   		 ------------------------------------------------------------------*/
        jQuery('.animateNumber').each(function() {
            var num = jQuery(this).attr('data-num');

            var top = jQuery(document).scrollTop() + (jQuery(window).height());
            var pos_top = jQuery(this).offset().top;
            if (top > pos_top && !jQuery(this).hasClass('active')) {
                jQuery(this).addClass('active').animateNumber({
                    number: num
                }, 2000);
            }
        });
        jQuery('.animateProcent').each(function() {
            var num = jQuery(this).attr('data-num');
            var percent_number_step = jQuery.animateNumber.numberStepFactories.append('%');
            var top = jQuery(document).scrollTop() + (jQuery(window).height());
            var pos_top = jQuery(this).offset().top;
            if (top > pos_top && !jQuery(this).hasClass('active')) {
                jQuery(this).addClass('active').animateNumber({
                    number: num,
                    numberStep: percent_number_step
                }, 2000);
                jQuery(this).css('width', num + '%');
            }
        });
        /*------------------------------------------------------------------
		Initialize Morph text for rotating text in header
		------------------------------------------------------------------*/
        $("#js-rotating").Morphext({
            animation: "flipInX",
            separator: "|",
            speed: 4000,
            complete: function() {}
        });
        /*----------------------------------------------------
          Start Change Menu Bg
		 ----------------------------------------------------*/
        $(window).on('scroll', function() {
            if ($(window).scrollTop() > 50) {
                $('.welcome-hero-area').addClass('menu-bg');
            } else {
                $('.welcome-hero-area').removeClass('menu-bg');
            }
        });
    });

    /*------------------------------------------------------------------
     Scroll Top
     ------------------------------------------------------------------*/
    $.scrollUp({
        scrollText: '<i class="fa fa-chevron-up"></i>',
        easingType: 'linear',
        scrollSpeed: 900,
        animation: 'fade'
    });

    /*------------------------------------------------------------------
    Navigation Hover effect
    ------------------------------------------------------------------*/
    // jQuery for page scrolling feature - requires jQuery Easing plugin

    $('.smoth-scroll').on('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: ($($anchor.attr('href')).offset().top - 50)
        }, 1250, 'easeInOutExpo');
        event.preventDefault();
    });
    // Highlight the top nav as scrolling occurs

    $('body').scrollspy({
        target: '.navbar-default',
        offset: 70
    });
    // Closes the Responsive Menu on Menu Item Click

    $('.navbar-collapse ul li a:not(.dropdown-toggle)').on('click', function() {
        $('.navbar-toggle:visible').click();
    });

    /*------------------------------------------------------------------
   	 Scrollup opacity downarrow 
	 ------------------------------------------------------------------*/
    var bottom_arrow = $('.bottom_row, .banner-content');
    $(window).on('scroll', function() {
        var st = $(this).scrollTop();
        bottom_arrow.css({
            'opacity': (1 - (st / 350))
        });
    });

    /*------------------------------------------------------------------
    	theme color change
    ------------------------------------------------------------------*/

    var theme_settings = $(".theme-settings").find(".theme-color");
    theme_settings.on('click', function() {
        var val = $(this).attr('data-color');
        $('#style_theme').attr('href', 'css/' + val + '.css');

        theme_settings.removeClass('theme-active');
        theme_settings.addClass('theme-active');
        return false;
    });
    $(".theme-click").on('click', function() {
        $("#switcher").toggleClass("active");
        return false;
    });

})(jQuery);

/*------------------------------------------------------------------
	WOW
------------------------------------------------------------------*/

wow = new WOW({
    animateClass: 'animated',
    offset: 100
});
wow.init();