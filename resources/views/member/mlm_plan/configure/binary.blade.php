@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Marketing Plan - Binary</span>
                <small>
                    You can set the computation of your binary marketing plan here.
                </small>
            </h1>
            <a href="javascript:" class="panel-buttons btn btn-custom-blue pull-right" onClick="update_basic_settings()">Update</a>
            <a href="/member/mlm/plan" class="panel-buttons btn btn-custom-white pull-right"> < Back</a>
        </div>
    </div>
</div>

<div class="bsettings">
    {!! $basic_settings !!}  
</div>
<!-- Notes -->
@include('member.mlm_plan.notes.binary')
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
           <div class="table-responsive" >
                <table class="table table-condensed">
                    <tbody>
                        <tr>
                            <td>
                                @if(isset($advance_binary))
                                <form method="post" class="global-submit" action="/member/mlm/plan/binary/settings/submit" id="binary_advance_settings">
                                {!! csrf_field() !!}
                                <div class="col-md-12">
                                    <center>Advance Settings</center>
                                    <span class="pull-right">
                                        <a href="javascript:" onClick="submit_binary_advance()">Save</a>
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <label for="binary_settings_gc_enable"><small><span stype="color: gray">Enable Pairing GC</span></small></label>
                                    <select name="binary_settings_gc_enable" class="form-control">
                                        <option value="enable" {{$advance_binary->binary_settings_gc_enable == 'enable' ? 'selected' : ''}}>Enable</option>
                                        <option value="disable" {{$advance_binary->binary_settings_gc_enable == 'disable' ? 'selected' : ''}}>Disable</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="binary_settings_gc_enable"><small><span stype="color: gray">GC Every Pair</span></small></label>
                                    <input type="number" name="binary_settings_gc_every_pair" class="form-control" value="{{$advance_binary->binary_settings_gc_every_pair}}">
                                </div>
                                <div class="col-md-6">
                                    <label for="binary_settings_gc_title"><small><span stype="color: gray">GC Title</span></small></label>
                                    <input type="text" name="binary_settings_gc_title" class="form-control" value="{{$advance_binary->binary_settings_gc_title}}">
                                </div>
                                <div class="col-md-6">
                                    <label for="binary_settings_gc_amount_type"><small><span stype="color: gray">GC Income Type</span></small></label>
                                    <select name="binary_settings_gc_amount_type" class="form-control">
                                        <option value="fixed" {{$advance_binary->binary_settings_gc_amount_type == 'fixed' ? 'selected' : ''}}>Fixed</option>
                                        <option value="membership_based" {{$advance_binary->binary_settings_gc_amount_type == 'membership_based' ? 'selected' : ''}}>Membership Based</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    
                                </div>
                                <div class="col-md-6">
                                    <label for="binary_settings_gc_amount"><small><span stype="color: gray">GC Amount (For fixed type only)</span></small></label>
                                    <input type="number" name="binary_settings_gc_amount" class="form-control" value="{{$advance_binary->binary_settings_gc_amount}}">
                                </div>
                                <div class="col-md-12">
                                    <hr />
                                    <center>Binary Cycle</center>
                                    <br />  
                                </div>

                                <div class="col-md-6">
                                    <label for="binary_settings_no_of_cycle"><small><span stype="color: gray">No. Of Cycle</span></small></label>
                                    <select name="binary_settings_no_of_cycle" class="form-control" onchange="change_no_of_cycle(this)">
                                        <option value="1" {{$advance_binary->binary_settings_no_of_cycle == '1' ? 'selected' : ''}}>1</option>
                                        <option value="2" {{$advance_binary->binary_settings_no_of_cycle == '2' ? 'selected' : ''}}>2</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="binary_settings_time_of_cycle"><small><span stype="color: gray">Time Of Cycle (Every) </span></small></label>
                                    <!-- mlm_plan_release_schedule_hour -->
                                    <span class="hours_cycle">
                                    @if($advance_binary->binary_settings_no_of_cycle == 1)
                                    {!! mlm_plan_release_schedule_hour($advance_binary->binary_settings_time_of_cycle) !!}
                                    @else
                                     {!! mlm_plan_release_schedule_hour_12($advance_binary->binary_settings_time_of_cycle) !!}
                                    @endif
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <label for="binary_settings_no_of_cycle"><small><span stype="color: gray">Enable Strong Leg Retention</span></small></label>
                                    <select name="binary_settings_strong_leg" class="form-control">
                                        <option value="strong_leg" {{$advance_binary->binary_settings_strong_leg == 'strong_leg' ? 'selected' : ''}}>Enable</option>
                                        <option value="no_strong_leg" {{$advance_binary->binary_settings_strong_leg == 'no_strong_leg' ? 'selected' : ''}}>Disable</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="binary_settings_max_tree_level"><small><span stype="color: gray">Maximum Tree Level</span></small></label>
                                    <input type="text" class="form-control" name="binary_settings_max_tree_level" value="{{$advance_binary->binary_settings_max_tree_level}}">  
                                </div>
                                <div class="col-md-12">
                                    <hr />
                                    <center>Binary Placement</center>
                                    <br />
                                </div>
                                <div class="col-md-6">
                                    <select name="binary_settings_placement" class="form-control" onchange="binary_settings_placement_change(this)">
                                        <option value="0" {{$advance_binary->binary_settings_placement == 0 ? 'selected' : ''}}>Manual</option>
                                        <option value="1" {{$advance_binary->binary_settings_placement == 1 ? 'selected' : ''}}>Auto</option>
                                    </select>
                                </div>
                                <div class="col-md-6 binary_settings_class">
                                    @if($advance_binary->binary_settings_placement == 0)
                                        <input type='hidden' name='binary_settings_auto_placement' value='0'>
                                    @else
                                        <select name="binary_settings_auto_placement" class="form-control">
                                            <option value="left_to_right" @if($advance_binary->binary_settings_auto_placement == 'left_to_right') selected @endif >LEFT TO RIGHT</option>
                                            <option value="auto_balance"  @if($advance_binary->binary_settings_auto_placement == 'auto_balance') selected @endif >AUTO BALANCE</option>
                                        </select>
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <hr />
                                    <center>Binary Type</center>
                                    <br />
                                </div>
                                <div class="col-md-6">
                                    <label for="binary_settings_max_tree_level"><span stype="color: gray"><small>Points/Matrix</small></span></label>
                                    <select class="form-control" name="binary_settings_type">
                                        <option value="0" {{$advance_binary->binary_settings_type == 0 ? 'selected' : ''}}>Points System</option>
                                        <option value="1" {{$advance_binary->binary_settings_type == 1 ? 'selected' : ''}}>Triangle Matrix</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="binary_settings_max_tree_level"><span stype="color: gray"><small>Earning for triangle matrix</small></span></label>
                                    <input type="number" class="form-control" name="binary_settings_matrix_income" value="{{$advance_binary->binary_settings_matrix_income}}">
                                </div>
                                </form>
                                @else
                                    <center>Something Went Wrong Please Contact The Administator</center>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>                
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
           <div class="table-responsive" >
                <table class="table table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr><th colspan="3"><center>MEMBERSHIP POINTS PER ENTRY</center></th></tr>
                        <tr>
                            <th>MEMEBERSHIP NAME</th>
                            <th>
                                <div class="col-md-3">BINARY POINTS</div>
                                <div class="col-md-3">BINARY POINTS LIMIT</div>
                                <div class="col-md-3 hide">BINARY SINGLE LINE INCOME</div>
                                <div class="col-md-3 hide">BINARY SINGLE LINE INCOME LIMIT</div>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($membership))
                            @if(!empty($membership))
                                @foreach($membership as $key => $mem)
                                
                                    <tr class="tr{{$mem->membership_id}}">
                                        <td>{{$mem->membership_name}}</td>
                                        <td>
                                            <form id='form{{$mem->membership_id}}' action='/member/mlm/plan/binary/edit/membership/points' method='post' class='global-submit'>
                                            {!! csrf_field() !!}
                                            <input type="hidden" name="membership_id" value="{{$mem->membership_id}}">
                                            
                                            <div class="col-md-3">
                                                <span class="membership_binary_points{{$mem->membership_id}}">{{$mem->membership_points_binary != null ? $mem->membership_points_binary : 0 }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <span class="membership_binary_limit_points{{$mem->membership_id}}">{{$mem->membership_points_binary_limit  != null ? $mem->membership_points_binary_limit  : 0 }}</span>
                                            </div>
                                            <div class="col-md-3 hide">
                                                <input type="number" class="form-control" name="membership_points_binary_single_line" value="{{$mem->membership_points_binary_single_line}}">
                                            </div>
                                            <div class="col-md-3 hide">
                                                <input type="number" class="form-control" name="membership_points_binary_single_line_limit" value="{{$mem->membership_points_binary_single_line_limit}}">
                                            </div>
                                            </form> 
                                        </td>
                                        <td><span class="membership_binary_points_edit{{$mem->membership_id}}"><a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="javascript:" onClick="edit_binary_points('{{$mem->membership_id}}', '{{$mem->membership_name}}', '{{$mem->membership_points_binary}}', '{{$mem->membership_points_binary_limit}}')">Edit</a></span></td>
                                    </tr>
                                @endforeach
                            @else
                            <tr>
                                <td colspan="3"><center>No Active Membership</center></td>
                            </tr>
                            @endif
                        @endif
                    </tbody>
                </table> 
            </div>
            <div>
                <hr>
            </div>   
            <div class="table-responsive" >
                <table class="table table-condensed">
                        <thead style="text-transform: uppercase">
                        <tr>
                            <th colspan="3"> <center>PAIRING COMBINATION</center></th>
                        </tr>
                        <tr>
                            <th>MEMBERSHIP NAME</th>
                            <th>NUMBER OF PAIRING</th>
                            <th>MAX PAIR PER CYCLE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($membership))
                            @if(!empty($membership))
                                @foreach($membership as $key => $mem)
                                    <tr class="binarypairingtr{{$mem->membership_id}}">
                                        <td>{{$mem->membership_name}}</td>
                                        <td>{{$mem->paring_count}}</td>
                                        <td>
                                            {{$mem->membership_points_binary_max_pair == null ? 0 : $mem->membership_points_binary_max_pair }}
                                            <a data-toggle="tooltip" data-placement="left" class="pull-right" title="Tooltip on left" href="javascript:" onClick="edit_binary_pairing({{$mem->membership_id}})">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                            <tr>
                                <td colspan="3"><center>No Active Membership</center></td>
                            </tr>
                            @endif
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>    
@endsection

