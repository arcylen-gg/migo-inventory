@extends('member.layout')

@section('content')
{!! $head !!}
@include('member.reports.filter.filtercustomerlist')
@include('member.reports.output.customer_list')
@endsection

@section('script')
<script type="text/javascript">

	var customer_list_report = new customer_list_report();

	function customer_list_report()
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
			
			$(".load-data").load("/member/report/accounting/customer_list?"+serialize_data+"&load_view=true .load-content", function()
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