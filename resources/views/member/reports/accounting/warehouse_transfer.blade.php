@extends('member.layout')

@section('content')
{!! $head !!}
@include('member.reports.filter.filter1')
@include('member.reports.output.warehouse_transfer')
@endsection

@section('script')
<script type="text/javascript">

	var warehouse_transfer_report = new warehouse_transfer_report();

	function warehouse_transfer_report()
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
			var serialize_data = $("form.filter").serialize()
			
			$(".load-data").load("/member/report/warehouse/transfer?"+serialize_data+"&load_view=true .load-content", function()
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