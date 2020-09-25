
<div class="form-group tab-content panel-body wis-container">
    <div id="all" class="tab-pane fade in active wis-table">
        <div class="table-responsive">
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th width="10px">NO</th>
                        <th class="text-center">TRANSACTION NUMBER</th>
                        <th class="text-center">RECEIVER WAREHOUSE</th>
                        @if(!$optimize_wiswt)
                            <th class="text-center">TOTAL ISSUED INVENTORY</th>
                        @endif
                        @if($status == 'confirm')
                        <th class="text-center">RECEIVER CODE</th>
                        @elseif($status == 'received')
                            @if(!$optimize_wiswt)
                            <th class="text-center">TOTAL RECEIVED INVENTORY</th>
                            <th class="text-center">TOTAL REMAINING INVENTORY</th>
                            @endif
                        @elseif($status == 'all')
                            <th class="text-center">STATUS</th>
                        @endif
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($_wis) > 0)
                        @foreach($_wis as $key => $wis)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="text-center">{{$wis->wis_number}}</td>
                            <td class="text-center">{{$wis->warehouse_name}}</td>
                            @if(!$optimize_wiswt)
                            <td class="text-center">{{$wis->issued_qty}} pc(s)</td>
                            @endif
                            @if($status == 'confirm')
                            <td class="text-center">{{$wis->receiver_code}}</td>
                            @elseif($status == 'received')
                                @if(!$optimize_wiswt)
                                    <td class="text-center">{{$wis->total_received_qty}} pc(s)</td>
                                    <td class="text-center">{{$wis->issued_qty - $wis->total_received_qty}} pc(s)</td>
                                @endif
                            @elseif($status == 'all')
                                @if($wis->wis_status == 'pending')
                                    <td class="text-center" style="font-weight: bold;color: red">PENDING</td>
                                @elseif($wis->wis_status == 'confirm')
                                    <td class="text-center" style="font-weight: bold;color: #ff9900">CONFIRM</td>   
                                @elseif($wis->wis_status == 'received')
                                    <td class="text-center" style="font-weight: bold;color: green">RECEIVED</td> 
                                @endif                                
                            @endif
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-custom">
                                        <li ><a target="_blank" href="/member/transaction/warehouse_transfer/print?id={{$wis->wis_id}}"> Print </a></li>

                                        @if($wis->wis_status == 'pending')
                                        <li ><a target="_blank" href="/member/transaction/warehouse_transfer/create?id={{$wis->wis_id}}"> Edit </a></li>
                                        <li><a class="popup" link="/member/transaction/warehouse_transfer/confirm/{{$wis->wis_id}}" size="md">Confirm</a></li>
                                        @elseif($wis->wis_status == 'confirm')
                                        <li ><a target="_blank" href="/member/transaction/warehouse_transfer/print?id={{$wis->wis_id}}&picking=slip"> Picking Slip</a></li>
                                        <li><a href="/member/transaction/receiving_report/receive-items/{{$wis->wis_id}}">Receive</a></li>
                                        @elseif($wis->wis_status == 'received')
                                        <li ><a target="_blank" href="/member/transaction/warehouse_transfer/print?id={{$wis->wis_id}}&picking=slip"> Picking Slip</a></li>
                                        @else
                                        <li ><a target="_blank" href="/member/transaction/warehouse_transfer/print?id={{$wis->wis_id}}&picking=slip"> Picking Slip</a></li>
                                        <li><a href="/member/transaction/receiving_report/receive-items/{{$wis->wis_id}}">Receive</a></li>
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
        </div>
    </div>
</div>