@if(isset($_estimate))
	<div style="margin-top: 50px" >
	@foreach($_estimate as $est)
		<div class="est-count form-group est-style est-{{$est->est_id}}">
			<div>
				<strong>{{$est->is_sales_order == 1 ? 'Sales Order ' : 'Estimate'}} #{{$est->est_id}}</strong>
			</div>
			<div class="{{$y = date('Y') == date('Y',strtotime($est->est_date)) ? '' : date('Y',strtotime($est->est_date))}}" >
				{{date("M d ".$y ,strtotime($est->est_date))}}
			</div>
			<div>
				<strong>{{currency("PHP",$est->est_overall_price)}}</strong>
			</div>
			<div class="row clearfix">
				<div class="col-md-6 col-xs-6">
					<a onclick="add_est_to_inv({{$est->est_id}}, 'est')">Add</a>
				</div>
				<div class="col-md-6 col-xs-6">
					<a href="/member/customer/{{$est->is_sales_order == 1 ? 'sales_order' : 'estimate'}}?id={{$est->est_id}}">Open</a>
				</div>
			</div>
		</div>
	@endforeach
	</div>
@endif
@if(isset($_so))
	<div style="margin-top: 50px" >
	@foreach($_so as $so)
		<div class="so-count form-group est-style est-{{$so->est_id}}">
			<div>
				<strong>{{$so->is_sales_order == 1 ? 'Sales Order ' : 'Estimate'}} #{{$so->est_id}}</strong>
			</div>
			<div class="{{$y = date('Y') == date('Y',strtotime($so->est_date)) ? '' : date('Y',strtotime($so->est_date))}}" >
				{{date("M d ".$y ,strtotime($so->est_date))}}
			</div>
			<div>
				<strong>{{currency("PHP",$so->est_overall_price)}}</strong>
			</div>
			<div class="row clearfix">
				<div class="col-md-6 col-xs-6">
					<a onclick="add_est_to_inv({{$so->est_id}}, 'so')">Add</a>
				</div>
				<div class="col-md-6 col-xs-6">
					<a href="/member/customer/{{$so->is_sales_order == 1 ? 'sales_order' : 'estimate'}}?id={{$so->est_id}}">Open</a>
				</div>
			</div>
		</div>
	@endforeach
	</div>
@endif