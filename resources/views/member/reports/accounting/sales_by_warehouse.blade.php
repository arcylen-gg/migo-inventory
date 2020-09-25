@extends('member.layout')

@section('css')
<link rel="stylesheet" href="/assets/member/css/customBTN.css" type="text/css" />
@endsection

@section('content')
{!! $head !!}
@include('member.reports.filter.filter2')
{!! $field_checker !!}

<div class="append_report_a">{!! $output !!}</div>

@endsection

@section('script')
<script type="text/javascript" src="/assets/mlm/jquery.aCollapTable.min.js"></script>  		
<script type="text/javascript">
var link = '/member/report/accounting/sale/get/report';
action_collaptible();
function submit_done (data) {
	// body...
	switch(data.status)
	{
		case 'success_plain' :
		$('.append_report_a').html(data.view);
		action_collaptible();
		break
	}
		
}
</script>
@endsection