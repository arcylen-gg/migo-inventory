@if($rank)
    @foreach($rank as $key => $value)
    <tr>
        <td colspan="6">
        <form class="global-submit" method="post" id="edit_form{{$key}}" action="/member/mlm/plan/rank/edit/save" id="edit_stairstep{{$key}}">
            {!! csrf_field() !!}
            <input type="hidden" name="stairstep_id" value="{{$value->stairstep_id}}" />
            <div class="col-md-2">
                <label for="stairstep_name">Rank Name</label>
                <input type="text" class="form-control" name="stairstep_name" value="{{$value->stairstep_name}}" placeholder="Enter Rank Name">
            </div>
            <div class="col-md-1">
                <label for="stairstep_level">Rank Level</label>
                <select name="stairstep_level" class="form-control">
                @for($i = 1; $i < $rank_count; $i++)
                    <option value="{{$i}}" {{$value->stairstep_level == $i ? 'selected' : "" }}>{{$i}}</option>
                @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label for="stairstep_required_pv">Required Personal Sales</label>
                <input type="number" class="form-control" name="stairstep_required_pv" value="{{$value->stairstep_required_pv}}">
            </div>
            <div class="col-md-2">
                <label for="stairstep_required_gv">Required Group Sales</label>
                <input type="number" class="form-control" name="stairstep_required_gv" value="{{$value->stairstep_required_gv}}">
            </div>            
            <div class="col-md-2">
                <label for="stairstep_required_gv">Personal Sales Maintenance</label>
                <input type="number" class="form-control" name="stairstep_pv_maintenance" value="{{$value->stairstep_pv_maintenance}}">
            </div>            
            <div class="col-md-2">
                <label for="stairstep_required_gv">Commission Multiplier</label>
                <input type="number" class="form-control" name="commission_multiplier" value="{{$value->commission_multiplier}}">
            </div>
            <div class="col-md-1">
                <label for="stairstep_bonus">Bonus</label>
                <input type="number" class="form-control" name="stairstep_bonus" value="{{$value->stairstep_bonus}}">
            </div>               
            <div class="col-md-12"></div>      
            <div class="col-md-2">
                <label for="direct_rank_bonus">Direct Bonus</label>
                <input type="number" class="form-control" name="direct_rank_bonus" value="{{$value->direct_rank_bonus}}">
            </div>            
            <div class="col-md-2">
                <label for="stairstep_rebates_bonus">Rebates Bonus</label>
                <input type="number" class="form-control" name="stairstep_rebates_bonus" value="{{$value->stairstep_rebates_bonus}}">
            </div>   
            <div class="col-md-2">
                <label for="stairstep_leg_id">Leg Rank</label>
                <select name="stairstep_leg_id" class="form-control">
                    <option value="0">None</option>
                    @foreach($rank as $rk)
                        <option value="{{$rk->stairstep_id}}" {{$rk->stairstep_id == $value->stairstep_leg_id ? "selected" : ""}}>{{$rk->stairstep_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <label for="stairstep_leg_count">Leg Count</label>
                <input type="number" class="form-control" name="stairstep_leg_count" value="{{$value->stairstep_leg_count}}">
            </div>
            <div class="col-md-2">
                <label for="stairstep_genealogy_color">Genealogy Color</label>
                <input type="text" class="form-control jscolor" id="stairstep_genealogy_color" name="stairstep_genealogy_color" value="{{$value->stairstep_genealogy_color == 'Default' ? '519fcd' : ltrim($value->stairstep_genealogy_color, '#')}}">
            </div> 
            <div class="col-md-2">
                <label for="stairstep_genealogy_border_color">Genealogy Border Color</label>
                <input type="text" class="form-control jscolor" id="stairstep_genealogy_border_color" name="stairstep_genealogy_border_color" value="{{$value->stairstep_genealogy_border_color == 'Default' ? '519fcd' : ltrim($value->stairstep_genealogy_border_color, '#')}}">
            </div> 
            <div class="col-md-1 pull-right">
                <br>
                <a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="javascript:" onClick="edit_stairstep({{$key}})">Save</a>
            </div>
        </form>
        </td>
    </tr>
    @endforeach
@endif

<tr>
    <td colspan="6">
    <form class="global-submit" method="post" action="/member/mlm/plan/rank/save" id="save_stairstep">
        {!! csrf_field() !!}
        <div class="col-md-2">
            <label for="stairstep_name">Rank Name</label>
            <input type="text" class="form-control" name="stairstep_name" value="" placeholder="Enter Rank Name">
        </div>
        <div class="col-md-1">
            <label for="stairstep_level">Rank Level</label>
            <select name="stairstep_level" class="form-control">
            @for($i = 1; $i < $rank_count_new; $i++)
                <option value="{{$i}}" <?php if($i == ($rank_count_new - 1)) echo "selected"; ?> >{{$i}}</option>
            @endfor
            </select>
        </div>
        <div class="col-md-2">
            <label for="stairstep_required_pv">Required Personal Sales</label>
            <input type="number" class="form-control" name="stairstep_required_pv" value="0">
        </div>
        <div class="col-md-2">
            <label for="stairstep_required_gv">Required Group Sales</label>
            <input type="number" class="form-control" name="stairstep_required_gv" value="0">
        </div>
        <div class="col-md-2">
                <label for="stairstep_required_gv">Personal Sales Maintenance</label>
                <input type="number" class="form-control" name="stairstep_pv_maintenance" value="">
        </div>            
        <div class="col-md-2">
                <label for="stairstep_required_gv">Commission Multiplier</label>
                <input type="number" class="form-control" name="commission_multiplier" value="">
        </div>
        <div class="col-md-1">
            <label for="stairstep_bonus">Bonus</label>
            <input type="number" class="form-control" name="stairstep_bonus" value="0">
        </div>       
        <div class="col-md-12"></div>
        <div class="col-md-2">
            <label for="direct_rank_bonus">Direct Bonus</label>
            <input type="number" class="form-control" name="direct_rank_bonus" value="0">
        </div>        
        <div class="col-md-2">
            <label for="stairstep_rebates_bonus">Rebates Bonus</label>
            <input type="number" class="form-control" name="stairstep_rebates_bonus" value="0">
        </div>
        <div class="col-md-2">
            <label for="stairstep_leg_id">Leg Rank</label>
            <select name="stairstep_leg_id" class="form-control">
                <option value="0">None</option>
            @foreach($rank as $rk)
                <option value="{{$rk->stairstep_id}}">{{$rk->stairstep_name}}</option>
            @endforeach
            </select>
        </div>
        <div class="col-md-1">
            <label for="stairstep_leg_count">Leg Count</label>
            <input type="number" class="form-control" name="stairstep_leg_count" value="0">
        </div>
        <div class="col-md-2">
            <label for="stairstep_genealogy_color">Genealogy Color</label>
            <input type="text" class="form-control jscolor" id="stairstep_genealogy_color" name="stairstep_genealogy_color" value="519fcd">
        </div>        
         <div class="col-md-2">
            <label for="stairstep_genealogy_border_color">Genealogy Border Color</label>
            <input type="text" class="form-control jscolor" id="stairstep_genealogy_border_color" name="stairstep_genealogy_border_color" value="519fcd">
        </div> 
        <div class="col-md-1 pull-right">
            <br>
            <a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="javascript:" onClick="save_stairstep()">Save</a>
        </div>
    </form>
    </td>
</tr>


