@extends('member.layout')

@section('content')
{!! $head !!}
@include('member.reports.filter.filter1')
@include('member.reports.sales_representative.sales_representative_output')
@endsection

@section('script')	
<script type="text/javascript">

	var sales_rep_report = new sales_rep_report();

	function sales_rep_report()
	{
		init();

		function init()
		{
			event_run_report_click();
			action_collaptible(false);
		}

		function event_run_report_click()
		{
			$(document).on("click", ".run-report", function()
			{
				var serialize_data = $("form.filter").serialize()
				
				$(".load-data").load("/member/report/sales_representative?"+serialize_data+"&load_view=true .load-content", function()
				{
					action_collaptible(false);
				});
			});
		}
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