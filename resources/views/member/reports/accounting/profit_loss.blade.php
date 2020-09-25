@extends('member.layout')

@section('css')
<link rel="stylesheet" href="/assets/member/css/customBTN.css" type="text/css" />
@endsection

@section('content')
{!! $head !!}
@include('member.reports.filter.filter1')
@include('member.reports.output.profit_loss')
@endsection

@section('script')
<script type="text/javascript">

	var profit_loss_report = new profit_loss_report();

	function profit_loss_report()
	{
		init();

		function init()
		{
			event_run_report_click();
			action_collaptible(false);
		}
	}

	function event_run_report_click()
	{
		$(document).on("click", ".run-report", function()
		{
			var serialize_data = $("form.filter").serialize()
			
			$(".load-data").load("/member/report/accounting/profit_loss?"+serialize_data+"&load_view=true .load-content", function()
				{
					action_collaptible(false);
				});
		});
	}

	function submit_done(data)
	{
		if(data.status == 'success_plain')
		{
			toastr.success('Success');
		}
	}

	$(function()
	{
	    $(".wrapper-top-scroll").scroll(function()
	    {
	        $(".wrapper-bottom-scroll").scrollLeft($(".wrapper-top-scroll").scrollLeft());
	    });
	    $(".wrapper-bottom-scroll").scroll(function()
	    {
	        $(".wrapper-top-scroll").scrollLeft($(".wrapper-bottom-scroll").scrollLeft());
	    });
	});


</script>
@endsection