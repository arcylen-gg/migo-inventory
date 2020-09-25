var home = new home();

function home()
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
		event_scroll_up();
		event_slick();
		event_show_more();
	}

	function event_show_more()
	{
	    $(".show-more-1").on("click", function () {

		    if($(this).text()=="Show less.")
		    {
		        $(this).text("Show more.");
		    } else {
		        $(this).text("Show less.");
		    }
	    	$(".show-1").slideToggle();
	    	return false;
		});

		$(".show-more-2").on("click", function () {

		    if($(this).text()=="Show less.")
		    {
		        $(this).text("Show more.");
		    } else {
		        $(this).text("Show less.");
		    }
	    	$(".show-2").slideToggle();
	    	return false;
		});

	    $(".show-more-3").on("click", function () {

		    if($(this).text()=="Show less.")
		    {
		        $(this).text("Show more.");
		    } else {
		        $(this).text("Show less.");
		    }
	    	$(".show-3").slideToggle();
	    	return false;
		});
	}

	function event_slick()
	{
		$('.autoplay').slick({
			slidesToShow: 4,
			slidesToScroll: 1,
			dots: false,
			autoplay: true,
			autoplaySpeed: 2000,
			responsive: [
			    {
			      breakpoint: 1024,
			      settings: {
			        slidesToShow: 3,
			        slidesToScroll: 3,
			        infinite: true,
			        dots: true
			      }
			    },
			    {
			      breakpoint: 600,
			      settings: {
			        slidesToShow: 2,
			        slidesToScroll: 2
			      }
			    },
			    {
			      breakpoint: 480,
			      settings: {
			        slidesToShow: 1,
			        slidesToScroll: 1
			      }
			    }
			  ]
		});
	}

	function event_scroll_up()
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
}