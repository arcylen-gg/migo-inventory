@extends('member.layout')

@section('content')
{!! $head !!}
@include('member.reports.filter.filter1')
<input type="hidden" name="" id="account_id" value="{{$account_id or ''}}">
@include('member.reports.output.quick_report_account')
@endsection
@section('script')	
<script type="text/javascript">

	var quick_report = new quick_report();

	function quick_report()
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
				var account_id = $("#account_id").val();
				$(".load-data").load("/member/accounting/journal/all-entry-by-account/"+account_id+"?"+serialize_data+"&load_view=true .load-content", function()
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