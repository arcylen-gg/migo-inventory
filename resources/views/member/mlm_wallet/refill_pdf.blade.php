
<form  action="/member/mlm/wallet/refill/process/submit" method="post">
{!! csrf_field() !!}
<input type="hidden" name="wallet_log_refill_id" value="{{$request->wallet_log_refill_id}}">
<div class="panel panel-default panel-block panel-title-block panel-gray col-md-12">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="panel-heading">
            {!! $customer_view !!}
            <hr>
            <div class="col-md-12">
            	<label>Date Requested</label>
            	<input type="datetime" class="form-control" name="wallet_log_refill_date"  value="{{$request->wallet_log_refill_date}}" readonly>
            </div>
            <div class="col-md-12">
            	<label>Amount Paid</label>
            	<input type="number" class="form-control" name="wallet_log_refill_amount_paid" value="{{$request->wallet_log_refill_amount_paid}}" readonly>
            </div>
            <div class="col-md-12">
            	<label>Processing Fee</label>
            	<input type="number" class="form-control" name="wallet_log_refill_processing_fee" value="{{$request->wallet_log_refill_processing_fee}}" readonly>
            </div>
            <div class="col-md-12">
            	<label>Accepted Amount</label>
            	<input type="number" class="form-control" name="wallet_log_refill_amount" value="{{$request->wallet_log_refill_amount_paid - $request->wallet_log_refill_processing_fee}}" @if($request->wallet_log_refill_approved != 0) readonly @endif>
            </div>
            <div class="col-md-12">
            	<label>Customer Remarks</label>
            	<textarea class="form-control" name="wallet_log_refill_remarks" readonly>{{$request->wallet_log_refill_remarks}}
            	</textarea>
            </div>
            <div class="col-md-12">
            	<hr>
            	<label>Admin Remarks</label>
            	<textarea class="form-control" name="wallet_log_refill_remarks_admin" @if($request->wallet_log_refill_approved != 0) readonly @endif >{{$request->wallet_log_refill_remarks_admin}}</textarea>
            </div>
            <div class="col-md-12">
            	<label>Status</label>
            	@if($request->wallet_log_refill_approved == 0)
            	<input type="text" class="form-control" value="Pending" readonly>
            	@elseif($request->wallet_log_refill_approved == 1)
            	<input type="text" class="form-control" value="Processed" readonly>
            	@elseif($request->wallet_log_refill_approved == 2)
            	<input type="text" class="form-control" value="Denied" readonly>
            	@endif
            </div>
        </div>
    </div>
</div>  
</div>   
<div class="panel panel-default panel-block panel-title-block panel-gray col-md-12">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="panel-heading">
            <center>Proof of payment</center>
            @if($request->wallet_log_refill_attachment != null)
            <img src="{{public_path(). '/' . $request->wallet_log_refill_attachment}}" width="100%">
            @else
            <center>No Attachment</center>
            @endif
            <hr>
            </div>
        </div>
    </div>
</div>     
</form>       
