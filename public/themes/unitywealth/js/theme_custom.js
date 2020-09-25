var theme_custom = new theme_custom()

function theme_custom()
{
	init();
	function init()
	{
		document_ready();
	}
	function document_ready()
	{
		$(document).ready(function()
		{
			action_match_height();
			event_show_cart();
			action_fit_text();
			action_slick();
			ready_load_ecom_cart();
			ready_load_mini_ecom_cart();

			(function(d, s, id) {
	        var js, fjs = d.getElementsByTagName(s)[0];
	        if (d.getElementById(id)) return;
	        js = d.createElement(s); js.id = id;
	        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.10";
	        fjs.parentNode.insertBefore(js, fjs);
	        }(document, 'script', 'facebook-jssdk'));

	        $window = $(window);
	        $window.scroll(function() {
	          $scroll_position = $window.scrollTop();
	            if ($scroll_position > 32.2167) { 
	                $('.header-container').addClass('header-fixed');
	                $('.subheader-container').addClass('header-fixed');

	                header_height = $('.your-header').innerHeight();
	                $('body').css('padding-top' , header_height);
	            } else {
	                $('body').css('padding-top' , '0');
	                $('.header-container').removeClass('header-fixed');
	                $('.subheader-container').removeClass('header-fixed');
	            }
	         });

	        // Open a URL in a lightbox
			// var lightbox = lity('//www.youtube.com/watch?v=XSGBVzeBUbk');

			// Bind as an event handler
			$(document).on('click', '[data-lightbox]', lity);

			

	        $('.slider3').diyslider({
	            width: "580px", // width of the slider
	            height: "120px", // height of the slider
	            display: 5, // number of slides you want it to display at once
	            loop: false // disable looping on slides
	            }); // this is all you need!

	        // use buttons to change slide
	        $('#gotoleft').bind("click", function(){
	            // Go to the previous slide
	            $('.slider3').diyslider("move", "back");
	        });
	        $('#gotoright').unbind("click")
	        $('#gotoright').bind("click", function(){
	            // Go to the previous slide
	            $('.slider3').diyslider("move", "forth");
	        });

	        //Mel push menu
	        /*$(document).ready(function() {
        		$menuLeft = $('.pushmenu-left');
        		$nav_list = $('#nav_list');
        		
        		$nav_list.click(function() {
        			$(this).toggleClass('active');
        			$('.pushmenu-push').toggleClass('pushmenu-push-toright');
        			$menuLeft.toggleClass('pushmenu-open');
        		});
        	});*/

    		$menuLeft = $('.pushmenu-left');
    		$nav_list = $('#nav_list');
    		
    		$nav_list.click(function() {
    			$(this).toggleClass('active');
    			// $('.pushmenu-push').toggleClass('pushmenu-push-toright');
    			$menuLeft.toggleClass('pushmenu-open');
    		});

    		/*EXIT SIDE NAV TOGGLE*/
            $('.nav-ext').click(function()
            {
                $('#nav_list').click();
            });

	        // NAVIRINO CLICK TOGGLE
	        $(".menu-nav").click(function()
	        {
	            $(".navirino").toggle("slow");
	        });

	        /*PRODUCT HOVER TOGGLE*/
	        $('.product-hover').hover(function()
	        {
	            $('.product-dropdown').stop();
	            $('.product-dropdown').fadeIn(400);
	        },
	        function()
	        {
	            $('.product-dropdown').stop();
	            $('.product-dropdown').fadeOut(400);
	        });

	        $('.company-hover').hover(function()
	        {
	            $('.company-dropdown').stop();
	            $('.company-dropdown').fadeIn(400);
	        },
	        function()
	        {
	            $('.company-dropdown').stop();
	            $('.company-dropdown').fadeOut(400);
	        });

	        $('.cart-hover').hover(function()
	        {
	            $('.cart-dropdown').stop();
	            $('.cart-dropdown').fadeIn(400);
	        },
	        function()
	        {
	            $('.cart-dropdown').stop();
	            $('.cart-dropdown').fadeOut(400);
	        });

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
		});
	}
	function event_show_cart()
	{
		$('html').click(function() 
		{
			action_hide_cart();
		});

		$("body").on("click", ".shopping-cart-container", function(e)
		{
			event.stopPropagation();
			action_show_cart();
		});
	}
	function action_show_cart()
	{
		$(".shopping-cart-container .container-cart").addClass("show");
	}
	function action_hide_cart()
	{
		$(".shopping-cart-container .container-cart").removeClass("show");
	}
	function action_match_height()
	{
		$(".match-height").matchHeight();

		$(window).resize(function()
		{
			$(".match-height").matchHeight();
		});
	}
	function action_fit_text()
	{
		jQuery(".item-price").fitText(0.8, {
			maxFontSize: '16px',
			minFontSize: '8px',
		});
	}
	function action_slick()
	{
		$('.daily-container').slick({
			arrows: false,
			autoplay: true,
  			autoplaySpeed: 2000,
		})

		$(document).on('click', '.hot-deals-container .left-container-title .scroll-button a', function(event) 
		{
			event.preventDefault();
			var direction = $(event.currentTarget).attr("class");
			
			if (direction == "left") 
			{
				$(".daily-container").slick("slickPrev");
			}
			else
			{
				$(".daily-container").slick("slickNext");
			}
		});
	}
}

// CART GLOBAL
function ready_load_ecom_cart()
{
	$('#shopping_cart .modal-content').load('/cart',
	function()
	{
		event_load_cart();
	});
}

function event_load_cart()
{
	$('body').on('click', '.show-cart', function(event) 
	{
		event.preventDefault();
		
		$('#shopping_cart').modal();
	});
}

function action_load_cart()
{
	$('#shopping_cart .modal-content').load('/cart',
	function()
	{
		$('#shopping_cart').modal();
	});
}

function ready_load_mini_ecom_cart()
{
	$('.mini-cart').load('/mini_cart', function()
	{
		var quantity = $('.mini-cart .quantity-get').val();
		var total_price = $('.mini-cart .total-get').val();
		
		$('.mini-cart-quantity').html(quantity);
		$('.mini-cart-total-price').html(total_price);
	});
}

/*JAVASCRIPT for event_side_nav*/
function on() 
{
    document.getElementById("overlay").style.display = "block";
    $("body").css({"overflow": "hidden","position": "fixed","margin": "0","padding": "0","right": "0","left": "0"});
    $(".blur-me").css({
        filter: 'blur(50px)'
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