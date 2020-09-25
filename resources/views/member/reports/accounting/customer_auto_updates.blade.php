@extends('member.layout')

@section('content')
{!! $head !!}
@include('member.reports.filter.filtercustomerlist')
@include('member.reports.output.customer_auto_updates')
@endsection

@section('script')
<script type="text/javascript">

	var customer_auto_updates_report = new customer_auto_updates_report();

	function customer_auto_updates_report()
	{
		init();

		function init()
		{
			event_run_report_click();
			action_collaptible(true);
		}
	}

	function event_run_report_click()
	{
		$(document).on("click", ".run-report", function()
		{
			var serialize_data = $("form.filter").serialize();
			
			$(".load-data").load("/member/report/customer_auto_updates?"+serialize_data+"&load_view=true .load-content", function()
				{
					action_collaptible(true);
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

</script>
@endsection