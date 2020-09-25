@extends('member.layout')

@section('content')
{!! $head !!}
@include('member.reports.filter.filterbinlocation')
@include('member.reports.output.output_bin_location')
@endsection

@section('script')	
<script type="text/javascript">

	var bin_location = new bin_location();

	function bin_location()
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
				
				$(".load-data").load("/member/report/bin_location?"+serialize_data+"&load_view=true .load-content", function()
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

    $('.droplist-sub-warehouse').globalDropList(
    {
    	hasPopup : "false",
        link : "/member/item/v2/warehouse/add",
        width : "100%",
        placeholder : 'Search Bin...',
        onCreateNew : function()
        {
            bin_selected = $(this);
        },
        onChangeValue : function()
        {
            
        }
    });
</script>
@endsection