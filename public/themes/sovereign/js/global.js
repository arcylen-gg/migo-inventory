var global = new global()

function global()
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
		action_match_height();
		action_product_carousel();
		event_show_product();
		event_nav_dropdown();
		event_scroll_up();
		event_side_nav();
		product_mobile_dropdown();
	}

	function product_mobile_dropdown()
	{
		$(".product-mobile-dropdown").on("click", function (e) 
		{
		    $(e.currentTarget).siblings(".product-mobile-dropdown-list").slideToggle();
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

	function action_match_height()
	{
		$(".match-height").matchHeight();

		$(window).resize(function()
		{
			$(".match-height").matchHeight();
		});
	}
	function event_show_product()
	{
		$(".show-product").unbind("click");
		$(".show-product").bind("click", function()
		{
			action_show_product();
		});
	}
	function action_show_product()
	{
		$('.product-container').toggleClass('show');

		if ($('.product-container').hasClass('show')) 
		{
			$('.show-product').parent().addClass('active');
		}
		else
		{
			$('.show-product').parent().removeClass('active');
		}
	}
	function action_product_carousel()
	{
		$('.product-container .list-product').slick({
			infinite: true,
			slidesToShow: 7,
			slidesToScroll: 1,
			prevArrow:"<img class='a-left control-c prev slick-prev' src='/themes/sovereign/img/carousel-left.png'>",
      		nextArrow:"<img class='a-right control-c next slick-next' src='/themes/sovereign/img/carousel-right.png'>",
      		autoplay: true,
			autoplaySpeed: 4000,
			responsive: [
			  {
			    breakpoint: 1024,
			    settings: {
			      slidesToShow: 4,
			      slidesToScroll: 1,
			      infinite: true,
			    }
			  },
			  {
			    breakpoint: 600,
			    settings: {
			      slidesToShow: 3,
			      slidesToScroll: 1
			    }
			  },
			  {
			    breakpoint: 480,
			    settings: {
			      slidesToShow: 2,
			      slidesToScroll: 1
			    }
			  }
			  // You can unslick at a given breakpoint now by adding:
			  // settings: "unslick"
			  // instead of a settings object
			]
		})
	}
	function event_nav_dropdown()
	{
		$('.dropdown.mega-dropdown a').on('click', function (event) 
		{
			$('.dropdown.mega-dropdown').removeClass('open');
		    $(this).parent().toggleClass('open');
		});

		$('body').on('click', function (e) 
		{
		    if (!$('.dropdown.mega-dropdown').is(e.target) 
		        && $('.dropdown.mega-dropdown').has(e.target).length === 0 
		        && $('.open').has(e.target).length === 0
		    ) {
		        $('.dropdown.mega-dropdown').removeClass('open');
		    }
		});
	}
}


/*JAVASCRIPT for event_side_nav*/
function on()
{
    document.getElementById("overlay").style.display = "block";
    $("body").css({"overflow": "hidden","position": "fixed","margin": "0","padding": "0","right": "0","left": "0"});
}

function off()
{
    document.getElementById("overlay").style.display = "none";
    $('.pushmenu').removeClass("pushmenu-open");
    $("body").css({"overflow": "auto","position": "static"});
}