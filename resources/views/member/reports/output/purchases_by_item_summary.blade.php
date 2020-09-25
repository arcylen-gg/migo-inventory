<div class="report-container">
  <div class="panel panel-default panel-block panel-title-block panel-report load-data">
    <div class="panel-heading load-content">
      @include('member.reports.report_header')
      <div class="table-reponsive">
        <table class="table table-condensed collaptable">
          <tr>
            <th class="text-center">Item Type</th>
            <th class="text-center">Item Name</th>
            <th class="text-center">Qty</th>
            <th class="text-center">U/M</th>
            <th class="text-center">Amount</th>
          </tr>
          <tbody>
            @foreach($_item_type as $key => $item_type)
              <tr data-id="customer-{{$key}}" data-parent="" style="" bgcolor="#c2bcbc">
                <td>{{$item_type->item_type_name}}</td>
                <td colspan="1"></td>
                <td class="text-right"><b>{{$item_type->inventory_qty_sum}}</b></td>
                <td colspan="1"></td>
                <td class="text-right"><b>{{currency('PHP', $item_type->inventory_amt_sum)}}</b></td>
              </tr>

              @foreach($item_type->inventory as $key_result => $value)
                <tr data-id="customer2-{{$key}}" data-parent="customer-{{$key}}">
                  <td nowrap></td>
                  <td nowrap>{{$value->item_name}}</td>
                  <td nowrap class="text-right">{{$value->item_sum}}</td>
                  <td nowrap class="text-right">{{$value->multi_name}}</td>
                  <td nowrap class="text-right">{{currency('PHP', $value->item_amount)}}</td>
                </tr>
              @endforeach
                <tr data-id="customer2-{{$key}}" data-parent="customer-{{$key}}" bgcolor="#e2e0e0">
                  <td><b>Total</b></td>
                  <td colspan="1"></td>
                  <td class="text-right"><b>{{$item_type->inventory_qty_sum}}</b></td>
                  <td colspan="1"></td>
                  <td class="text-right"><b>{{currency('PHP', $item_type->inventory_amt_sum)}}</b></td>
                </tr>
            @endforeach
              <tr bgcolor="#c2bcbc">
                <td><b>Total</b></td>
                <td colspan="1"></td>
                <td class="text-right"><b>{{$item_type->inventory_qty_sum_all}}</b></td>
                <td colspan="1"></td>
                <td class="text-right"><b>{{currency('PHP', $item_type->inventory_amt_sum_all)}}</b></td>
              </tr>
          </tbody>
        </table>
      </div>
      <h5 class="text-center">---- {{$now or ''}} ----</h5>
    </div>
  </div>
</div>