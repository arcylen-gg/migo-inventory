@if(isset($_so_pm))
	@foreach($_so_pm as $key => $_pm)
		@foreach($_pm as $keypm => $val)							
			<tr class="tr-pm-row">
				<td class="td-pm-id">1</td>
				<td>
				    <select class="form-control drop-down-payment" name="txn_payment_method[]" >
				        @include("member.load_ajax_data.load_payment_method", ['payment_method_id' => $val->est_pm_id])
				    </select>
				</td>
				<td >
		    		<input type="text" class="form-control rcvpymnt-refno" name="txn_ref_no[]" id="rcvpymnt-refno" value="{{$val->estimate_reference_num or ''}}" />		
				</td>
				<td>
				    <input type="text" class="form-control  text-right number-input-pm" name="txn_payment_amount[]" id="rcvpymnt-refno" value="{{$val->estimate_amount or '0.00'}}" />				
				</td>
				<td class="text-center remove-tr-pm" width="10">
					<i class="fa fa-trash-o" aria-hidden="true"></i>		
				</td>
			</tr>
		@endforeach
	@endforeach
@endif