@section('script')
<script type="text/javascript">
var hours_12 = "{!! mlm_plan_release_schedule_hour() !!}";
var hours_24 = "{!! mlm_plan_release_schedule_hour_12() !!}";

var manual_p = "<input type='hidden' name='binary_settings_auto_placement' value='0'>";
var auto_p = '<select name="binary_settings_auto_placement" class="form-control"><option value="left_to_right" >LEFT TO RIGHT</option><option value="auto_balance" >AUTO BALANCE</option></select>';
function change_no_of_cycle(ito)
{
    console.log(ito.value);
    if(ito.value == 1)
    {
        $('.hours_cycle').html(hours_12);
    }
    else
    {
        $('.hours_cycle').html(hours_24);
    }
}
function submit_binary_advance()
{
    $('#binary_advance_settings').submit();
}
function edit_binary_pairing(membership_id)
{
    // /member/mlm/plan/binary/get/membership/pairing/
    $('.binarypairingtr' + membership_id).html('<td colspan="4"><center><div style="margin: 100px auto;" class="loader-16-gray"></div></center></td>');
    $('.binarypairingtr' + membership_id).load('/member/mlm/plan/binary/get/membership/pairing/' + membership_id);
}
function save_binary_points_membership(membershipid)
{
    console.log($('#form' + membershipid));
    $('#form' + membershipid).submit();
    cancel(membershipid);
}
function cancel(membershipid)
{
    // var binarypoints = $('.membership_points_binaryinput' + membershipid).val();
    // var binarypoints_limit = $('.membership_binary_limit_pointsinput' + membershipid).val();
    // $('.membership_binary_points' + membershipid).html(binarypoints);
    // $('.membership_binary_points_limit' + membershipid).html(binarypoints);
    // var edit = '<a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="javascript:" onClick="edit_binary_points('+membershipid+',membership_name, '+binarypoints+')">Edit</a>';
    // $('.membership_binary_points_edit' + membershipid).html(edit);
}
function edit_binary_points(membershipid, membership_name, binarypoints, binarypoints_limit)
{
    $('.membership_binary_points' + membershipid).html("<input type='number' class='form-control membership_points_binaryinput"+ membershipid +"' name='membership_points_binary' value='"+binarypoints+"'>");
    $('.membership_binary_limit_points' + membershipid).html("<input type='number' class='form-control membership_binary_limit_pointsinput"+ membershipid +"' name='membership_points_binary_limit' value='"+binarypoints_limit+"'>");
    $('.membership_binary_points_edit' + membershipid).html('<a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="#" onClick="save_binary_points_membership(' + membershipid +')">Save</a>');
}
function binary_settings_placement_change(ito)
{
    console.log(ito.value);
    if(ito.value == 0)
    {
        $('.binary_settings_class').html(manual_p);
    }
    else if (ito.value == 1)
    {
        $('.binary_settings_class').html(auto_p);
    }
}
</script>
@endsection
