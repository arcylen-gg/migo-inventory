@extends('member.layout')

@section('content')
{!! $head !!}
@include('member.reports.filter.exportonly')
@include('member.reports.output.warehouse_transfer_detailed')
@endsection

@section('script')
<script type="text/javascript">

	var warehouse_transfer_detailed_report = new warehouse_transfer_detailed_report();

	function warehouse_transfer_detailed_report()
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
			var wis_id = $(".wis").val();	
			$(".load-data").load("/member/report/warehouse/transfer/detailed/"+$wis_id+"?"+serialize_data+"&load_view=true .load-content", function()
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