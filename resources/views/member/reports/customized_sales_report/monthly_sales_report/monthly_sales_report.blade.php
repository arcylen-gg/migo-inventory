@extends('member.layout')

@section('content')
{!! $head !!}
@include('member.reports.filter.filter_one_date_month')
@include('member.reports.output.monthly_sales_report')
@endsection

@section('script')
<script type="text/javascript">

	var monthly_sales_report = new monthly_sales_report();

	function monthly_sales_report()
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

			console.log(serialize_data);
			
			$(".load-data").load("/member/report/monthly_sales_report?"+serialize_data+"&load_view=true .load-content", function()
				{
					monthly_sales_report_output.load_scroll();
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