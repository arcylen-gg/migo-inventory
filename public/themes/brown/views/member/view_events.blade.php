<div class="view-events-popup">
	<div class="modal-body row-no-padding clearfix" style="padding: 0; position: relative;">
	<input type="hidden" id="reserve_seat" name="" value="{!! isset($reserve_seat_btn) ? $reserve_seat_btn : '/members/event-reserve?id='.$event->event_id !!}">
		<div class="col-md-8">
			<div class="event-image">
				<img style="width: 100%;" src="{{$event->event_banner_image}}">
			</div>
		</div>
		<div class="col-md-4">
			<div class="event-details">
				<h1>{{ $event->event_title }}</h1>
				<div class="date">
					<span><i class="fa fa-calendar-o" aria-hidden="true"></i></span>&nbsp;&nbsp;<span style="font-weight: 400; color: #585858;">{{strtoupper(date('F d, Y', strtotime($event->event_date)))}}</span>
				</div>
				<p>
					{!! $event->event_description !!}
				</p>
				<a onClick="reserve()">
					<div class="reserve-btn">
						RESERVE A SEAT
					</div>
				</a>
			</div>
		</div>
	</div>
	<div class="modal-footer" style="display: none;">
	<a style="text-decoration: none; color: #fff !important;" onClick="reserve()">
		<div class="reserve-btn-2">
				RESERVE A SEAT
		</div>
	</a>
	</div>
	<div class="mob-close" data-dismiss="modal">
		<img src="/themes/{{ $shop_theme }}/img/mob-close.png">
	</div>
</div>
<script type="text/javascript">
	function reserve()
	{
		var seat = $('#reserve_seat').val();

		$('#global_modal').on('hidden.bs.modal', function () 
		{
		    action_load_link_to_modal(seat, 'md');
		    $('#global_modal').off('hidden.bs.modal');
		})

		$('#global_modal').modal('hide');

		$('.multiple_global_modal').on('hidden.bs.modal', function () 
		{
		    action_load_link_to_modal(seat, 'md');
		    $('.multiple_global_modal').off('hidden.bs.modal');
		})

		$('.multiple_global_modal').modal('hide');
	}
</script>
<style type="text/css">
	.view-events-popup .modal-body
	{
		border-radius: 6px;
		overflow: hidden;
	}
	.view-events-popup .event-details
	{
		padding: 40px 15px 15px 15px;
		text-align: center;
		position: relative;
		color: #404040;
	}
	.view-events-popup h1
	{
		font-size: 24px;
		font-weight: 300;
		margin-top: 50px;
	}
	.view-events-popup p
	{
		font-size: 15px;
		font-weight: 300;
		color: #585858;
		margin-top: 25px;
	}
	.view-events-popup .reserve-btn
	{
		width: 90%;
		background-color: #402a21;
		text-align: center;
		padding: 12px 0;
		color: #fff;
		font-weight: 300;
		position: absolute;
		top: 540px;
	}
	.view-events-popup .reserve-btn-2
	{
		width: 100%;
		background-color: #402a21;
		text-align: center;
		padding: 12px 0;
		color: #fff;
		font-weight: 300;
	}
	.view-events-popup .modal-content-global
	{
		border-radius: 0 !important;
	}
	.view-events-popup .reserve-btn:hover
	{
		cursor: pointer;
		background-color: #493026 !important;
	}
	.view-events-popup .mob-close
	{
		position: absolute;
		top: 0;
		right: 0;
	}
	@media screen and (max-width: 991px)
	{
		.view-events-popup .event-details
		{
			padding: 5px 10px 25px 10px !important;
		}
		.view-events-popup .reserve-btn
		{
			display: none !important;
		}
		.view-events-popup .modal-footer
		{
			display: block !important;
		}
		.view-events-popup .mob-close
		{
			position: absolute;
			top: 5px;
			right: 3px;
		}
	}
</style>