<style type="text/css">
.row
{
	margin-top: 10px;
}
</style>
<form action="/modal_rfp_save" method="post" class="global-submit">
<div class="modal-header">
    <h5 class="modal-title">RFP Application</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
</div>
<div class="modal-body form-horizontal">
	<div class="row">
		<div class='col-md-6'>
			<small for='request_payment_name'>Request Payment Name</small>
			<input type="text" class="form-control" name="request_payment_name" id='request_payment_name' required>
		</div>
		<div class='col-md-6'>
			<small for='request_payment_date'>Requested Date</small>
			<input type="date" class="form-control" name="request_payment_date" id='request_payment_date' required>
		</div>
	</div>
	<div class="table list-payment">
		<table class="table table-condensed">
			<thead>
				<th class="text-center" style="width: 75%;">Description</th>
				<th class="text-center" style="width: 20%;">Amount</th>
				<th class="text-center" style="width: 5%;"></th>
			</thead>
			<tbody id='dynamic-field' class="dynamic-field">
				<tr>
					<td class="text-center"><input type="text" name="request_payment_description[]" id="request_payment_description" step="any" placeholder="Enter Description" class="form-control" required></td>
					<td class="text-center amount-td"><input type="number" name="request_payment_amount[]" id="request_payment_amount" step="any" placeholder="Amount" class="form-control request_payment_amount" required></td>
					<td class="text-center"></td>
				</tr>
			</tbody>
		</table>
	</div>
	<hr>
	<div class="row">
		<div class="col-sm-6">
			<button type="button" class="btn btn-primary btn-md add-category"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Add Category</button>
		</div>
		<div class="col-sm-6 text-right" =>
			<h5 style="display: inline-block;  margin-right: 10px;">Total Amount:</h5>
			<h5 style="display: inline-block; margin-right: 50px;" class="total-amount">0.00</h5>
			<input type="hidden" name="request_payment_total_amount" class="request_payment_total_amount" val="">
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<small for="request_payment_remark">Remark</small>
			<textarea id="request_payment_remark"  name="request_payment_remark" class="form-control" placeholder="Enter Remark"></textarea>
		</div>
	</div>
    <div class="row">
    	<div class="col-sm-12">
    		<small for="approver_group">Select Group Approver</small>
	    	<select class="form-control approver_group" id="approver_group" name="approver_group" required>
	    		<option value=""> Select Group Approver </option>
	    		@foreach($_group_approver as $group_approver)
	    		<option value="{{ $group_approver->payroll_approver_group_id }}"> {{ $group_approver->payroll_approver_group_name }} </option>
	    		@endforeach
	    	</select>
    	</div>
    </div> 
    
    <div class="row">
    	<div class="col-sm-12 approver_group_list">
    		
    	</div>
    </div>   
</div>
<div class="modal-footer">
	<button type="submit" class="btn btn-primary btn-md">Save</button>
	<button type="button" data-dismiss="modal" class="btn btn-primary btn-md">Exit</button>
</div>
</form>

<script type="text/javascript" src="/assets/employee/js/modal_rfp_application.js?v=2"></script>
<script type="text/javascript">
	function reload(data)
	{
		data.element.modal("hide");
		location.reload();
	}
</script>
