<div class="form-group tab-content panel-body customer-wis-container">
    <div id="all" class="tab-pane fade in active customer-wis-table">
        <div class="table-responsive">
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th width="10px">NO</th>
                        <th class="text-center">SLIP NO.</th>
                        <th class="text-center">CUSTOMER</th>
                        <th class="text-center">DELIVERY DATE</th>
                        @if(!$optimize_wiswt)
                        <th class="text-center">TOTAL ISSUED INVENTORY</th>
                        @endif
                        @if($status == 'all')
                        <th class="text-center">STATUS</th>
                        @endif
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($_cust_wis) > 0)
                        @foreach($_cust_wis as $key => $wis)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="text-center">{{$wis->transaction_refnum}}</td>
                            <td class="text-center">{{$wis->company != '' ? $wis->company : ($wis->title_name.' '.$wis->first_name.' '.$wis->middle_name.' '.$wis->last_name)}}</td>
                            <td class="text-center">{{date('F d, Y',strtotime($wis->cust_delivery_date))}}</td>

                            @if(!$optimize_wiswt)
                            <td class="text-center">{{$wis->issued_qty}} pc(s)</td>
                            @endif
                            @if($status == 'all')
                                @if($wis->cust_wis_status == 'pending')
                                <td class="text-center" style="font-weight: bold;color: red">PENDING</td>
                                @elseif($wis->cust_wis_status == 'confirm')
                                <td class="text-center" style="font-weight: bold;color: #ff9900">CONFIRM</td>
                                @elseif($wis->cust_wis_status == 'delivered')
                                <td class="text-center" style="font-weight: bold;color: green">DELIVERED</td>
                                @endif
                            @endif
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-custom">
                                        @if($project == 'fieldmen')
                                        <li ><a target="_blank" href="/member/transaction/wis/print?id={{$wis->cust_wis_id}}"> Print WIS</a></li>
                                        <li ><a target="_blank" href="/member/transaction/wis/print?id={{$wis->cust_wis_id}}&type=for_billing"> Print For Billing</a></li>
                                        <li ><a target="_blank" href="/member/transaction/wis/print?id={{$wis->cust_wis_id}}&type=wo_amount"> Print w/o Amount</a></li>
                                        @else
                                        <li ><a target="_blank" href="/member/transaction/wis/print?id={{$wis->cust_wis_id}}"> Print </a></li>
                                        @endif
                                        @if($wis->cust_wis_status == 'pending')
                                        <li><a class="popup" link="/member/transaction/wis/confirm/{{$wis->cust_wis_id}}?action=confirm" size="md">Confirm</a></li>
                                        <li><a href="/member/transaction/wis/create?id={{$wis->cust_wis_id}}" size="md">Edit</a></li>
                                        @endif
                                        @if($wis->cust_wis_status == 'confirm')
                                        <li ><a target="_blank" href="/member/transaction/wis/print?id={{$wis->cust_wis_id}}&picking=slip"> Picking Slip </a></li>
                                        <li><a class="popup" link="/member/transaction/wis/confirm/{{$wis->cust_wis_id}}?action=delivered" size="md">Delivered</a></li>
                                        <li><a href="/member/transaction/wis/create?id={{$wis->cust_wis_id}}" size="md">Edit</a></li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                    <tr>
                        <td class="text-center" colspan="7">NO PROCESS YET</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <div class="pull-right">{!! $_cust_wis->render() !!}</div>
        </div>
    </div>
</div>