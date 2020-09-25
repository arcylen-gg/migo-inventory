@if(isset($_po))
	<div style="margin-top: 50px" >
	@foreach($_po as $po)
		<div class="po-bill-count form-group po-style po-{{$po->po_id}}">
			<div>
				<strong>Purchase Order #{{$po->po_id}}</strong>
			</div>
			<div class="{{$y = date('Y') == date('Y',strtotime($po->po_date)) ? '' : date('Y',strtotime($po->po_date))}}" >
				{{date("M d ".$y ,strtotime($po->po_date))}}
			</div>
			<div>
				<strong>{{currency("PHP",$po->po_overall_price)}}</strong>
			</div>
			<div class="row clearfix">
				<div class="col-md-6 col-xs-6">
					<a onclick="add_po_to_bill({{$po->po_id}})">Add</a>
				</div>
				<div class="col-md-6 col-xs-6">
					<a href="/member/vendor/purchase_order?id={{$po->po_id}}">Open</a>
				</div>
			</div>
		</div>
	@endforeach
	</div>
@endif
@if(isset($_bill))
	<div style="margin-top: 50px" class="bill-data">
	@foreach($_bill as $bill)
		<div class="po-bill-count-bill form-group po-style po-{{$bill['bill_id']}}">
			<div>
				<strong>Bill #{{$bill['bill_id']}}</strong>
			</div>
			<div class="{{$y = date('Y') == date('Y',strtotime($bill['bill_date'])) ? '' : date('Y',strtotime($bill['bill_date']))}}" >
				{{date("M d ".$y ,strtotime($bill['bill_date']))}}
			</div>
			<div>
				<strong>{{currency("PHP",$bill['bill_total_amount'])}}</strong>
			</div>
			<div class="row clearfix">
				<div class="col-md-6 col-xs-6">
					<a href="/member/vendor/paybill?bill_id={{$bill['bill_id']}}&vendor_id={{$bill['bill_vendor_id']}}" >Add</a>
				</div>
				<div class="col-md-6 col-xs-6">
					<a href="/member/vendor/create_bill?id={{$bill['bill_id']}}">Open</a>
				</div>
			</div>
		</div>
	@endforeach
	</div>
@endif