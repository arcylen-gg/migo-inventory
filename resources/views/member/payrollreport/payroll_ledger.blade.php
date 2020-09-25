@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Payroll Reports &raquo; Payroll Ledger</span>
                <small>
               	select employee
                </small>
            </h1>
        </div>
    </div>
</div>
<div class="form-group order-tags load-data" target="value-id-1"></div>
    <div class="clearfix">
        <div class="col-md-12">
            <div class="table-responsive" id="value-id-1">
				<table class="table table-bordered table-striped table-condensed">
				        <thead style="text-transform: uppercase">
				            <tr>
				                <th class="text-center" width="50px"><input type="checkbox" name=""></th>
				                <th class="text-center" width="100px">NO.</th>
				                <th class="text-center">Employee Name</th>
				                <th class="text-center" width="150px">Company</th>
				                <th class="text-center" ></th>
				            </tr>
				        </thead>
				        <tbody>
				           	@foreach($_employee as $employee)
				           		<tr>
				           			<td class="text-center"><input type="checkbox" name=""></td>
				           			<td class="text-center">{{ $employee->payroll_employee_number }}</td>
				           			<td class="text-center">{{$employee->payroll_employee_first_name}} {{$employee->payroll_employee_last_name}}</td>
				           			<td class="text-center">{{ $employee->payroll_company_name }}</td>
				           			<td class="text-center"><a href="/member/payroll/reports/payroll_ledger/{{$employee->payroll_employee_id}}">View Employee</a></td>
				           		</tr>
				           	@endforeach
				        </tbody>
				</table>
      		</div>
        </div>
    </div>

@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
@endsection
