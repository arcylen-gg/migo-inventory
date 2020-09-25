@extends('member.layout')

@section('content')
{!! $head !!}
@include('member.reports.filter.filter1')
@include('member.reports.top_customer.list')
@endsection

@section('script')
<script type="text/javascript">

	var top_customer = new top_customer();

	function top_customer()
	{
		init();

		function init()
		{
			event_run_report_click();
		}
	}


	function event_run_report_click()
	{
		$(document).on("click", ".run-report", function()
		{
			var serialize_data = $("form.filter").serialize();
			
			$(".load-data").load("/member/report/top_customer?"+serialize_data+"&load_view=true .load-content");
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