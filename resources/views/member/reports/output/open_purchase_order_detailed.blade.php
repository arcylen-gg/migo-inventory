<div class="report-container">
  <div class="panel panel-default panel-block panel-title-block panel-report load-data">
      <div class="panel-heading load-content">
         @include('member.reports.report_header')
         <div class="table-reponsive">
                <table class="table table-condensed collaptable">
                <tr>
                    <th ></th>
                    <th >Date</th>
                    <th >Ref Num</th>
                    <th class="text-center">Vendor Name</th>
                    <th class="text-center">Memo</th>
                    <th class="text-center">Delivery Date</th>
                    <th class="text-center">Ordered Qty</th>
                    <th class="text-center">Received Qty</th>
                    <th class="text-center">UM</th>
                    <th class="text-center">Backordered Qty</th>
                    <th >Amount</th>
                    <th >Open Balance</th>
                </tr>
                <tbody class="{{$category  = 0}}">
                @foreach($_type as $key => $type)
                    @if($type)
                    @foreach($type as $key_type_name => $value_type_name)
                    <tr data-id="type-{{$key_type_name}}" data-parent="" >
                            <td><b>{{ucfirst(str_replace("_"," ",$key_type_name))}}</b></td>
                            <td colspan="5"></td>
                            <td class="text-center"><text class="total-report"><b>{{$value_type_name['total_qty']}}</b></td>
                            <td class="text-center"><text class="total-report"><b>{{$value_type_name['total_received_qty']}}</b></td>
                            <td class="text-center"><text class="total-report"></td>
                            <td class="text-center"><text class="total-report"><b>{{$value_type_name['total_backorder_qty']}}</b></td>
                            <td class="text-center"><text class="total-report"><b>{{number_format($value_type_name['total_amount'], 2)}}</b></td>
                            <td class="text-right"><text class="total-report"><b>{{number_format($value_type_name['total'], 2)}}</b></text></td>
                    </tr>
                    @if($value_type_name['acategory'])
                    @foreach($value_type_name['acategory'] as $key_category => $value_category)
                        <tr data-id="category-{{$key_type_name}}" data-parent="type-{{$key_type_name}}" >
                            <td style="padding-left:50px;"><b>{{$key_category}}</b></td>
                            <td colspan="5"></td>
                            <td class="text-center"><text class="total-report"><b>{{$value_category['items_total_qty']}}</b></td>
                            <td class="text-center"><text class="total-report"><b>{{$value_category['items_total_received_qty']}}</b></td>
                            <td class="text-center"><text class="total-report"></td>
                            <td class="text-center"><text class="total-report"><b>{{$value_category['items_total_backorder_qty']}}</b></td>
                            <td class="text-center"><text class="total-report"><b>{{number_format($value_category['items_total_amount'], 2)}}</b></td>
                            <td class="text-right"><text class="total-report"><b>{{number_format($value_category['items_total'], 2)}}</b></text></td>
                        </tr>
                        @if($value_category['items'])
                        @foreach($value_category['items'] as $key_items => $value_items)
                            @if($value_items)
                            <tr data-id="itemlist-{{$key_type_name}}" data-parent="category-{{$key_type_name}}">
                                <td style="padding-left:100px;"><b>{{$key_items}}</b></td>
                                <td colspan="5"></td>
                                <td class="text-center"><text class="total-report"><b>{{$value_items['poline_total_qty']}}</b></td>
                                <td class="text-center"><text class="total-report"><b>{{$value_items['poline_total_received_qty']}}</b></td>
                                <td class="text-center"><text class="total-report"></td>
                                <td class="text-center"><text class="total-report"><b>{{$value_items['poline_total_backorder_qty']}}</b></td>
                                <td class="text-center"><text class="total-report"><b>{{number_format($value_items['poline_total_amount'], 2)}}</b></td>
                                <td class="text-right"><text class="total-report"><b>{{number_format($value_items['poline_total'], 2)}}</b></text></td>
                            </tr>
                            @if($value_items['poline'])
                            @foreach($value_items['poline'] as $key_poline => $value_poline)
                                @if($value_poline)
                                    <tr data-id="item-{{$key_type_name}}" data-parent="itemlist-{{$key_type_name}}">
                                        <td></td>
                                        <td>{{date("m/d/Y", strtotime($value_poline->po_date))}}</td>
                                        <td>{{$value_poline->transaction_refnum}}</td>
                                        <td>{{$value_poline->vendor_company != '' ? ucfirst($value_poline->vendor_company) : ucfirst($value_poline->vendor_title_name." ".$value_poline->vendor_first_name." ".$value_poline->vendor_middle_name." ".$value_poline->vendor_last_name." ".$value_poline->vendor_suffix_name)}}</td>
                                        <td>{{$value_poline->po_memo}}</td>
                                        <td>{{date("m/d/Y", strtotime($value_poline->po_delivery_date))}}</td>
                                        <td class="text-center">{{$value_poline->poline_orig_qty}}</td>
                                        <td class="text-center">{{$value_poline->poline_orig_qty - $value_poline->poline_qty}}</td>
                                        <td class="text-center">{{$value_poline->multi_name}}</td>
                                        <td class="text-center">{{$value_poline->poline_qty}}</td>
                                        <td class="text-right">{{number_format($value_poline->poline_amount, 2)}}</td>
                                        <td class="text-right">{{number_format($value_poline->poline_amount * $value_poline->poline_qty, 2)}}</td>
                                        <td class="text-center" ></td>

                                    </tr>
                                @endif
                            @endforeach
                            @endif
                            <tr data-id="itemlisttotal-{{$key_type_name}}" data-parent="itemlist-{{$key_type_name}}" bgcolor="#d9d9d9">
                                <td style="padding-left:100px;" colspan="6"><b>Item {{$key_items}} - Total</b></td>
                                <td class="text-center"><b>{{$value_items['poline_total_qty']}}</b></td>
                                <td class="text-center"><b>{{$value_items['poline_total_received_qty']}}</b></td>
                                <td class="text-center"></td>
                                <td class="text-center"><b>{{$value_items['poline_total_backorder_qty']}}</b></td>
                                <td class="text-center"><b>{{number_format($value_items['poline_total_amount'], 2)}}</b></td>
                                <td style="text-decoration-line: underline;text-decoration-style: dotted;" class="text-right"><b>{{number_format($value_items['poline_total'], 2)}}</b></td>
                            </tr>
                            @endif
                        @endforeach
                        @endif

                        <tr data-id="categorytotal-{{$key_type_name}}" data-parent="category-{{$key_type_name}}" bgcolor="#cccccc">
                            <td style="padding-left:50px;" colspan="6"><b>Category {{$key_category}} - Total</b></td>
                            <td class="text-center"><b>{{$value_category['items_total_qty']}}</b></td>
                            <td class="text-center"><b>{{$value_category['items_total_received_qty']}}</b></td>
                            <td class="text-center"></td>
                            <td class="text-center"><b>{{$value_category['items_total_backorder_qty']}}</b></td>
                            <td class="text-center"><b>{{number_format($value_category['items_total_amount'], 2)}}</b></td>
                            <td style="text-decoration-line: underline;text-decoration-style: solid;" class="text-right"><b>{{number_format($value_category['items_total'], 2)}}</b></td>
                        </tr>
                    @endforeach
                    @endif
                    <tr data-id="typetotal-{{$key_type_name}}" data-parent="type-{{$key_type_name}}" bgcolor="#b3b3b3"  style="font-weight: bold">
                        <td colspan="6"><b>Type {{ucfirst($key_type_name)}} - Total Amount</b></td>
                        <td class="text-center"><text class="total-report"><b>{{$value_type_name['total_qty']}}</b></td>
                        <td class="text-center"><text class="total-report"><b>{{$value_type_name['total_received_qty']}}</b></td>
                        <td class="text-center"><text class="total-report"></td>
                        <td class="text-center"><text class="total-report"><b>{{$value_type_name['total_backorder_qty']}}</b></td>
                        <td class="text-center"><text class="total-report"><b>{{number_format($value_type_name['total_amount'], 2)}}</b></td>
                        <td style="text-decoration-line: underline;text-decoration-style: double;" class="text-right">{{number_format($value_type_name['total'], 2)}}</td>
                    </tr>
                    @endforeach
                    @endif
                @endforeach
                <tr bgcolor="#b3b3b3"  style="font-weight: bold">
                        <td colspan="6"><b>Total Amount</b></td>
                        <td class="text-center"><text class="total-report"><b>{{$total_qty}}</b></td>
                        <td class="text-center"><text class="total-report"><b>{{$total_received_qty}}</b></td>
                        <td class="text-center"><text class="total-report"></td>
                        <td class="text-center"><text class="total-report"><b>{{$total_backorder_qty}}</b></td>
                        <td class="text-center"><text class="total-report"><b>{{number_format($total_amount, 2)}}</b></td>
                        <td style="text-decoration-line: underline;text-decoration-style: double;" class="text-right">{{number_format($total, 2)}}</td>
                    </tr>
                </tbody>
                </table>
            </div>
          <h5 class="text-center">---- {{$now or ''}} ----</h5>
      </div>
  </div>
</div>