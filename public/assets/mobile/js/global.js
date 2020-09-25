var global = new global();
var mainView = myApp.addView('.view-main');

function global()
{
	init();

	function init()
	{
		$(document).ready(function()
		{
			page_ready();
		});

        myApp.onPageInit('profile', function(page)
        {
            event_profile_update_form();
			event_reward_update_form();
			event_picture_update_form();
			event_password_update_form();
        });
	}

	function page_ready()
	{
		event_back_index();
		// mainView.router.loadPage('/members/order');
	}

	function event_back_index()
	{
		$("body").on("click", ".back-index", function()
		{
			action_back_index();
		})
	}

	function action_back_index()
	{
		mainView.router.back({url: "/members",force: true,ignoreCache: true});
	}

	function event_profile_update_form()
	{
		$$('form.ajax-submit.profile-update-form').off('form:success');
        $$('form.ajax-submit.profile-update-form').on('form:success', function(e)
        {
            action_profile_update_form(e);
        });
	}

	function action_profile_update_form(x)
	{
		var xhr = x.detail.xhr; // actual XHR object
        var data = x.detail.data; // Ajax response from action file
        var data = JSON.parse(data);

        if (data == "success") 
        {
        	myApp.addNotification({
		        message: 'Successfully Updated!'
		    });
        }
        else
        {
        	$.each(data, function(index, val) 
        	{
        		 myApp.addNotification({
			        message: val
			    });
        	});
        }
	}

	function event_reward_update_form()
	{
		$$('form.ajax-submit.reward-update-form').off('form:success');
        $$('form.ajax-submit.reward-update-form').on('form:success', function(e)
        {
            action_profile_update_form(e);
        });
	}

	function event_picture_update_form()
	{
		$(".upload-profile").unbind("change");
		$(".upload-profile").bind("change", function()
		{
			$(".tab-pane").css("opacity", "0.5");
			$(".upload-profile").unbind("change");	
		    readURL(this);
		});
	}

	function action_picture_update_form()
	{
		var upload_data = new FormData($(".profile-picture-form")[0]);

		$.ajax({
			url      	: '/members/profile-update-picture',
			data     	: upload_data,
			dataType 	: 'json',
			async    	: false,
			type     	: 'post',
			processData : false,
			contentType : false,
			success:function(response)
			{
				event_picture_update_form();
			},
		});
	}

	function readURL(input) 
	{
	    if (input.files && input.files[0]) 
	    {
	        var reader = new FileReader();
	        
	        reader.onload = function (e) 
	        {
				var filename = $(input).val().split('\\').pop();
				console.log(filename,$('.file-name'));
				var lastIndex = filename.lastIndexOf("\\");   
				$('.file-name').text(filename);

	        	console.log(e.target);
	            $('.img-upload').attr('src', e.target.result);

	            $(".tab-pane").css("opacity", "1");
	            action_picture_update_form();

	            event_picture_update_form();
	        }
	        
	        reader.readAsDataURL(input.files[0]);
	    }
	}

	function event_password_update_form()
	{
		$$('form.ajax-submit.password-update-form').off('form:success');
        $$('form.ajax-submit.password-update-form').on('form:success', function(e)
        {
            action_profile_update_form(e);
        });
	}
}