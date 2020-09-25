var genealogy_modal = new genealogy_modal();
var x = null;
function genealogy_modal()
{
	init();
	
	function init()
	{
		$(document).ready(function()
		{
			document_ready();
			console.log('genealogy_modal');
		});
	}
	function document_ready()
	{
		event_add_slot();
		event_close_modal();
		select2();
		event_radio_change();
		event_verify_sponsor();
		action_verify_submit();
		action_hide_new_and_exist();
	}
	function select2()
	{
		$('.code-vault').select2();
		$('.lead-list').select2();
	}
	function action_hide_new_and_exist()
	{
		$('.new-user').hide();
	    $('.exist-user').hide();
	}
	function action_verify_submit()
	{
		$('.form-create-slot').submit(function(e)
		{
			
			var owner = $("input[name='owner']:checked").val();

			var leadlist = $('.lead-list').val();
			
			var membership = $('.code-vault').val();
			var sponsor = $('.sponsor').val();

			var fname = $('.fname').val();
			var mname = $('.mname').val();
			var lname = $('.lname').val();
			var contact = $('.contact').val();
			var email = $('.email').val();
			var username = $('.username').val();
			var password = $('.password').val();
			var confirm_pass = $('.confirm_pass').val();

			var form = true;
			var pass = true;
			var passlength = true;
			var lead = true;

			var stop = false;

			var sponsor_verify = $('.verify-sponsor').attr('verify');

			console.log(membership);
			if(membership == '' || membership == null)
			{
				e.preventDefault();
				toastr.error('Invalid code');
				stop = true;
			}
			else if(sponsor_verify == 'false')
			{
				e.preventDefault();
				toastr.error('Invalid sponsor');
				stop = true;
			}

			if(!stop)
			{
				if(owner == 'self')
				{
					console.log('submit');
				}
				else if(owner == 'exist')
				{
					if(leadlist == '' || leadlist == null)
					{
						lead = false;
						console.log("lead list: "+leadlist+" lead: "+lead);
					}
					if(!lead)
					{
						toastr.error('Invalid lead list');
						e.preventDefault();
					}
				}
				else
				{
					if(fname == "")
					{
						form = false;
					}
					if(mname == "")
					{
						form = false;
					}
					if(lname == "")
					{
						form = false;
					}
					if(contact == "")
					{
						form = false;
					}
					if(email == "")
					{
						form = false;
					}
					if(username == "")
					{
						form = false;
					}
					if(password == "")
					{
						form = false;
					}
					if(confirm_pass == "")
					{
						form = false;
					}

					if(password != confirm_pass)
					{
						pass = false;
					}
					if(password.length < 8 )
					{
						passlength = false;
					}

					if(!form)
					{
						toastr.error('please complete all fields');
						e.preventDefault();
					}
					else if(!pass)
					{
						toastr.error('password did not match');
						e.preventDefault();
					}
					else if(!passlength)
					{
						toastr.error('password must be at least 8 characters');
						e.preventDefault();
					}
				}
			}
			

		});
	}
	function event_verify_sponsor()
	{
		$('.sponsor').keydown(function()
		{
			action_verify_loader();
			clearTimeout(x);

			x = setTimeout(function()
			{
				action_verify_sponsor();
			}, 1000);
		});
	}
	function action_verify_loader()
	{
		$('.verify-sponsor').html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
	}
	function action_verify_sponsor()
	{
		var shop_id = $('.shop_id').val();
		var sponsor = $('.sponsor').val();
		$.ajax(
		{
			url: '/members/available-sponsor',
			type: 'get',
			data: 'shop_id='+shop_id+"&sponsor="+sponsor,
			success: function(data)
			{
				if(data == 'true')
				{
					$('.verify-sponsor').html('<font color="green"<i class="fa fa-check-circle-o" aria-hidden="true"></font></i>');
					$('.verify-sponsor').attr('verify','true');
				}
				else
				{
					$('.verify-sponsor').html('<font color="red"><i class="fa fa-times-circle-o" aria-hidden="true"></font></i>');
					$('.verify-sponsor').attr('verify','false');
				}
			}
		});
	}
	function event_add_slot()
	{
		$('body').on('click','.positioning',function(e)
        {
            console.log($(e.currentTarget).attr('placement'));
            $('.slot-position').val($(e.currentTarget).attr('position'));
            $('.slot-placement').val($(e.currentTarget).attr('placement'));
            // alert($('.slot-placement').val()+" "+$('.slot-position').val());
            var no_of_codes = $('.no_of_codes').val();
            if(no_of_codes > 0)
            {
            	event_show_modal();
            }
            else
            {
            	toastr.error('No package available');
            }
        });
	}
	function event_close_modal()
	{
		$('.close-modal').on('click',function()
		{
			var modal = document.getElementById('myModal');
			modal.style.display = "none";
		});
	}
	function event_show_modal()
	{
        var modal = document.getElementById('myModal');
		modal.style.display = "block";
	}
	function event_radio_change()
	{
		$('input[type=radio][name=owner]').change(function()
		{
	        if(this.value == 'new')
	        {
	        	$('.new-user').slideDown();
	        	$('.exist-user').slideUp();
	        }
	        else if(this.value == 'exist')
	        {
	        	$('.exist-user').slideDown();
	        	$('.new-user').slideUp();
	        }
	        else
	        {
	        	$('.new-user').slideUp();
	        	$('.exist-user').slideUp();
	        }
	    });
	}
}