<div class="row clearfix draggable-container {{$migo_customization ? '' : 'hidden'}}">
	<div class="table-responsive">
	    <div class="col-sm-12">
			<table class="digima-table" style="width: 100%">
				<thead>
					<tr>
						<th>#</th>
						<th>Payment Method</th>
						<th>Reference No</th>
						<th>Amount</th>
						<th></th>
					</tr>
				</thead>
				<tbody class="applied-pm">
					@include("member.accounting_transaction.customer.sales_receipt.sales_receipt_pm_applied")
				</tbody>
				<tbody class="draggable-pm">
					@if(isset($sales_receipt_pm))
						@foreach($sales_receipt_pm as $key => $_pm)
							<tr class="tr-pm-row">
								<td class="td-pm-id">1</td>
								<td>
								    <select class="form-control drop-down-payment" name="txn_payment_method[]" >
								        @include("member.load_ajax_data.load_payment_method", ['payment_method_id' => $_pm->inv_pm_id])
								    </select>
								</td>
								<td >
						    		<input type="text" class="form-control rcvpymnt-refno" name="txn_ref_no[]" id="rcvpymnt-refno" value="{{$_pm->invoice_reference_num or ''}}" />		
								</td>
								<td>
								    <input type="text" class="form-control  text-right number-input-pm" name="txn_payment_amount[]" id="rcvpymnt-refno" value="{{$_pm->invoice_amount or '0.00'}}" />				
								</td>
								<td class="text-center remove-tr-pm" width="10">
									<i class="fa fa-trash-o" aria-hidden="true"></i>		
								</td>
							</tr>
						@endforeach
					@endif
					<tr class="tr-pm-row">
						<td class="td-pm-id">1</td>
						<td>
						    <select class="form-control drop-down-payment" name="txn_payment_method[]" >
						        @include("member.load_ajax_data.load_payment_method")
						    </select>
						</td>
						<td >
				    		<input type="text" class="form-control rcvpymnt-refno" name="txn_ref_no[]" id="rcvpymnt-refno" value="" />		
						</td>
						<td>
						    <input type="text" class="form-control  text-right number-input-pm" name="txn_payment_amount[]" id="rcvpymnt-refno" value="0.00" />				
						</td>
						<td class="text-center remove-tr-pm" width="10">
							<i class="fa fa-trash-o" aria-hidden="true"></i>		
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="row clearfix  {{$migo_customization ? '' : 'hidden'}} ">
    <div class="col-sm-6">&nbsp;</div>
    <div class="col-sm-6">
        <div class="row">
            <div class="col-md-7 text-right digima-table-label">
            	Total Payment Amount
            </div>
            <div class="col-md-5 text-right digima-table-value">
            	PHP&nbsp;<span class="total-amount-pm">0.00</span>
            </div>
        </div>
    </div>
</div>