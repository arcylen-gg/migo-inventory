<div class="table-responsive">
    <table class="table table-condensed table-bordered">
        <thead>
            <th class="text-center">Slot</th>
            <th class="text-center">Membership</th>
            <th class="text-center">Name</th>
            <th class="text-center">Date Created</th>
            <th class="text-center">Wallet</th>
            <th class="text-center"></th>
        </thead>
        @if(isset($code_selected))
            @if(count($code_selected) != 0)
                @foreach($code_selected as $key => $slot)
                    <tr>
                    
                        <td class="text-center"><a href="javascript:" class="popup" link="/member/mlm/slot/view/{{$slot->slot_id}}" size="lg">{{$slot->slot_no}}</a></td>
                        <td class="text-center">{{$slot->membership_name}} ({{$slot->slot_status}})</td>
                        <td class="text-center"><a href="javascript:" link="/member/customer/customeredit/{{$slot->customer_id}}" class="popup" size="lg">{{$slot->first_name}} {{$slot->middle_name}} {{$slot->last_name}}</a></td>
                        <td class="text-center">{{$slot->slot_created_date}}</td>
                        <td class="text-center" style="color: green;">{{currency('PHP',$slot->slot_wallet_all)}}</td>
                        <td class="text-left">
                            <!-- ACTION BUTTON -->
                            <div class="btn-group">
                              <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu">
                                <li><a href="/member/mlm/slot/genealogy?id={{$slot->slot_id}}&mode=sponsor" target="_blank">View Unilevel Genealogy</a></li>
                                @if(isset($binary_settings->marketing_plan_enable))
                                <li><a href="/member/mlm/slot/genealogy?id={{$slot->slot_id}}&mode=binary" target="_blank">View Binary Genealogy</a></li>
                                @endif 
                                <li><a href="/member/mlm/slot/login?slot={{$slot->slot_id}}" target="_blank">LOGIN</a></li>                             
                                <li><a href="javascript:" link="/member/mlm/slot/transfer?slot={{$slot->slot_id}}" class="popup">TRANSFER</a></li>                             
                              </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
            <tr><td colspan="6"><center>{!! $code_selected->render() !!}</center></td></tr>
        @endif
    </table> 
</div> 