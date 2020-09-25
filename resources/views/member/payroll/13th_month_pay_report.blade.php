@extends('member.layout')
@section('css')
@endsection
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
	<div class="panel-heading">
		<div>
			<i class="fa fa-building-o"></i>
			<h1>
			<span class="page-title">13th Month Pay Report</span>
			<small>
			Payroll
			</small>
			</h1>
			<div class="pull-right">
				<input type="text" class="datepicker form-control end-date" name="end_date" placeholder="End Date" value="{{$date_end}}" />
			</div>
			<div class="pull-right margin-right-10">
				<input type="text" class="datepicker form-control start-date" name="start_date" placeholder="Start Date" value="{{$date_start}}"/>
			</div>
		</div>
	</div>
</div>
<div class="">
	<div class="table-responsive load-data">
		<table class="table table-striped table-condensed">
			<thead>
				<tr>
					<th>Employee Name</th>
					<th>Department</th>
					<th>Payroll Period</th>
					<th>Basic Salary</th>
					<th>13 Month</th>
					<th>Sub Total</th>
				</tr>
			</thead>
			<tbody>
				{{-- @foreach($_record as $record)
					@if($record['total'] > 0)
						<tr>
							<td>{{$record['account_number']}}</td>
							<td>{{$record['account_name']}}</td>
							<td>{{$record['chart_type_name']}}</td>
							<td>{{$record['normal_balance'] == 'debit' ? currency('PHP', $record['total']) : ''}}</td>
							<td>{{$record['normal_balance'] == 'credit' ? currency('PHP', $record['total']) : ''}}</td>
						</tr>
					@endif
				@endforeach
				<tr>
					<td colspan="3"></td>
					<td>{{currency('PHP', collect($_record)->where('normal_balance', 'debit')->sum('total'))}}</td>
					<td>{{currency('PHP', collect($_record)->where('normal_balance', 'debit')->sum('total'))}}</td>
				</tr> --}}
			</tbody>
		</table>
	</div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
<script>

var payroll_journal_entry = new payroll_journal_entry();

function payroll_journal_entry()
{
	init();

	function init()
	{
		document_ready();
	}

	function document_ready()
	{
		event_date_change();
	}

	function event_date_change()
	{
		$(document).on('change', '.start-date, .end-date', function(event) {
			event.preventDefault();
			var start 	= $(".start-date").val();
			var end 	= $(".end-date").val();
			$(".load-data").load("/member/payroll/payroll_summary?start=" +start +"&&end=" +end +" .load-data table", function()
			{
				toastr.success("Generated");
			})
			/* Act on the event */
		});
	}
}

</script>
@endsection