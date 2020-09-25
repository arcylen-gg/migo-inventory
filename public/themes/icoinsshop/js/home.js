var home_js = new home_js();

function home_js()
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
		action_product_show();
		action_news_show();
	}

	function action_product_show()
	{
		$('.product-display').slick(
		{
			prevArrow:"<img class='a-left control-c prev slick-prev' src='/themes/icoinsshop/img/arrow-left.png'>",
			nextArrow:"<img class='a-right control-c next slick-next' src='/themes/icoinsshop/img/arrow-right.png'>",
			infinite: true,
			slidesToShow: 3,
			slidesToScroll: 1,
	  		autoplay: true,
			autoplaySpeed: 3000,
			arrows: true,
			dots: false,

			responsive: [
			    {
			      breakpoint: 1024,
			      settings: {
			        slidesToShow: 2,
			        slidesToScroll: 1,
			        infinite: true,
			      }
			    },
			    {
			      breakpoint: 480,
			      settings: {
			        slidesToShow: 1,
			        slidesToScroll: 1
			      }
			    }
			    // You can unslick at a given breakpoint now by adding:
			    // settings: "unslick"
			    // instead of a settings object
			  ]
		});
	}

	function action_news_show()
	{
		$('.news-display').slick(
		{
			prevArrow:"<img class='a-left control-c prev slick-prev' src='/themes/icoinsshop/img/arrow-left.png'>",
			nextArrow:"<img class='a-right control-c next slick-next' src='/themes/icoinsshop/img/arrow-right.png'>",
			infinite: true,
			slidesToShow: 3,
			slidesToScroll: 1,
	  		autoplay: true,
			autoplaySpeed: 3000,
			arrows: false,
			dots: false,

			responsive: [
			    {
			      breakpoint: 1024,
			      settings: {
			        slidesToShow: 2,
			        slidesToScroll: 1,
			        infinite: true,
			      }
			    },
			    {
			      breakpoint: 480,
			      settings: {
			        slidesToShow: 1,
			        slidesToScroll: 1
			      }
			    }
			    // You can unslick at a given breakpoint now by adding:
			    // settings: "unslick"
			    // instead of a settings object
			  ]
		});
	}

}
