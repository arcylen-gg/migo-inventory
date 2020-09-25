<div class="">
	@if(isset($encashment_process))
		@if($encashment_process != null)
			@if($log != null)
				@if(isset($customer_view))
					{!! $customer_view !!}
				@endif
				<table class="table table-condensed">
					<tr>
						<td>Date</td>
						<td>Complan</td>
						<td>Amount</td>
					</tr>
					
					@foreach($log as $key => $value)
					<tr>
						<td>{{$value->wallet_log_date_created}}</td>
						<td>{{$value->wallet_log_plan}}</td>
						<td>{{$value->wallet_log_amount}}</td>
					</tr>
					@endforeach

					@if(isset($log_final))
					<tr>
						<td colspan="40">
							<hr>
						</td>
					</tr>
					<tr>
						<td>{{$log_final->wallet_log_date_created}}</td>
						<td>{{$log_final->wallet_log_plan}}</td>
						<td>{{$log_final->wallet_log_amount}}</td>
					</tr>

					<tr>
						<td colspan="40">
							<hr>
						</td>
					</tr>
					<?php 
                        $value2 = $log_final->wallet_log_amount * (-1);
                        $p_fee_type = $encashment_process->enchasment_process_p_fee_type;
                        $p_fee = $encashment_process->enchasment_process_p_fee;
                        $tax_p =    $encashment_process->enchasment_process_tax_type;
                        $tax = $encashment_process->enchasment_process_tax;
                        if($p_fee_type == 0)
                        {
                            $value2 = $value2 - $p_fee;
                        }
                        else
                        {
                            $p_fee = ($value2 * $p_fee)/100;
                            $value2 = $value2 - $p_fee;
                        }

                        if($tax_p == 0)
                        {
                            $value2 = $value2 - $tax;
                        }
                        else
                        {
                            $tax = ($value2 * $tax)/100;
                            $value2 = $value2-$tax;
                        }
                    ?>
                    <tr>
						<td></td>
						<td>
							<span class="pull-right">
								Subtotal
							</span>
						</td>
						<td>{{$log_final->wallet_log_amount * (-1)}}</td>
					</tr>
                    <tr>
						<td></td>
						<td>
							<span class="pull-right">
								Processing Fee
							</span>
						</td>
						<td>{{$p_fee}}</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<span class="pull-right">
								Tax
							</span>
						</td>
						<td>{{$tax}}</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<span class="pull-right">
								Total
							</span>
						</td>
						<td>{{$value2}}</td>
					</tr>
					@if(isset($encashment_details->encashment_process))
					<tr>
						<td colspan="40"><center>Encashment Details</center></td>
					</tr>
						@if($encashment_details->encashment_type == 0)
						<tr>
							<td>Payment Type: </td>
							<td>Bank Deposit</td>
							<td></td>
						</tr>
						<tr>
							<td>Bank:</td>
							<td>{{$encashment_details->bank_name}}</td>
							<td></td>
						</tr>
						<tr>
							<td>Branch:</td>
							<td>{{$encashment_details->bank_account_branch}}</td>
							<td></td>
						</tr>
						<tr>
							<td>Name: </td>
							<td>{{$encashment_details->bank_account_name}}</td>
							<td></td>
						</tr>
						<tr>
							<td>Account Number: </td>
							<td>{{$encashment_details->bank_account_number}}</td>
							<td></td>
						</tr>
						@elseif($encashment_details->encashment_type == 1)
						<tr>
							<td>Payment Type: </td>
							<td>Cheque</td>
							<td></td>
						</tr>
						<tr>
							<td>Name on cheque:</td>
							<td>{{$encashment_details->cheque_name}}</td>
							<td></td>
						</tr>
						@endif
					@endif
					<tr>
						<td colspan="40">
							<hr>
						</td>
					</tr>
					<tr>
						<td colspan="40">
							<center> --- End Transaction ---</center>
						</td>
					</tr>
					@if($log_final->encashment_process_type == 0)
					
					@else
					<tr>
						<td colspan="40">
							<label>Status</label>
							<input type="text" class="form-control" value="Processed" readonly>
						</td>
					</tr>
					@endif

					@endif
				</table>


			@endif
		@endif
	@endif	
	<hr>

</div>