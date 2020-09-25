var myApp = new Framework7(
{
	animateNavBackIcon:true,
	cache:false,
    onAjaxStart: function (xhr) { myApp.showIndicator(); },
    onAjaxComplete: function (xhr) { myApp.hideIndicator(); }
});

var $$ = Dom7;
var mainView = null;
var admin = new admin();

function admin()
{
	init();

	function init()
	{
		framework7init();

		$(document).ready(function()
		{
			page_ready();
		});

		/* CUSTOMER LIST EVENTS */
        myApp.onPageInit('customer-list', function(page)
        {
       		event_customer_list_actions();  	
        });

        /*  CUSTOMER EDIT EVENTS */
        myApp.onPageInit('customer-edit', function(page)
        {
        	event_customer_edit_archive(page);
        	event_customer_edit_restore(page);
		});

        myApp.onPageInit('*', function(page)
        {
        	event_tab(page);
        	event_global_ajax_submit(page);
        	
			$$('form.ajax-submit').on('form:beforesend', function (e) { myApp.showIndicator(); });
			$$('form.ajax-submit').on('form:error', function (e) { myApp.hideIndicator(); });
        });

        myApp.onPageBack('*', function(page)
        {
        	event_tab(page, true);
        });
	}

	function event_tab(page, back)
	{
		$(".tab-link").removeClass("active");

		if(mainView.url == "#index")
		{
			$(".tab-link.dashboard").addClass("active");
		}
	}
	function event_global_ajax_submit(page)
	{
		$$('form.ajax-submit').on('form:success', function (e)
		{
			var isjson = true;

			try
			{
				var data = $.parseJSON(e.detail.data);
			}
			catch(err)
			{
				isjson = false;
			}

			if(isjson)
			{
				myApp.alert(data.message, data.title);

				if(data.back)
				{
	  				mainView.router.back(
	  				{
	  					url: page.view.history[page.view.history.length - 2],
	  					force: true,
            			ignoreCache: true
        			});
				}
			}
			else
			{
				myApp.alert("Kindly check your internet connection.", "Error Occurred");
			}


			myApp.hideIndicator();
		});
	}
	function event_customer_edit_archive(page)
	{
		$$('.confirm-archive').on('click', function ()
		{
		    myApp.confirm('Are you sure you want to archive this customer?','Confirm Archive', function ()
		    {
		        $shop_id = $(".customer-edit-shop-id").val();

		        $.ajax(
		        {
		        	url:"/super/client/archive",
		  			data: {'shop_id':$shop_id},
		  			type:"get",
		  			success: function(data)
		  			{
		  				mainView.router.back(
		  				{
		  					url: page.view.history[page.view.history.length - 2],
		  					force: true,
                			ignoreCache: true
            			});

		  			}
		        });


			});
		});
	}
	function event_customer_edit_restore(page)
	{
		$$('.confirm-restore').on('click', function ()
		{
		    myApp.confirm('Are you sure you want to restore this customer?','Confirm Restore', function ()
		    {
		        $shop_id = $(".customer-edit-shop-id").val();

		        $.ajax(
		        {
		        	url:"/super/client/restore",
		  			data: {'shop_id':$shop_id},
		  			type:"get",
		  			success: function(data)
		  			{
		  				mainView.router.back(
		  				{
		  					url: page.view.history[page.view.history.length - 3],
		  					force: true,
                			ignoreCache: true
            			});
		  			}
		        });


			});
		});
	}
	function event_customer_list_actions()
	{
    	var mySearchbar = $$('.searchbar')[0].f7Searchbar;
		$$('.action-sheet-customer').off('click')
		$$('.action-sheet-customer').on('click', function ()
		{
		    var buttons = [
		        {
		            text: 'Add New Client',
		            bold: true,
		            onClick: function ()
		            {
		                mainView.router.loadPage('/super/client/add');
		            }
		        },
		        {
		            text: 'Archive List',
		            onClick: function ()
		            {
		                mainView.router.loadPage('/super/client?filter=archive');
		            }
		        },
		        {
		            text: 'Cancel',
		            color: 'red'
		        },
		    ];
		    myApp.actions(buttons);
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