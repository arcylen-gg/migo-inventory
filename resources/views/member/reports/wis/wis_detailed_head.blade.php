@extends('member.layout')

@section('content')
{!! $head !!}
@include('member.reports.filter.exportonly')
@include('member.reports.wis.wis_detailed')
@endsection

@section('script')
<script type="text/javascript">

	var wis_detailed = new wis_detailed();

	function wis_detailed()
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
			var wis_id = $(".wis").val();			
			$(".load-data").load("/member/report/warehouse/wis/detailed/?"+$wis_id+serialize_data+"&load_view=true .load-content");
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