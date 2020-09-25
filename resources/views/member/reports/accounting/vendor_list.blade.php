@extends('member.layout')

@section('content')
{!! $head !!}
@include('member.reports.filter.filter7')
@include('member.reports.output.vendor_list')
@endsection

@section('script')
<script type="text/javascript">

	var vendor_list_report = new vendor_list_report();

	function vendor_list_report()
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
			
			$(".load-data").load("/member/report/accounting/vendor_list?"+serialize_data+"&load_view=true .load-content", function()
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