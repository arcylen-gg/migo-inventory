var home = new home();

function home()
{
	init();

	function init()
	{
		document_ready();
	}

	function document_ready()
	{
		action_featured_carousel();
	}

	function action_featured_carousel()
	{
		$('.product-carousel').slick({
		  dots: true,
		  infinite: true,
		  speed: 300,
		  slidesToShow: 4,
		  slidesToScroll: 4,
		  arrows: true,
		  prevArrow:"<img style='width: 10px; height: 15px;' class='a-left control-c prev slick-prev' src='/themes/brown/img/left.png'>",
		  nextArrow:"<img style='width: 10px; height: 15px;' class='a-right control-c next slick-next' src='/themes/brown/img/right.png'>",
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
		    // You can unslick at a given breakpoint now by adding:
		    // settings: "unslick"
		    // instead of a settings object
		  ]
		});

	}
}