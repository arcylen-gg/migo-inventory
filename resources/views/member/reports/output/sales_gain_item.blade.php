<div class="report-container">
  <div class="panel panel-default panel-block panel-title-block panel-report load-data">
      <div class="panel-heading load-content">
         @include('member.reports.report_header')
         <div class="table-reponsive">
         		<table class="table table-condensed collaptable">
              <thead>
             		<tr>
                  <th class="text-center" class="text-center">Remarks</th>
             			<th class="text-center">Reference Module</th>
             			<!-- <th class="text-center">Date</th> -->
             			<th class="text-center">Qty</th>
             			<th class="text-center">Sales Price</th>
             			<th class="text-center">Cost Price</th>
             			<th class="text-center">Sales Amount</th>
                  <th class="text-center">Cost Amount</th>
                  <th class="text-center">Discount given</th>
                  <th class="text-center">Gain</th>
             		</tr>
              </thead>
              <tbody>
                @if(count($_gain) > 0)
                  @foreach($_gain as $gain)
                  <tr>
                    <td>{{$gain->item_name}}</td>
                    <td>
                      {{$gain->reference_module}}
                    </td>
                    <!-- <td></td> -->
                    <td>{{$gain->qty}}</td>
                    <td class="text-right">{{currency('PHP ',$gain->sales_price)}}</td>
                    <td class="text-right">{{currency('PHP ',$gain->cost_price)}}</td>
                    <td class="text-right">{{currency('PHP ',$gain->sales_amount)}}</td>
                    <td class="text-right">{{currency('PHP ',$gain->cost_amount)}}</td>
                    <td class="text-right">{{currency('PHP ',$gain->discount_given)}}</td>
                    <td class="text-right">{{currency('PHP ',$gain->gain)}}</td>
                  </tr>
                  @endforeach
                @else
                <tr>
                  <td colspan="20" class="text-center">NO TRANSACTION YET</td>
                </tr>
                @endif
              </tbody>         	
         		</table>
         	</div>
          <h5 class="text-center">---- {{$now or ''}} ----</h5>
      </div>
  </div>
</div>