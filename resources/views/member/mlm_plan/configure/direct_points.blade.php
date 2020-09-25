@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Marketing Plan - Direct Points</span>
                <small>
                    You can set the computation of your direct points marketing plan here.
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
                        <tr><th colspan="3"><center>DIRECT POINTS PER MEMBERSHIP</center></th></tr>
                        <tr>
                            <th>MEMEBERSHIP NAME</th>
                            <th>DIRECT POINTS</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($membership))
                            @if(!empty($membership))
                                @foreach($membership as $key => $mem)
                                    <tr>
                                        <td>{{$mem->membership_name}}</td>
                                        <td>
                                            <form id='form{{$mem->membership_id}}' action='/member/mlm/plan/direct/edit/points' method='post' class='global-submit'>
                                            {!! csrf_field() !!}
                                            <input type="hidden" name="membership_id" value="{{$mem->membership_id}}">
                                            <span class="membership_points_direct{{$mem->membership_id}}">{{$mem->membership_points_direct_not_bonus == "" ? 0 : $mem->membership_points_direct_not_bonus }}</span>
                                            </form>
                                            </td>
                                        <td>
                                            <span class="membership_points_direct_edit{{$mem->membership_id}}">
                                                <a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="javascript:" onClick="edit_direct_points('{{$mem->membership_id}}','{{$mem->membership_points_direct_not_bonus}}')">Edit</a>
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
function save_direct_points_membership(membershipid)
{
    console.log($('#form' + membershipid));
    $('#form' + membershipid).submit();
    cancel(membershipid);
}
function cancel(membershipid)
{
    var directpoints = $('.membership_points_directinput' + membershipid).val();
    directpoints = parseInt(directpoints);
    console.log(Number.isInteger(directpoints));
    if(Number.isInteger(directpoints) == true)
    {
        console.log(directpoints);
        $('.membership_points_direct' + membershipid).html(directpoints);
        var edit = '<a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="javascript:" onClick="edit_direct_points('+membershipid+', '+directpoints+')">Edit</a>';
        $('.membership_points_direct_edit' + membershipid).html(edit)
    }
}
function edit_direct_points(membershipid,  directpoints)
{
    $('.membership_points_direct' + membershipid).html("<input type='number' class='form-control membership_points_directinput"+ membershipid +"' name='membership_points_direct' value='"+directpoints+"'>");
    $('.membership_points_direct_edit' + membershipid).html('<a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="#" onClick="save_direct_points_membership(' + membershipid +')">Save</a>');
}
</script>
@endsection
