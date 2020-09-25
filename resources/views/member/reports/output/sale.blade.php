<div class="report-container">
	<div class="panel panel-default panel-block panel-report panel-title-block">
	    <div class="panel-heading">
	    	@include('member.reports.report_header');
	        <div class="table-repsonsive">
				<table class="table table-condensed collaptable">
					<thead>
						<tr>
							@foreach($report_field as $key => $value)
								<th class="text-center">{{$value->report_field_label}}</th>
							@endforeach
							<th>Balance</th>
						</tr>
					</thead>
					<tbody>
			            @foreach($sales_by_customer as $key => $value)
			            	<?php $total = 0; ?>
			            	<?php $amount = 0; ?>
			            	<tr data-id="{{$key}}" data-parent=""  style="background-color: #dedede;" >
			            		<td colspan="{{count($report_field)}}" @if($report_type != 'excel')@endif >{{$customer_info[$key]}}</td>
			            		<td class="text-right"><text class="total-report">{{currency('PHP', collect($value)->sum('amount'))}}</text></td>
			            	</tr>
			            	@foreach($value as $key2 => $value2)
			            	<tr data-id="{{$key2}}" data-parent="{{$key}}">
				            	@foreach($report_field as $key3 => $value3)
									@if($key3  == 'jline_amount')
									<td>{{currency('PHP', $value2->amount)}}</td>
									@elseif($key3  == 'item_price')
									<td>{{currency('PHP', $value2->item_price)}}</td>
									@elseif($key3  == 'item_rate')
									<td>{{currency('PHP', $value2->item_rate)}}</td>
									@else
									<td>{{$key3 == 'z_um' ? $value2->sales_quantity . "" .$value2->$key3 : $value2->$key3}}</td>
									@endif
		
								@endforeach
								<?php $total += $value2->amount; ?>
								<td>{{currency('PHP', $total)}}</td>
							</tr>		
							@endforeach
							<tr data-id="total-{{$key2}}" data-parent="{{$key}}">
								<td colspan="{{count($report_field) }}">Total : {{$customer_info[$key]}} </td>
								<td>{{currency('PHP', $total)}}</td>
							</tr>
			            @endforeach
			            
	            	</tbody>
				</table>
	        </div>
	        <h5 class="text-center">---- {{$now or ''}} ----</h5>
	    </div>
	</div>
</div>