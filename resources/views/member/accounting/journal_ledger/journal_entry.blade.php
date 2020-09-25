@extends('member.layout')
@section('css')
@endsection
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
	<div class="panel-heading">
		<div>
			<i class="fa fa-building-o"></i>
			<h1>
			<span class="page-title">Journal Entry</span>
			<small>

			</small>
			</h1>
			<!-- <div class="pull-right">
				<input type="text" class="datepicker form-control end-date" name="end_date" placeholder="End Date" value="" />
			</div> -->
			<!-- <div class="pull-right margin-right-10">
				<input type="text" class="datepicker form-control start-date" name="start_date" placeholder="Start Date" value=""/>
			</div> -->
		</div>
	</div>
</div>
<div class="">
	@if(isset($date_period))
	<div class="date-holder">
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
				<input name="start_date" class="form-control input-sm datepicker" value="">
			</div>
			<div class="col-md-2">
				<input name="end_date" class="form-control input-sm datepicker" value="">
			</div>
			<div class="col-md-1">
				<button class="btn btn-custom-primary btn-sm btn-generate">Generate</button>
			</div>
		</div>
		</br>
	</div>
	@endif
	<div class="table-responsive load-data">
		<table class="table table-striped table-condensed table-hovered">
			<thead>
				<tr>
					<th>Date</th>
					<th>Transaction Type</th>
					<th>Num</th> 
					<th>Name</th>
					<th>Item Name</th>
					<th>Account Name</th>
					<th>Account Type</th>
					<th>Debit</th>
					<th>Credit</th>
				</tr>
			</thead>
			<tbody>
				<?php $debit = 0 ?>
				<?php $credit = 0?>
				@foreach($_journal as $key=>$journal)
					@foreach($journal->entries as $key2=>$entry)
					<tr>
						<td>{{$key2 == 0 ? dateFormat($journal->je_entry_date) : ''}}</td>
						<td>{{$key2 == 0 ? $journal->je_reference_module : ''}}</td>
						<td>
							<a href="{{$journal->txn_link}}">
								{{$key2 == 0 ? $journal->je_reference_id : ''}}
							</a>
						</td>
						<td>{{$key2 == 0 ? $entry->full_name : ''}}</td>
						<td>{{$entry->item_name or ''}}</td>
						<td>{{$entry->account_name}}</td>
						<td>{{$entry->chart_type_name}}</td>
						<td>{{$entry->jline_type == 'Debit' ? currency('PHP', $entry->jline_amount) : ''}}</td>
						<td>{{$entry->jline_type == 'Credit' ? currency('PHP', $entry->jline_amount) : ''}}</td>
					</tr>
					@endforeach
					<tr>
						<?php $debit  +=  collect($journal->entries)->where("jline_type", 'Debit')->sum('jline_amount') ?>
						<?php $credit +=  collect($journal->entries)->where("jline_type", 'Credit')->sum('jline_amount') ?>
						<td colspan="7"></td>
						<td><b>{{currency('PHP', collect($journal->entries)->where("jline_type", 'Debit')->sum('jline_amount'))}}</b></td>
						<td><b>{{currency('PHP', collect($journal->entries)->where("jline_type", 'Credit')->sum('jline_amount'))}}</b></td>
					</tr>
				@endforeach
				<tr>
					<td><b>TOTAL</b></td>
					<td colspan="6"></td>
					<td><b>{{currency('PHP', $debit)}}</b></td>
					<td><b>{{currency('PHP', $credit)}}</b></td>
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

			$(".load-data").load("/member/accounting/journal/all-entry?period_date="+$period_date+"&&start_date="+$start_date+"&&end_date="+$end_date +" table", function()
			{
				toastr.success("Generated");
			})
		})
	}
}

</script>
@endsection