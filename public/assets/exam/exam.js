var myApp = new Framework7( { animateNavBackIcon:true });
var $$ = Dom7;
var mainView = null;
var exam = new exam();

function exam()
{
	init();

	function init()
	{
		framework7init();

		$(document).ready(function()
		{
			page_ready();
		});

        myApp.onPageInit('register-step1', function(page)
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

					mainView.router.loadPage(data.load_page);
				}
			});
        });
	}
	function page_ready()
	{
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