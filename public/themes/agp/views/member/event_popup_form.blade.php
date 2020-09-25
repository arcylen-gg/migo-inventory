<form class="global-submit events-popup" method="post" action="{{$action}}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="event_id" value="{{ $event->event_id or '' }}">
	<div class="modal-body clearfix">
		<div class="top-head row clearfix">
			<div class="col-md-4">
				<div class="header-logo">
					<img class="h-logo" src="/themes/{{ $shop_theme }}/img/header-logo.png">
				</div>
			</div>
			<div class="col-md-8">
				<div class="head-title">
					<h1>BROWN&PROUD ACADEMY</h1>
					<h2>{{$event->event_title}}</h2>
					<h3>OFFICIAL APPLICATION FORM</h3>
					<h2>{{strtoupper(date('M d, Y', strtotime($event->event_date)))}}</h2>
				</div>
			</div>
		</div>
		<div class="form-group clearfix">
			<div class="error-message-content hidden">
				
			</div>
		</div>
		<div class="form-group clearfix">
			<div class="col-md-4">
				<input type="text" class="form-control primary-fill" placeholder="FAMILY NAME" name="reservee_lname" value="{{$customer_details->last_name or ''}}">
			</div>
			<div class="col-md-4">
				<input type="text" class="form-control primary-fill" placeholder="FIRST NAME" name="reservee_fname" value="{{$customer_details->first_name or ''}}">
			</div>
			<div class="col-md-4">
				<input type="text" class="form-control primary-fill" placeholder="MIDDLE NAME" name="reservee_mname" value="{{$customer_details->middle_name or ''}}">
			</div>
		</div>
		<div class="form-group clearfix">
			<div class="col-md-12">
				<div style="color: #a1a1a1;">ADDRESS:</div>
				<textarea class="form-control" placeholder="Address" name="reservee_address" style="height: 150px">{{$customer_address->customer_street or '' }} {{$customer_address->customer_city or '' }} {{$customer_address->customer_state or ''}}</textarea>
			</div>
		</div>
		<div class="form-group clearfix">
			<div class="col-md-12">
				<input type="text" class="form-control" name="reservee_contact" placeholder="CONTACT INFO (Email/Mobile Number)" value="{{$customer_details->email or ''}}">
			</div>
		</div>
		<div class="form-group clearfix">
			<div class="col-md-12">
				<input type="text" class="form-control" name="reservee_enrollers_code" placeholder="ENROLLERS CODE">
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<div ><button class="reg-button reserved-submit" type="button">SUBMIT</button> </div>
	</div>
	<div class="mob-close" data-dismiss="modal">
		<img src="/themes/{{ $shop_theme }}/img/mob-close.png">
	</div>
</form>
<style type="text/css">
	.events-popup .modal-content-global
	{
		border-radius: 0 !important;
	}
	.events-popup .modal-body
	{
		padding-top: 0 !important;
		border-radius: 6px;
		overflow: hidden;
	}
	.events-popup .error-message-content
	{
		color: red;
		text-align: center;
		border: 1px solid #B5A195;
		font-size: 13px;
		font-weight: 400 !important;
	}
	.events-popup .top-head
	{
		background-color: #593620 !important;
		margin-bottom: 15px;
	}
	.events-popup .header-logo
	{
		text-align: center;
	}
	.events-popup .head-title
	{
		text-align: center;
		margin-top: 20px;
	}
	.events-popup h1
	{
		font-size: 24px;
		font-weight: 500;
		color: #fff;
		margin: 0;
		padding-bottom: 10px;
	}
	.events-popup h2
	{
		font-size: 13px;
		font-weight: 400;
		color: #fff;
		margin: 0;
		padding-bottom: 10px;
	}
	.events-popup h3
	{
		font-size: 15px;
		font-weight: 400;
		color: #e5d8b5;
		margin: 0;
		padding-bottom: 10px;
	}
	.events-popup .form-control
	{
		border-radius: 0 !important;
		border: 1px solid #b5a195 !important;
		font-weight: 300;
	}
	.events-popup .h-logo
	{
		width: 100%;
	}
	.events-popup .reg-button
	{
		background-color: #593620;
		padding: 10px 0;
		text-align: center;
		width: 100%;
		color: #fff;
		font-weight: 400;
		border: none;
	}
	.events-popup .reg-button:hover
	{
		cursor: pointer;
		background-color: #603a23 !important;
	}
	.events-popup .mob-close
		{
			position: absolute;
			top: 0;
			right: 0;
		}
	@media screen and (max-width: 991px)
	{
		.events-popup .primary-fill
		{
			margin-bottom: 12px;
		}
		.events-popup .mob-close
		{
			position: absolute;
			top: 5px;
			right: 3px;
		}
		.events-popup .h-logo
		{
			width: 50% !important;
		}
		.events-popup .head-title
		{
			padding-bottom: 20px !important;
		}
	}	
</style>

<script type="text/javascript">
	$('.reserved-submit').unbind('click');
	$('.reserved-submit').bind('click', function(e)
	{
		$(e.currentTarget).css('opacity',0.3);
		$(e.currentTarget).html('Reserving...');
		$('.events-popup').submit();
	});
	function success_reserve(data)
	{
		if(data.status == 'success')
		{
		    toastr.success('Success');
		    setInterval(function()
		    {
		    	location.reload();
		    },2000);
		}
		if(data.status == 'error_status')
		{
			$('.reserved-submit').css('opacity',1);
			$('.reserved-submit').html('SUBMIT');
			$('.error-message-content').removeClass('hidden');
			$('.error-message-content').html(data.status_message);
		}
	}
</script>


















