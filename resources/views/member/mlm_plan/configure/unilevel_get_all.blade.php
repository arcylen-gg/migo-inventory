@if(isset($membership))
    @if(!empty($membership))
        @foreach($membership as $key => $mem)
            <tr class="tr_to_appen_indirect_per_membership{{$mem->membership_id}}">
                <td>{{$mem->membership_name}}</td>
                <td>{{$uni_count[$key]}}</td>
                <td>
                    <a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="javascript:" onClick="editunilevel({{$mem->membership_id}})">Edit</a>
                </td>
            </tr>
        @endforeach
    @else
    <tr>
        <td colspan="3"><center>No Active Membership</center></td>
    </tr>
    @endif
@endif