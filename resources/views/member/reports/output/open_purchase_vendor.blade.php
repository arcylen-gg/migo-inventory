<div class="report-container">
  <div class="panel panel-default panel-block panel-title-block panel-report load-data">
    <div class="panel-heading load-content">
      @include('member.reports.report_header')
      <div class="table-reponsive">
        <table class="table table-condensed collaptable">
          <tr>
            <th>Vendor Name</th>
            <th>Type</th>
            <th>Date</th>
            <th>Num</th>
            <th>Memo</th>
            <th>Item</th>
            <th>Qty</th>
            <th>U/M</th>
            <th>Cost Price</th>
            <th>Amount</th>
            <th>Balance</th>
          </tr>
          <tbody>
            @foreach($_vendor as $key => $vendor)
              <tr data-id="customer-{{$key}}" data-parent="" style="">
                <td>{{$vendor->vendor_company == '' ? ucfirst($vendor->vendor_title_name." ".$vendor->vendor_first_name." ".$vendor->vendor_middle_name." ".$vendor->vendor_last_name." ".$vendor->vendor_suffix_name) : $vendor->vendor_company}}</td>
                <td colspan="5"></td>
                <td class="text-right"><text class="total-report"><b>{{$vendor->itm_qty_total}}</b></text></td>
                <td colspan="2"></td>
                <td class="text-right"><text class="total-report"><b>{{currency('PHP', $vendor->amt_total)}}</b></text></td>
                <td class="text-right"><text class="total-report"><b>{{currency('PHP', $vendor->amt_total)}}</b></text></td>
              </tr>
              @if(count($vendor->bill) > 0)
              @foreach($vendor->bill as $value)
                <tr data-id="customer2-{{$key}}" data-parent="customer-{{$key}}">
                  <td nowrap></td>
                  <td nowrap>Bill</td>
                  <td nowrap>{{date('F d, Y', strtotime($value['bill_date']))}}</td>
                  @if($value['transaction_refnum'] == "" || $value['transaction_refnum'] == null)
                    <td class="text-center" nowrap>{{$value['bill_id']}}</td>
                  @else
                    <td class="text-center" nowrap>{{$value['transaction_refnum']}}</td>
                  @endif
                  <td class="text-center" nowrap>{{$value['bill_memo']}}</td>
                  <td class="text-center" nowrap>{{$value['item_name']}}</td>
                  <td class="text-center" nowrap>{{$value['itemline_qty']}}</td>
                  <td class="text-center" nowrap>{{$value['multi_name']}}</td>
                  <td class="text-right" nowrap>{{currency('PHP', $value['itemline_rate'])}}</td>
                  <td class="text-right" nowrap>{{currency('PHP', $value['balance_vendor'])}}</td>
                  <td class="text-right" nowrap>{{currency('PHP', $value['balance_cumulative'])}}</td>
                </tr>
              @endforeach
              @else
              <tr  data-id="customer2-{{$key}}" data-parent="customer-{{$key}}"><td colspan="11" class="text-center">NO TRANSACTION YET</td></tr>
              @endif
              <tr data-id="customer2-{{$key}}" data-parent="customer-{{$key}}" bgcolor="#c2bcbc">
                <td colspan="6"><b>Total Amount {{$vendor->vendor_company == '' ? ucfirst($vendor->vendor_title_name." ".$vendor->vendor_first_name." ".$vendor->vendor_middle_name." ".$vendor->vendor_last_name." ".$vendor->vendor_suffix_name) : $vendor->vendor_company}}</b></td>
                <td class="text-right"><text class="total-report"><b>{{$vendor->itm_qty_total}}</b></text></td>
                <td colspan="2"></td>
                <td class="text-right"><b>{{currency('PHP', $vendor->amt_total)}}</b></td>
                <td class="text-right"><b>{{currency('PHP', $vendor->amt_total)}}</b></td>
              </tr>
            @endforeach
              <tr bgcolor="#c2bcbc">
                <td colspan="9"><b>Total Amount</b></td>
                <td class="text-right"><b>{{currency('PHP', $vendor->amt_total_all_vendor)}}</b></td>
                <td class="text-right"><b>{{currency('PHP', $vendor->amt_total_all_vendor)}}</b></td>
              </tr>
            
          </tbody>
        </table>
      </div>
      <h5 class="text-center">---- {{$now or ''}} ----</h5>
    </div>
  </div>
</div>