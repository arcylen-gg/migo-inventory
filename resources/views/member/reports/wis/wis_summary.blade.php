<div class="report-container">
  <div class="panel panel-default panel-block panel-title-block panel-report load-data">
      <div class="panel-heading load-content">
         @include('member.reports.report_header')
        <div class="table-reponsive">
          <table class="table table-condensed collaptable">
              <thead>
                  <tr>
                    <th>Warehouse Name</th>
                    <th class="text-center">Ref Num</th>
                    <th class="text-center">Destination Warehouse</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">Truck</th>
                    <th class="text-center">Issued by</th>
                    <th class="text-center hidden">Amount</th>
                </tr>
              </thead>
              <tbody {{$total = 0}}>
                @foreach($_warehouse as $key =>$warehouse)
                  <tr data-id="customer-{{$key}}" data-parent="">
                    <td><b>{{$warehouse->warehouse_name}}</b></td>
                    <td colspan="6"></td>
                    <td class="hidden text-right"><b>{{number_format($warehouse['total_per_warehouse'],2)}}</b></td>
                  </tr>
                  @if(count($warehouse['_wis']) > 0)
                    @foreach($warehouse['_wis'] as $key2 => $wis)
                    <tr data-id="customer2-{{$key}}" data-parent="customer-{{$key}}">
                        <td></td>
                        <td nowrap><a target="_blank" href="/member/report/warehouse/wis/detailed/{{$wis->cust_wis_id}}">{{$wis->transaction_refnum}}</a></td>
                        <td nowrap>{{$wis->company != '' ? $wis->company : ($wis->title_name.' '.$wis->first_name.' '.$wis->middle_name.' '.$wis->last_name)}}</td>
                        <td nowrap>{{ucfirst($wis->cust_wis_status == 'confirm' ? 'In-Transit' : $wis->cust_wis_status)}}</td>
                        <td nowrap>{{$wis->cust_delivery_date}}</td>
                        <td class="text-center" nowrap>{{strtoupper($wis->plate_number)}}</td>
                        <td class="text-center" nowrap>{{$wis->issued_created_by}}</td>
                        <td class="text-right hidden" nowrap>{{number_format($wis->total_amount, 2)}}</td>
                        <td class="text-right" nowrap></td>
                    </tr>
                    @endforeach
                  @else
                      <tr data-id="customer2-{{$key}}" data-parent="customer-{{$key}}"><td colspan="9" class="text-center ">NO TRANSACTION YET</td></tr>
                  @endif
                  <tr class="hidden" data-id="customer2-{{$key}}" data-parent="customer-{{$key}}" bgcolor="#b7b7b7">
                    <td colspan="7"><b>Total {{$warehouse->warehouse_name}}</b></td>
                    <td class="text-right" {{$total += $warehouse['total_per_warehouse'] }}><b>{{number_format($warehouse['total_per_warehouse'],2)}}</b></td>
                  </tr> 
                @endforeach
                <tr class="hidden" bgcolor="#a7abb2">
                    <td colspan="7"><b>Total Amount</b></td>
                    <td class="text-right"><b>{{currency('P ', $total)}}</b></td>
                </tr>
              </tbody>
          </table>
        </div>
          <h5 class="text-center">---- {{$now or ''}} ----</h5>
      </div>
  </div>
</div>