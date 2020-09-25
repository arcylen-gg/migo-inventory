var myApp = new Framework7( { animateNavBackIcon:true });
var $$ = Dom7;
var mainView = null;
var login = new login();

function login()
{
	init();

	function init()
	{
		framework7init();

		$(document).ready(function()
		{
			page_ready();
		});

	}
	function page_ready()
	{

    	$$('form.ajax-submit').off('form:success');
		$$('form.ajax-submit').on('form:success', function (e)
		{
			var data = $.parseJSON(e.detail.data);

			if(data.status == "error")
			{
				myApp.alert(data.message, data.title);
			}
			else
			{
				window.location.reload();
			}
		});
	}
	function framework7init()
	{
		mainView = myApp.addView('.view-main',
		{
		    dynamicNavbar: true,
		    domCache: true
		});
	}
}