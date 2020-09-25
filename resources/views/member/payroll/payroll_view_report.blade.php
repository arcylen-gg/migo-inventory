@extends('member.layout')
@section('css')
@endsection
@section('content')
<form action="/member/payroll/payroll_reports/download_excel_report" method="GET">
<div class="panel panel-default panel-block panel-title-block" id="top">
	<div class="panel-heading">
		<div>
			<i class="fa fa-bar-chart" aria-hidden="true"></i>
			<h1>
			<span class="page-title">{{$data['report']->payroll_reports_name}}</span>
			<small>
			Manage Payroll Reports
			</small>
			</h1>
			<button class="btn btm-custom-green pull-right" type="submit"><i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;Download as Excel</button>
			<input type="hidden" name="_token" class="_token" value="{{csrf_token()}}">
			<input type="hidden" name="payroll_reports_id" class="payroll_reports_id" value="{{$data['report']->payroll_reports_id}}">
		</div>
	</div>
</div>
<div class="panel panel-default panel-block">
	<div class="panel-body form-horizontal">
		<div class="form-group">
			<div class="col-md-4 pull-right">
				<div class="col-md-6">
					<small>From</small>
					<input type="text" name="start" value="{{$data['start']}}" class="form-control date-start date-change datepicker">
				</div>
				<div class="col-md-6">
					<small>To</small>
					<input type="text" name="end" value="{{$data['end']}}" class="form-control date-end date-change datepicker">
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 over-flow table-reports">
				<table class="table table-condensed table-bordered column-fit">
					<thead>
						<tr>
							@foreach($header as $head)
							<th class="text-center" colspan="{{$head['count']}}"><b>{{$head['name']}}</b></th>
							@endforeach
						</tr>
						<tr>
							<th></th>
							@foreach($data['_columns'] as $column)
							<th class="block">{{$column}}</th>
							@endforeach
						</tr>
					</thead>
					<tbody>
						@foreach($data['_emp'] as $emp)
						<tr>
							<td>{!!$emp['name']!!}</td>
							@foreach($emp['_record'] as $record)
							<td class="text-right">
								{!!$record!!}
							</td>
							@endforeach
						</tr>
						
						@endforeach
						<tr>
							<td><b>Total</b></td>
							@foreach($data['_total'] as $total)
							<td class="text-right">
								<b>{{number_format($total, 2)}}</b>
							</td>
							@endforeach
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</form>
@endsection
@section('script')
<script type="text/javascript">
	$(".date-change").unbind('change');
	$(".date-change").bind('change', function(){
		var start = $('.date-start').val();
		var end = $('.date-end').val();
		var _token = $('._token').val();
		var payroll_reports_id = $('.payroll_reports_id').val();

		var loading = '<div class="loader-16-gray"></div>';

		$(".table-reports").html(loading);
		
		$.ajax({
			url 	: 	'/member/payroll/payroll_reports/date_change_report',
			type 	: 	'POST',
			data 	: 	{
				_token:_token,
				start:start,
				end:end,
				payroll_reports_id:payroll_reports_id
			},
			success	: 	function(result)
			{
				$('.table-reports').html(result);
			},
			error 	: 	function(err)
			{
				toastr.error('Error, something went wrong.');
			}
		});
	});
</script>
@endsection