
<input type="hidden" class="wis" value="{{$wis_id}}">
<div class="report-container">
  <div class="panel panel-default panel-block panel-title-block panel-report load-data">
      <div class="panel-heading load-content">
         @include('member.reports.report_header')
         <div class="table-reponsive">
         		<table class="table table-condensed collaptable">
         		<tr>
         			<th>Ref Num</th>
         			<th class="text-center">Remarks</th>
         			<th class="text-center">Item Description</th>
         			<th class="text-center">Qty</th>
         			<th class="text-center">UM</th>
                    <th class="text-center hidden">Amount</th>
         		</tr>
         		<tbody>
     				<tr>
         				<td><b>{{$wis->transaction_refnum}}</b></td>
                        <td>{{$transaction_description}}</td>
                        <td colspan="5"></td>
                    </tr>
                    @foreach($wis_item as $key => $value)
                    <tr>
                        <td nowrap></td>
                        <td nowrap></td>
                        <td nowrap>
                            {{$value['item_name']}}<br>
                            <small>{{$value['item_sku']}}</small>
                        </td>
                        <td nowrap class="text-center">{{$value['item_orig_qty']}}</td>
                        <td nowrap class="text-center">{{$value['item_um']}}</td>
                        <td class="text-right hidden" nowrap></td>
                        
                    </tr>
                    @endforeach
         		</tbody>
         		</table>
         	</div>
          <h5 class="text-center">---- {{$now or ''}} ----</h5>
      </div>
  </div>
</div>