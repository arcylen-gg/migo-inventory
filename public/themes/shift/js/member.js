var member = new member();

function member()
{
	init();

	function init()
	{
		document_ready();
		window_load();
		event_hover_sidebar();
	}

	function window_load()
	{
		$(window).load(function()
		{
			// action_break_overflow();
		});
	}

	function document_ready()
	{
		$(document).ready(function()
		{
			
		});
	}

	function event_hover_sidebar()
	{
		$(".sidebar").hover(function() 
		{
			$(".sidebar").removeClass("small");
		}, 
		function() 
		{
			$(".sidebar").addClass("small");
		});
	}

	function action_break_overflow()
	{
		breakOverflow($(".side-nav > ul > li > ul"));
	}

	function breakOverflow(elm) 
	{
	   var top = elm.offset().top;
	   var left = elm.offset().left;
	   elm.appendTo($('body'));
	   elm.css({
	      position: 'absolute',
	      left: left+'px',
	      top: top+'px',
	      bottom: 'auto',
	      right: 'auto',
	      'z-index': 10000
	   });
	} 
}