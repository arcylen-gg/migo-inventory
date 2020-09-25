<table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        <tr>
            @if(count($_redeemable)!=0)
            <th class="text-center">Item Id</th>
            <th class="text-center">Item Name</th>
            <th class="text-center">Item Description</th>
            <th class="text-center">Redeemable Points</th>
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
                <td class="text-center">{{ $redeemable->item_id }}</td>
                <td class="text-center">{{ $redeemable->item_name }}</td>
                <td class="text-center">{{ $redeemable->item_description }}</td>
                <td class="text-center">{{ $redeemable->redeemable_points }}</td>
                <!-- <td class="text-center">{{ $redeemable->shop_id }}</td> -->
                <td class="text-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-custom">
                            <li>
                                @if($redeemable->archived==0)
                                <a class="action-archive" href="javascript:">Archive</a>
                                @else
                                <a class="action-archive" href="javascript:">Restore</a>
                                @endif
                            </li>
                            <li>
                                <a class="action-modify" href="javascript:">Modify</a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>