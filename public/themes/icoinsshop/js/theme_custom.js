var custom_js = new custom_js();

function custom_js()
{
    init();

    function init()
    {
        $(document).ready(function() 
        {
            document_ready();   
        });
    }

    function document_ready()
    {
        header_fixed();
        event_side_nav();
        genealogy_mobile_dropdown();
        scroll_up();
    }

    function scroll_up()
    {
        /*scroll up*/
        $(window).scroll(function () {
            if ($(this).scrollTop() > 700) {
                $('.scroll-up').fadeIn();
            } else {
                $('.scroll-up').fadeOut();
            }
        });

        $('.scroll-up').click(function () {
            $("html, body").animate({
                scrollTop: 0
            }, 700);
            return false;
        });
    }

    function header_fixed()
    {
        $window = $(window);
        $window.scroll(function() {
          $scroll_position = $window.scrollTop();
            if ($scroll_position > 100) { 
                $('.header-container').addClass('header-fixed');
        
                header_height = $('.your-header').innerHeight();
                $('body').css('padding-top' , header_height);
            } else {
                $('.header-container').removeClass('header-fixed');
                $('body').css('padding-top' , '0');
            }
         });
    }

    function event_side_nav()
    {
        $menuLeft = $('.pushmenu-left');
        $nav_list = $('#nav_list');
        
        $nav_list.on("click", function() 
        {
            $(this).toggleClass('active');
           /* $('.pushmenu-push').toggleClass('pushmenu-push-toright');*/
            $menuLeft.toggleClass('pushmenu-open');
        }); 
    }

    function genealogy_mobile_dropdown()
    {
        $(".genealogy-mobile-dropdown").on("click", function (e) 
        {
            $(e.currentTarget).siblings(".genealogy-mobile-dropdown-list").slideToggle();
            $(e.currentTarget).find(".fa-angle-down").toggleClass('fa-rotate-180');
        });
    }
}

function on() 
{
    document.getElementById("overlay").style.display = "block";
    $("body").css({"overflow": "hidden","position": "fixed","margin": "0","padding": "0","right": "0","left": "0"});
    // $(".blur-me").css({
    //     filter: 'blur(50px)'
    // });
}

function off()
{
    document.getElementById("overlay").style.display = "none";
    $('.pushmenu').removeClass("pushmenu-open");
    $("body").css({"overflow": "auto","position": "static"});
    // $(".blur-me").css({
    //     filter: 'blur(0)'
    // });
}