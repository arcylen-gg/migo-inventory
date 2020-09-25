@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Marketing Plan - Stairstep Direct</span>
                <small>
                    You can set the computation of your stairstep direct marketing plan here.
                </small>
            </h1>
            <a href="javascript:" class="panel-buttons btn btn-custom-blue pull-right" onClick="update_basic_settings()">Update</a>
            <a href="/member/mlm/plan" class="panel-buttons btn btn-custom-white pull-right">  Back</a>
        </div>
    </div>
</div>
{!! $basic_settings !!} 
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr><th colspan="3"><center>DIRECT SPONSOR BONUS PER MEMBERSHIP</center></th></tr>
                        <tr>
                            <th>MEMEBERSHIP NAME</th>
                            <th>STAIRSTEP DIRECT</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($membership))
                            @if(!empty($membership))
                                @foreach($membership as $key => $mem)
                                    <tr>
                                        <td>{{$mem->membership_name}}</td>
                                            <form id='form{{$mem->membership_id}}' action='/member/mlm/plan/binary/edit/membership/points' method='post' class='global-submit'>
                                                {!! csrf_field() !!}
                                                <input type="hidden" name="membership_id" value="{{$mem->membership_id}}">
                                                <input type='hidden' class='form-control stairstep_direct_points_container' name='stairstep_direct_points' value=''>
                                            </form>
                                            <td>
                                                <span class="stairstep_direct_points{{$mem->membership_id}}">{{$mem->stairstep_direct_points == "" ? 0 : $mem->stairstep_direct_points }}</span>
                                            </td>                                      
                                        <td>
                                            <span class="stairstep_direct_points_edit{{$mem->membership_id}}">
                                                <a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="javascript:" onClick="edit_stairstep_direct_points({{$mem->membership_id}},{{$mem->stairstep_direct_points}})">Edit</a>
                                            </span>
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
function save_include()
{
 $('#save_include').submit();   
}
function save_direct_points_membership(membershipid)
{
    var stairstep_direct_points      = $('.stairstep_direct_pointsinput' + membershipid).val();
    $(".stairstep_direct_points_container").val(stairstep_direct_points);

    console.log($('#form' + membershipid));
    $('#form' + membershipid).submit();
    cancel(membershipid);
}
function cancel(membershipid)
{
    var stairstep_direct_points = $('.stairstep_direct_pointsinput' + membershipid).val();
    stairstep_direct_points = parseInt(stairstep_direct_points);

    console.log(Number.isInteger(stairstep_direct_points));

    if(Number.isInteger(stairstep_direct_points) == true)
    {
        console.log(stairstep_direct_points);
        $('.stairstep_direct_points' + membershipid).html(stairstep_direct_points);
        
        var edit = '<a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="javascript:" onClick="edit_stairstep_direct_points('+membershipid+', '+stairstep_direct_points+')">Edit</a>';
        $('.stairstep_direct_points_edit' + membershipid).html(edit)
    }
}
function edit_stairstep_direct_points(membershipid,stairstep_direct_points)
{
    $('.stairstep_direct_points' + membershipid).html("<input type='number' class='form-control stairstep_direct_pointsinput"+ membershipid +"' name='stairstep_direct_points' value='"+stairstep_direct_points+"'>");

    $('.stairstep_direct_points_edit' + membershipid).html('<a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="#" onClick="save_direct_points_membership(' + membershipid +')">Save</a>');
}
</script>
@endsection
