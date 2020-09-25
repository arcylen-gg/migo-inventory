var theme_custom = new theme_custom();
var jqxhr = {abort: function () {}};

function theme_custom()
{
    this.call_event_cart = function() 
    {
        cart_event();
    }

    init();

    function init()
    {
        $(document).ready(function()
        {
            document_ready();
        });

        $(window).load(function() 
        {
            window_load();
        });
    }
    function document_ready()
    {
        action_image_crop();
        match_height();
        event_sticky_nav();
        event_list_category();

        $(".date-picker").datepicker({
            dateFormat:"yy-mm-dd"
        });
    }
    function window_load()
    {
        if (typeof action_after_load == 'function') 
        {
            action_after_load();    
        }
    }
    function match_height()
    {
        $('.match-height').matchHeight();
    }
    function event_sticky_nav()
    {
        var stickyOffset = $('.main-content-scroll').offset().top;
        var nav_height = $('header').height();

        $(window).resize(function()
        {
            $("header").removeClass('hide'); 
            $('.sticky-nav').addClass('hide-sticky'); 
            $('.main-content-scroll').css("margin-top", "");

            stickyOffset = $('.main-content-scroll').offset().top;
            nav_height = $('header').height();
        });

        action_sticky_nav(stickyOffset, nav_height);

        $(window).bind("scroll", function()
        {
            action_sticky_nav(stickyOffset, nav_height);
        });
    }
    function action_sticky_nav(stickyOffset, nav_height)
    {
        var sticky = $('header'),
            scroll = $(window).scrollTop();

        if (scroll >= stickyOffset) 
        {
            sticky.addClass('hide'); 
            $('.sticky-nav').removeClass('hide-sticky'); 
            $('.main-content-scroll').css("margin-top", nav_height);
        }
        else 
        {
            sticky.removeClass('hide'); 
            $('.sticky-nav').addClass('hide-sticky'); 
            $('.main-content-scroll').css("margin-top", "");
        }
    }
    function event_list_category()
    {
        $('.list-category-button').unbind("click");
        $('.list-category-button').bind("click", function()
        {
            action_list_category();       
        });
    }
    function action_list_category()
    {
        $('.list-category').toggleClass('hide');
    }
}