@if($item_code != null)
	@foreach($item_code as $key => $value)
	<table class="table table-condensed">
		<tr>
			<td>
				<div class="col-md-6">
					<div class="col-md-12">
						Details
						<div class="col-md-12">Activation Code :{{$value->item_activation_code}}</div>
					</div>

					<div class="col-md-12">
						<div class="col-md-6">Customer Email :{{$value->item_code_customer_email}}</div>
					</div>

					<div class="col-md-12">
						Item Info
						<div class="col-md-12">
							Name : {{$value->item_name}}
						</div>
						<div class="col-md-12">
							Payment Status : {{$value->item_code_paid == 1 ? "Paid" : "Unpaid" }}
						</div>
						<div class="col-md-12">
							Item Status : {{$value->item_code_product_issued == 1 ? "Issued" : "Not Yet Issued" }}
						</div>
					</div>
				</div>

			</td>
		</tr>
	</table>
	@endforeach
@else
	<center><h2>Invalid Product Code</h2></center>
@endif