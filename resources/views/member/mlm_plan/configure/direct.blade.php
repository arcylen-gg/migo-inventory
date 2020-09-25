@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Marketing Plan - Direct</span>
                <small>
                    You can set the computation of your direct marketing plan here.
                </small>
            </h1>
            <a href="javascript:" class="panel-buttons btn btn-custom-blue pull-right" onClick="update_basic_settings()">Update</a>
            <a href="/member/mlm/plan" class="panel-buttons btn btn-custom-white pull-right">  Back</a>
        </div>
    </div>
</div>
{!! $basic_settings !!} 
@include('member.mlm_plan.notes.direct')
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr><th colspan="3"><center>DIRECT SPONSOR BONUS PER MEMBERSHIP</center></th></tr>
                        <tr>
                            <th>MEMEBERSHIP NAME</th>
                            <th>DIRECT SPONSOR BONUS</th>
                            <th>DIRECT INCOME LIMIT</th>
                            <th>GC INCOME</th>
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
                                                <input type='hidden' class='form-control membership_points_direct_container' name='membership_points_direct' value=''>
                                                <input type='hidden' class='form-control membership_direct_income_limit_container' name='membership_direct_income_limit' value=''>
                                                <input type='hidden' class='form-control membership_points_direct_gc_container' name='membership_points_direct_gc' value=''>
                                            </form>
                                            <td>
                                                <span class="membership_points_direct{{$mem->membership_id}}">{{$mem->membership_points_direct == "" ? 0 : $mem->membership_points_direct }}</span>
                                            </td>                                      
                                            <td>
                                                <span class="membership_points_direct_limit{{$mem->membership_id}}">{{$mem->membership_direct_income_limit == "" ? 0 : $mem->membership_direct_income_limit }}</span>
                                            </td>                                            
                                            <td>
                                                <span class="membership_points_direct_gc{{$mem->membership_id}}">{{$mem->membership_points_direct_gc == "" ? 0 : $mem->membership_points_direct_gc }}</span>
                                            </td>
                                        <td>
                                            <span class="membership_points_direct_edit{{$mem->membership_id}}">
                                                <a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="javascript:" onClick="edit_direct_points('{{$mem->membership_id}}','{{$mem->membership_points_direct}}', '{{$mem->membership_direct_income_limit}}','{{$mem->membership_points_direct_gc}}')">Edit</a>
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
    var directpoints      = $('.membership_points_directinput' + membershipid).val();
    var directlimitpoints = $('.membership_points_direct_limitinput' + membershipid).val();
    var directpointsgc    = $('.membership_points_direct_gcinput' + membershipid).val();
    $(".membership_points_direct_container").val(directpoints);
    $(".membership_direct_income_limit_container").val(directlimitpoints);
    $(".membership_points_direct_gc_container").val(directpointsgc);

    console.log($('#form' + membershipid));
    $('#form' + membershipid).submit();
    cancel(membershipid);
}
function cancel(membershipid)
{
    var directpoints = $('.membership_points_directinput' + membershipid).val();
    var directlimitpoints = $('.membership_points_direct_limitinput' + membershipid).val();
    var directpointsgc = $('.membership_points_direct_gcinput' + membershipid).val();
    directpoints = parseInt(directpoints);
    directlimitpoints = parseInt(directlimitpoints);
    directpointsgc = parseInt(directpointsgc);

    console.log(Number.isInteger(directpoints));
    console.log(Number.isInteger(directlimitpoints));

    if(Number.isInteger(directpoints) == true && Number.isInteger(directlimitpoints) == true)
    {
        console.log(directpoints);
        console.log(directlimitpoints);
        $('.membership_points_direct' + membershipid).html(directpoints);
        $('.membership_points_direct_limit' + membershipid).html(directlimitpoints);
        $('.membership_points_direct_gcinput' + membershipid).html(directpointsgc);
        
        var edit = '<a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="javascript:" onClick="edit_direct_points('+membershipid+', '+directpoints+','+directlimitpoints+','+directpointsgc+')">Edit</a>';
        $('.membership_points_direct_edit' + membershipid).html(edit)
    }
}
function edit_direct_points(membershipid,directpoints,income_limit,gc)
{
    $('.membership_points_direct' + membershipid).html("<input type='number' class='form-control membership_points_directinput"+ membershipid +"' name='membership_points_direct' value='"+directpoints+"'>");
    $('.membership_points_direct_limit' + membershipid).html("<input type='number' class='form-control membership_points_direct_limitinput"+ membershipid +"' name='membership_direct_income_limit' value='"+income_limit+"'>");
    $('.membership_points_direct_gc' + membershipid).html("<input type='number' class='form-control membership_points_direct_gcinput"+ membershipid +"' name='membership_points_direct_gc' value='"+gc+"'>");

    $('.membership_points_direct_edit' + membershipid).html('<a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="#" onClick="save_direct_points_membership(' + membershipid +')">Save</a>');
}
</script>
@endsection
