<td colspan="3">
    <form method="post" action="/member/mlm/plan/unilevel/settings/save" class="global-submit" id="form_unilevel{{$membership->membership_id}}">
        
    {!! csrf_field() !!}
    <input type="hidden" name="membership_id" value="{{$membership->membership_id}}">
        <div class="col-md-12">
            <div class="col-md-4">
                <label for="membership_name">Membership Name</label>
                <input type="text" class="form-control" name="membership_name" value="{{$membership->membership_name}}" disabled>
            </div>
            <div class="col-md-4">
                <label for="membership_name">No. of levels</label>
                <input type="text" class="form-control" name="unilevel_level_count" value="{{$uni_count}}" membership_id="{{$membership->membership_id}}" id="unilevel_level_count{{$membership->membership_id}}" onChange="change_level_append(this)">
            </div>
            <div class="col-md-4">
                <a href="javascript:" class="pull-right" name="save-c" onClick="submit_form_unilevel({{$membership->membership_id}})">Save</a>
            </div>
        </div>    
        
        <div class="col-md-12" id="unilevel_per_level{{$membership->membership_id}}">
            @if(isset($uni_settings))
                @if(!empty($uni_settings))
                    @foreach($uni_settings as $key => $value)
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <label for='unilevel_settings_level'>Level</label>
                            <input type="number" class="form-control" name="unilevel_settings_level[]" value="{{$value->unilevel_settings_level}}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for='unilevel_settings_amount'>Amount</label>
                            <input type="number" class="form-control" name="unilevel_settings_amount[]" value="{{$value->unilevel_settings_amount}}">
                        </div>
                        <div class='col-md-3'>
                            <label for='unilevel_settings_percent'>Type</label>
                            <select class='form-control' name='unilevel_settings_percent[]'>
                                <option value='0' {{$value->unilevel_settings_percent == 0 ? "selected" : "" }} >Fixed</option>
                                <option value='1' {{$value->unilevel_settings_percent == 1 ? "selected" : "" }} >Percentage</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Earn Type</label>
                            <select class="form-control" name="unilevel_settings_type[]">
                                <option value="0" {{$value->unilevel_settings_type == 0 ? "selected" : " "}}>Points</option>
                                <option value="1" {{$value->unilevel_settings_type == 1 ? "selected" : " "}}>Cash</option>
                            </select>
                        </div>
                    </div>
                    @endforeach
                @else
                <center>No Unilevel Setting</center>
                @endif
            @else
            <center>No Unilevel Setting</center>
            @endif
        </div>
    </form>
</td>
