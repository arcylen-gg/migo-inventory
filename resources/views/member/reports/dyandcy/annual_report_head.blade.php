@extends('member.layout')

@section('content')
{!! $head !!}
@include('member.reports.filter.exportonly')
@include('member.reports.dyandcy.annual_report_output')
@endsection

@section('script')
<script type="text/javascript">

	var annualreport = new annualreport();

	function annualreport()
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
			
			$(".load-data").load("/member/report/dyandcy/annualreport?"+serialize_data+"&load_view=true .load-content");
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