var custom = new custom();

function custom()
{
	init();

	function init()
	{
		$(document).ready(function($)
		{
			document_ready();
		});
	}

	function document_ready()
	{
		// event_toggle_nav();
		fix_header();
		event_mobile_sidenav();
	}

	function fix_header()
	{
		$window = $(window);

		$window.scroll(function() 
		{

		  $scroll_position = $window.scrollTop();

		    if ($scroll_position > 100)
		    { 
		        $('.header-container').addClass('header-fixed');

		        header_height = $('.your-header').innerHeight();

		        $('body').css('padding-top' , header_height);

		    } 

		    else 
		    {
		        $('.header-container').removeClass('header-fixed');
		        $('body').css('padding-top' , '0');
		    }

		 });
	}

	// function event_toggle_nav()
	// {
	//     $(".menu-nav").bind("click", function()
	//     {
	//         action_toggle_nav();
	//     });
	// }

	// function action_toggle_nav()
	// {
	//     $(".menu-nav").unbind("click");
	//     $(".navirino").slideToggle(400, function()
	//     {
	//         event_toggle_nav();
	//     });
	// }

	function event_mobile_sidenav()
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
}

function on()
{
    document.getElementById("overlay").style.display = "block";

    $("body").css({"overflow": "hidden","position": "fixed","margin": "0","padding": "0","right": "0","left": "0"});
        $(".blur-me").css({
        filter: 'blur(20px)'
    });

}

function off()
{
    document.getElementById("overlay").style.display = "none";
    $('.pushmenu').removeClass("pushmenu-open");
    $("body").css({"overflow": "auto","position": "static"});
        $(".blur-me").css({
        filter: 'blur(0)'
    });
}