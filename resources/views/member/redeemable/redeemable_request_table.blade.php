<table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        <tr>
            @if(count($_redeemable)!=0)
            <th class="text-center">Item Name</th>
            <th class="text-center">Slot No</th>
            <th class="text-center">Full Name</th>
            <th class="text-center">Contact #</th>
            <th class="text-center">Email Adress</th>
            <th class="text-center">Action</th>
            <!-- <th class="text-center">Shop Id</th> -->
            <th class="text-center"></th>
            @else
            <tr>
                <th class="text-center" colspan="5">NO DATA</th>
            </tr>
            @endif
        </tr>
    </thead>
    <tbody>
        @if(!count($_redeemable) == 0)
            @foreach($_redeemable as $redeemable)
            <tr id="{{ $redeemable->item_redeemable_id }}">
                <td class="text-center">{{ $redeemable->item_name }}</td>
                <td class="text-center">{{ $redeemable->slot_no }}</td>
                <td class="text-center">{{ $redeemable->first_name }} {{ $redeemable->middle_name }} {{ $redeemable->last_name }}</td>
                <td class="text-center">{{ $redeemable->contact }}</td>
                <td class="text-center">{{ $redeemable->email }}</td>
                <td class="text-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-custom">
                            @if($redeemable->status=="PENDING")
                                <li>
                                    <a class="action-archive" onclick="location.href='/member/mlm/item_redeemable_points/submit?action=complete&id={{$redeemable->redeemable_request_id}}'">Mark as Complete</a>
                                </li>
                                <li>
                                    <a class="action-modify"  onclick="location.href='/member/mlm/item_redeemable_points/submit?action=cancel&id={{$redeemable->redeemable_request_id}}'">Cancel</a>
                                </li>                                
                            @else
                                <li>
                                    <a class="action-modify" href="javascript:">No Action Available</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>