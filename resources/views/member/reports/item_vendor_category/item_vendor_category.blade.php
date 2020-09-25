@extends('member.layout')

@section('content')
{!! $head !!}
@include('member.reports.filter.filter8')
@include('member.reports.output.item_vendor_category')
@endsection

@section('script')
<script type="text/javascript">

	var item_vendor_category = new item_vendor_category();

	function item_vendor_category()
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
			
			$(".load-data").load("/member/report/accounting/purchase/item_vendor_category?"+serialize_data+"&load_view=true .load-content", function()
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
	$(".select-item").globalDropList({
	      hasPopup : "false",
	      width : "100%",
	      maxHeight: "309px"
	});
</script>
@endsection