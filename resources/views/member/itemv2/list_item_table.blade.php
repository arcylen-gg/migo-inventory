@if(count($_item) > 0)

<table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        <tr>
            <th width="20"><input type="checkbox" class="check-all-checkbox" name="check_all_checkbox"></th>
           
            @foreach($_item[$item_key[0]] as $column)
            <th class="text-center column-name" onclick="sortTable('{{$column["column_name"]}}');">{{ $column["label"] }}
            <span class="fa fa-fw fa-sort"></span></th>
            @endforeach
            <th class="text-left" width="170px"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($_item as $key => $item)
        <tr>
            <td class="text-center">
                <input type="checkbox" class="check-bulk-item" name="check_bulk_item" value="{{isset($_item[$key][0]['data']) ? $_item[$key][0]['data'] : '' }}" item-id="{{isset($_item[$key][0]['data']) ? $_item[$key][0]['data'] : '' }}" id="{{isset($_item[$key][0]['data']) ? $_item[$key][0]['data'] : '' }}">
            </td>
         
            <td class="hidden">{{isset($_item[$key][0]['data']) ? $_item[$key][0]['data'] : '' }}</td>
            @foreach($item as $column)
                @if( $column["column_name"] == "item_img")
                    <td class="text-center">
                        <img src="{{ $column['data'] }}" alt="" style="heigth: 50px; width: 50px; object-fit:contain">
                    </td>
                @else  
                    <td class="text-center">{{ $column["data"] }}</td>
                @endif
            @endforeach
           
            <td class="text-center">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-custom">
                        <li>
                            <a onclick="action_load_link_to_modal('/member/item/v2/edit?item_id={{ $column["default"]->item_id }}', 'lg')">
                                <div style="display: inline-block; width: 17px; text-align: center;"><i class="fa fa-edit"></i> &nbsp;</div>
                                Modify
                            </a>
                        </li>
                        <li>
                            <a href="javascript:" class="item-{{ $archive }}" item-id="{{ $column["default"]->item_id }}">
                                <div style="display: inline-block; width: 17px; text-align: center;"><i class="fa fa-trash"></i> &nbsp;</div>
                                <span style="text-transform: capitalize;">{{ $archive }}</span>
                            </a>
                        </li>
                        @if($user->position_rank === 0)
                            <li class="hidden">
                                <a href="javascript:">
                                    <div style="display: inline-block; width: 17px; text-align: center;"><i class="fa fa-info"></i> &nbsp;</div>
                                    Item Information
                                </a>
                            </li>
                            @if($column["default"]->item_type_id == 1)
                            <li>
                                <a onclick="action_load_link_to_modal('/member/item/v2/refill_item?item_id={{ $column["default"]->item_id}}','md')">
                                    <div style="display: inline-block; width: 17px; text-align: center;"><i class="fa fa-cubes"></i> &nbsp;</div>
                                    Refill Item
                                </a>
                            </li>
                            @endif
                        @endif
                    </ul>
                </div>
            </td>

        </tr>
        @endforeach
    </tbody>
</table>
<div class="pull-right">{!! $pagination !!}</div>
@else
<div style="padding: 100px; text-align: center;">NO DATA YET</div>
@endif

<!-- <script type="text/javascript">
$(document).ready(function()
{
    $(".check-all-checkbox").change(function()
    {  //"select all" change 
        $(".check-bulk-item").prop('checked', $(this).prop("checked")); //change all ".check-bulk-item" checked status
    });
    //".check-bulk-item" change 
    $('.check-bulk-item').change(function(){ 
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if(false == $(this).prop("checked"))
        { //if this item is unchecked
            $(".check-all-checkbox").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.check-bulk-item:checked').length == $('.check-bulk-item').length )
        {
            $(".check-all-checkbox").prop('checked', true);
        }
        
    });
    if ($('.check-bulk-item:checked').length == $('.check-bulk-item').length )
    {
        $(".check-all-checkbox").prop('checked', true);
    }
}); 

</script> -->