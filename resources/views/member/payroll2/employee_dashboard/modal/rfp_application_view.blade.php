<style type="text/css">
.row
{
	margin-top: 10px;
}
</style>
<div class="modal-header">
    <h5 class="modal-title">View Request Payment</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

</div>
<div class="modal-body form-horizontal">
	<div class="row">
		<div class='col-md-6'>
			<small for='request_payment_name'>Request Payment Name</small>
			<input type="text" class="form-control" name="request_payment_name" id='request_payment_name' value="{{$request_payment_info->payroll_request_payment_name}}" disabled>
		</div>
		<div class='col-md-6'>
			<small for='request_payment_date'>Requested Date</small>
			<input type="text" class="form-control" name="request_payment_date" id='request_payment_date' value="{{date('F d, Y', strtotime($request_payment_info->payroll_request_payment_date))}}" disabled>
		</div>
	</div>
	<div class="table list-payment">
		<table class="table table-condensed">
			<thead>
				<th class="text-center" style="width: 75%;">Description</th>
				<th class="text-center" style="width: 25%;">Amount</th>
			</thead>
			<tbody id='dynamic-field' class="dynamic-field">
				@foreach($_request_payment_sub_info as $request_payment_sub_info)
				<tr>
					<td class="text-center"><input type="text" id="request_payment_description" step="any" class="form-control" value="{{$request_payment_sub_info->payroll_request_payment_sub_description}}" disabled></td>
					<td class="text-center amount-td"><input type="number" id="request_payment_amount" step="any" class="form-control request_payment_amount" value="{{$request_payment_sub_info->payroll_request_payment_sub_amount}}" disabled></td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<hr>
	<div class="row">
		<div class="col-sm-6">
	
		</div>
		<div class="col-sm-6 text-right" =>
			<h5 style="display: inline-block;  margin-right: 10px;">Total Amount:</h5>
			<h5 style="display: inline-block; margin-right: 20px;" class="total-amount">{{$request_payment_info->payroll_request_payment_total_amount}}</h5>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<small for="request_payment_remark">Remark</small>
			<textarea id="request_payment_remark"  name="request_payment_remark" class="form-control" disabled>{{$request_payment_info->payroll_request_payment_remark}}</textarea>
		</div>
	</div>
    <div class="row">
    	<div class="col-sm-12">
    		<small for="approver_group">Approver Group Name</small>
	    	<input type="text" name="approver_group" id="approver_group" value="{{$approver_group_info->payroll_approver_group_name}}" class="form-control" disabled>
    	</div>
    </div> 
    <div class="row">
        <div class="col-sm-12">
        <small>Approver Group List</small>
        	@foreach($_group_approver as $level => $group_approver)
			<ul style="list-style-type: none;">
				<li>Level {{$level}} Approver/s
					<ul>
						@foreach($group_approver as $key => $employee_approver)
						<li>
							{{$employee_approver->payroll_employee_first_name}} {{$employee_approver->payroll_employee_last_name}}
						</li>
						@endforeach
					</ul>
				</li>
			</ul>
        	@endforeach
        </div>
    </div>
</div>
<div class="modal-footer">
	<button type="button" data-dismiss="modal" class="btn btn-primary btn-md">Exit</button>
</div>