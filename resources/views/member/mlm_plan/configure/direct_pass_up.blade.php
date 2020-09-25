@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Marketing Plan - Direct Pass Up</span>
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
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr><th colspan="3"><center>DIRECT SPONSOR BONUS PER MEMBERSHIP PASS UP</center></th></tr>
                        <tr>
                            <th>MEMEBERSHIP NAME</th>
                            <th>DIRECT PASS UP SPONSOR BONUS</th>
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
                                                <input type='hidden' class='form-control membership_points_direct_pass_up_container' name='membership_points_direct_pass_up' value=''>
                                            </form>
                                            <td>
                                                <span class="membership_points_direct_pass_up{{$mem->membership_id}}">{{$mem->membership_points_direct_pass_up == "" ? 0 : $mem->membership_points_direct_pass_up }}</span>
                                            </td>                                      
                                        <td>
                                            <span class="membership_points_direct_pass_up_edit{{$mem->membership_id}}">
                                                <a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="javascript:" onClick="edit_direct_points('{{$mem->membership_id}}','{{$mem->membership_points_direct_pass_up}}')">Edit</a>
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
<div class="panel panel-default">
  <div class="panel-body">

    <div class="col-md-12">
        <div class="col-md-4">
            <label for="membership_name">How many numbers?</label>
            <input type="text" class="form-control" name="stairstep_level_count" value="{{$direct_count}}" id="stairstep_level_count" perma_value="{{$direct_count}}" onChange="change_level_append(this)">
        </div>

        <div class="col-md-1">
            <br>
            <a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="javascript:" onClick="save_direct_passup()">Save</a>
        </div>
    </div>

    <form class="global-submit" method="post" id="save_direct_passup" action="/member/mlm/plan/direct_pass_up/edit/settings">
        {!! csrf_field() !!}
        <div class="col-md-12" id="direct_number_level">
                @foreach($direct_number_settings as $settings)
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <label for="direct_number">Direct number</label>
                            <input type="number" class="form-control" name="direct_number[]" value="{{$settings->direct_number}}">
                        </div>
                    </div>
                @endforeach 
        </div>  
    </form>       

  </div>
</div>  
@endsection

@section('script')
<script type="text/javascript">
function save_direct_points_pass_up_membership(membershipid)
{
    var directpoints      = $('.membership_points_direct_pass_upinput' + membershipid).val();
    $(".membership_points_direct_pass_up_container").val(directpoints);

    console.log($('#form' + membershipid));
    $('#form' + membershipid).submit();
    cancel(membershipid);
}
function cancel(membershipid)
{
    var directpoints = $('.membership_points_direct_pass_upinput' + membershipid).val();
    directpoints = parseInt(directpoints);

    console.log(Number.isInteger(directpoints));

    if(Number.isInteger(directpoints) == true && Number.isInteger(directlimitpoints) == true)
    {
        console.log(directpoints);
        $('.membership_points_direct_pass_up' + membershipid).html(directpoints);
        
        var edit = '<a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="javascript:" onClick="edit_direct_points('+membershipid+', '+directpoints+')">Edit</a>';
        $('.membership_points_direct_pass_up_edit' + membershipid).html(edit)
    }
}
function edit_direct_points(membershipid,  directpoints)
{
    $('.membership_points_direct_pass_up' + membershipid).html("<input type='number' class='form-control membership_points_direct_pass_upinput"+ membershipid +"' name='membership_points_direct_pass_up' value='"+directpoints+"'>");

    $('.membership_points_direct_pass_up_edit' + membershipid).html('<a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="#" onClick="save_direct_points_pass_up_membership(' + membershipid +')">Save</a>');
}
function save_direct_passup()
{
 $('#save_direct_passup').submit();   
}
function change_level_append(ito)
{
    var no_of_level = ito.value;
    var html_to_append_u = "";
    for(i = 0; i < no_of_level; i++)
    {
        var ya = i+1;
        html_to_append_u += '<div class="col-md-12">';
        html_to_append_u += '<div class="col-md-3">';
        html_to_append_u += '<label for="direct_number">Number</label>';
        html_to_append_u += '<input type="number" class="form-control" name="direct_number[]" value="0">';
        html_to_append_u += '</div>';
        html_to_append_u += '</div>';
    }
    console.log(html_to_append_u);
    $('#direct_number_level').html(html_to_append_u);
}
</script>
@endsection
    