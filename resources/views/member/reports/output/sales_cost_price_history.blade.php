<div class="report-container">
  <div class="panel panel-default panel-block panel-title-block panel-report load-data">
      <div class="panel-heading load-content">
         @include('member.reports.report_header')
         <div class="table-reponsive">
         		<table class="table table-condensed collaptable">
         		<tr>
              <th class="text-center" class="text-center">ITEM NAME</th>
         			<th class="text-center">DATE</th>
              <th class="text-center">CHANGE BY</th>
              <th class="text-center">SALES PRICE</th>
              <th class="text-center">COST PRICE</th>
              <th class="text-center">MARKUP</th>
         		</tr>
         		<tbody>
         		@if($_history)	
     				@foreach($_history as $key=> $history)
     				<tr data-id="customer-{{$key}}" data-parent="">
         				<td class="text-center"><b>{{$history->item_name}}</b></td>
                <td colspan="2"></td>
                <td class="text-center"><strong><text class="total-report">{{currency('PHP', $history->item_price)}}</text></strong></td>
                @if($item_costing != 'average_costing')
                <td class="text-center"><strong><text class="total-report">{{currency('PHP', $history->item_cost)}}</text></strong></td>
                <td class="text-center"><strong><text class="total-report">{{currency('PHP', $history->item_price - $history->item_cost)}}</text></strong></td>
                @elseif($item_costing == 'average_costing' && $history->average_cost)
                <td class="text-center"><strong><text class="total-report">{{currency('PHP', $history->average_cost)}}</text></strong></td>
                <td class="text-center"><strong><text class="total-report">{{currency('PHP', $history->item_price - $history->average_cost)}}</text></strong></td>
                @endif
         		</tr>
     				  @foreach($history->item_price_history as $key2 => $price_history)
  						<tr data-id="customer2-{{$key}}" data-parent="customer-{{$key}}">
                <td class="text-center"></td>
  							<td class="text-center">{{date('F d, Y',strtotime($price_history->pricing_created))}}</td>
                <td class="text-center">{{$price_history->user_first_name." ".$price_history->user_last_name}}</td>
                <td class="text-center">{{currency("PHP ",$price_history->pricing_sales_price)}}</td>
                <td class="text-center">{{currency("PHP ",$price_history->pricing_cost_price)}}</td>
                <td class="text-center">{{currency("PHP ",$price_history->pricing_sales_price - $price_history->pricing_cost_price)}}</td>
  						</tr>
    					@endforeach
     				@endforeach
            @endif
         		</tbody>
         		</table>
         	</div>
          <h5 class="text-center">---- {{$now or ''}} ----</h5>
      </div>
  </div>
</div>