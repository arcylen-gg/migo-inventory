@extends('member.layout')
@section('css')
@endsection
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
	<div class="panel-heading">
		<div>
			<i class="fa fa-building-o"></i>
			<h1>
			<span class="page-title">{{$account->account_name}}</span>
			<small>

			</small>
			</h1>
		</div>
	</div>
</div>
<!-- <div class="date-holder">
	<div class="row">
		<div class="col-md-2">
			<select class="form-control input-sm" name="report_period">
				<option value="all">All Dates</option>
				<option value="custom">Custom</option>
				<option value="today">Today</option>
				<option value="this_week">This Week</option>
				<option value="this_week_to_date">This Week to Date</option>
				<option value="this_month">This Month</option>
				<option value="this_month_to_date">This Month to Date</option>
				<option value="this_year">This Year</option>
			</select>
		</div>
		<div class="col-md-2">
			<input name="start_date" class="form-control input-sm datepicker">
		</div>
		<div class="col-md-2">
			<input name="end_date" class="form-control input-sm datepicker">
		</div>
		<div class="col-md-1">
			<button class="btn btn-custom-primary btn-sm btn-generate">Generate</button>
		</div>
	</div>
	</br> -->
</div>
<div class="">
	<div class="table-responsive load-data">
		<table class="table table-striped table-condensed table-hovered">
			<thead>
				<tr>
					<th>Date</th>
					<th>Transaction Type</th>
					<th>Num</th> 
					<th>Memo/Description</th>
					<th>Account Name</th>
					<th>Amount</th>
					<th>Balance</th>
				</tr>
			</thead>
			<tbody>
				<?php $balance = 0; ?>
				@foreach($_journal as $key=>$entry)
				<tr>
					<td>{{dateFormat($entry->je_entry_date)}}</td>
					<td>{{$entry->je_reference_module}}</td>
					<td>
						<a href="{{$entry->txn_link}}">
							{{$entry->je_reference_id}}
						</a>
					</td>
					<td>{{$entry->jline_description}}</td>
					<td>{{$entry->account_name}}</td>
					<td>{{strToUpper($entry->normal_balance) == strToUpper($entry->jline_type) ? currency('PHP', $entry->jline_amount) : currency('PHP', -$entry->jline_amount)}}</td>
					<?php $balance += strToUpper($entry->normal_balance) == strToUpper($entry->jline_type) ? $entry->jline_amount : -$entry->jline_amount ?>
					<td>{{currency('PHP', $balance)}}</td>
				</tr>
				@endforeach
				<tr>
					<td>TOTAL</td>
					<td colspan="5"></td>
					<td><b>{{currency('PHP', $balance)}}</b></td>
				</tr>
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
		// event_date_change();
		event_generate_click();
	}

	function event_date_change()
	{
		$(document).on('change', '.start-date, .end-date', function(event) {
			event.preventDefault();
			$(".load-data").load("/member/payroll/payroll_summary .load-data table", function()
			{
				toastr.success("Generated");
			})
			/* Act on the event */
		});
	}

	function event_generate_click()
	{
		$(document).on("click",".btn-generate", function()
		{
			$period_date = $(this).parents(".date-holder").find("select").val();
			$start_date  = $(this).parents(".date-holder").find("input[name=start_date]").val();
			$end_date	 = $(this).parents(".date-holder").find("input[name=end_date]").val();

			$(".load-data").load("/member/accounting/journal/all-entry-by-account/{{$account->account_id}}?period_date="+$period_date+"&&start_date="+$start_date+"&&end_date="+$end_date +" table", function()
			{
				toastr.success("Generated");
			})
		})
	}
}

</script>
@endsection