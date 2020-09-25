@if(isset($_po))
	<div style="margin-top: 50px" >
	@foreach($_po as $po)
		<div class="po-counter form-group po-style po-{{$po->po_id}}">
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