@extends('member.layout')

@section('content')
{!! $head !!}


@include('member.reports.filter.filter1')
@include('member.reports.purchase.po_vendor.po_vendor')
@endsection

@section('script')
<script type="text/javascript">

	var po_vendor = new po_vendor();

	function po_vendor()
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
			
			$(".load-data").load("/member/report/vendor_purchase_order?"+serialize_data+"&load_view=true .load-content", function()
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