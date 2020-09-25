<td colspan="4" class="well">
    <form class="global-submit" action="/member/mlm/plan/binary/edit/membership/pairing/save" method="post" id="binary_pairing_form{{$membership->membership_id}}">
        {!! csrf_field() !!}
        <input type="hidden" name="membership_id" value="{{$membership->membership_id}}">
    <div class="col-md-12">
        <a href="javascript:" class="pull-right" onClick="binary_pairing_submit({{$membership->membership_id}})">Save</a>
    </div>
    <div class="col-md-12 table-responsive">
        <table class="table table-codensed table-bordered">
            <tbody>
                <tr>
                    <td>
                        <label for="membership_name">Membership Name</label>
                        <input type="text" class="form-control"  name="membership_name" value="{{$membership->membership_name}}" disabled>
                    </td>
                    <td>
                        <label for="membership_name">Number of Pairing</label>
                        <input type="text" class="form-control"  name="membership_pairing_count" value="{{$membership_pairing_count}}" onChange="change_membership_pairing_count(this)">
                    </td>
                    <td>
                        <label for="membership_name">Max Pair Per Cycle</label>
                        <input type="text" class="form-control"  name="max_pair_cycle" value="{{$membership->membership_points_binary_max_pair == null ? 0 : $membership->membership_points_binary_max_pair }}">
                    </td>
                    <td>
                        <label for="membership_name">Max Income Per Cycle</label>
                        <input type="text" class="form-control"  name="membership_points_binary_max_income" value="{{$membership->membership_points_binary_max_income}}">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>        
    <div class="col-md-12 table-responsive">
        <table class="table table-codensed table-bordered">
            <thead>
                <tr>
                    <th>Point (LEFT)</th>
                    <th>Point (RIGHT)</th>
                    <th>Bonus</th>
                    <th>Single Line Binary</th>
                    <th>Level to distribute</th>
                    <th>Fix/%</th>
                </tr>
            </thead>
            <tbody class="pairing_body">
                @if($membership_pairing_count != 0)
                    @foreach($membership_pairing as $key => $value)
                    <tr>
                        <input type="hidden" name="pairing_id[]" value="{{$value->pairing_id}}">
                        <td><input type="text" class="form-control"  name="pairing_point_left[]" value="{{$value->pairing_point_left}}" ></td>
                        <td><input type="text" class="form-control"  name="pairing_point_right[]" value="{{$value->pairing_point_right}}" ></td>
                        <td><input type="text" class="form-control"  name="pairing_bonus[]" value="{{$value->pairing_bonus}}" ></td>
                        <td><input type="text" class="form-control"  name="pairing_point_single_line_bonus[]" value="{{$value->pairing_point_single_line_bonus}}" ></td>
                        <td><input type="text" class="form-control"  name="pairing_point_single_line_bonus_level[]" value="{{$value->pairing_point_single_line_bonus_level}}" ></td>
                        <td>
                            <select class="form-control" name="pairing_point_single_line_bonus_percentage[]">
                                <option value="0" {{$value->pairing_point_single_line_bonus_percentage == 0 ? 'selected' : ''}}>Fix</option>
                                <option value="1" {{$value->pairing_point_single_line_bonus_percentage == 1 ? 'selected' : ''}}>Percentage</option>
                            </select>
                        </td>
                    </tr>
                    @endforeach
                @else
                <tr>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    </form>
    <div class="col-md-12" style="background-color: white;">
        <br />
    </div>    
</td>
<script type="text/javascript">
    function binary_pairing_submit(membership_id)
    {
        $('#binary_pairing_form'+ membership_id).submit();
    }
    function change_membership_pairing_count(pro)
    {
        var count = pro.value;
        var html="";
        for(i = 0; i < count; i++)
        {
            html += "<tr>";
            html += '<td>';
            html += '<input type="hidden" name="pairing_id[]" value="0">';
            html += '   <input type="text" class="form-control"  name="pairing_point_left[]" value="0" >';
            html += '</td>';
            html += '<td>';
            html += '   <input type="text" class="form-control"  name="pairing_point_right[]" value="0" >';
            html += '</td>';
            html += '<td>';
            html += '   <input type="text" class="form-control"  name="pairing_bonus[]" value="0" >';
            html += '</td>';

            html += '<td>';
            html += '   <input type="text" class="form-control"  name="pairing_point_single_line_bonus[]" value="0" >';
            html += '</td>';

            html += '<td>';
            html += '   <input type="text" class="form-control"  name="pairing_point_single_line_bonus_level[]" value="1" >';
            html += '</td>';

            html += '<td>';
            html += '   <select class="form-control" name="pairing_point_single_line_bonus_percentage[]"> <option value="0">Fix</option><option value="1">Percentage</option></select>';
            html += '</td>';


            html += "</tr>";
        }
        $('.pairing_body').html(html);
    }
</script>