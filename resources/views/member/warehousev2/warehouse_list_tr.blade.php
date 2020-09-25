 <tr class="{{$tr_class}}">
    <td> 
      <span {!!$margin_left!!}><i class="fa fa-caret-down toggle-warehouse margin-right-10 cursor-pointer" data-content="{{$warehouse->warehouse_id}}"></i> {{$warehouse->warehouse_name}}
     </span>
    </td>
    <td class="text-center">
        <div class="btn-group">
          <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Action <span class="caret"></span>
          </button>
          <ul class="dropdown-menu dropdown-menu-custom">
            @if($warehouse->archived == 0)
            <li><a target="_blank" href="">View Warehouse</a></li>
            <li><a href="javascript:" class="popup" link="/member/item/v2/warehouse/edit/{{$warehouse->warehouse_id}}" size="lg" data-toggle="modal" data-target="#global_modal">Edit</a></li>
            <li><a class="popup" link="/member/transaction/notification/reorder-notification?warehouse_id={{$warehouse->warehouse_id}}" size="md" >View Reorder Point Item</a></li>
            <li><a href="/member/item/v2/warehouse_reorder?w_id={{$warehouse->warehouse_id}}" >Set Reorder Point Item</a></li>
            <li><a link="/member/item/v2/warehouse/archived-restore?ty=1&d={{$warehouse->warehouse_id}}" href="javascript:" class="popup">Archived Warehouse</a></li>
            @else
            <li><a link="/member/item/v2/warehouse/archived-restore?ty=0&d={{$warehouse->warehouse_id}}" href="javascript:" class="popup">Restore Warehouse</a></li>
            @endif
          </ul>
        </div>
    </td>
</tr>