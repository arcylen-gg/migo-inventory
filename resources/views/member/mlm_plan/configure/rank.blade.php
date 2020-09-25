@extends('member.layout')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.1/css/bootstrap-colorpicker.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.1/css/bootstrap-colorpicker.min.css" />
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Marketing Plan - Rank</span>
                <small>
                    You can set the computation of your Rank marketing plan here.
                </small>
            </h1>
            <a href="javascript:" class="panel-buttons btn btn-custom-blue pull-right" onClick="update_basic_settings()">Update</a>
            <a href="/member/mlm/plan" class="panel-buttons btn btn-custom-white pull-right"> < Back</a>
        </div>
    </div>
</div>
{!! $basic_settings !!}


<div class="panel panel-default">
  <div class="panel-body">

    <form class="global-submit" method="post" id="save_include" action="/member/mlm/plan/rank/edit/save_include">
        {!! csrf_field() !!}
        <div class="col-md-12 pull">
            <label for="include_rpv_on_rgpv">Include RPV on RGPV</label>
            <input type="checkbox" id="include_rpv_on_rgpv" name="include_rpv_on_rgpv" value="1" {{$include_rpv_on_rgpv == 1 ? 'checked' : ''}}>
        </div>         
        <div class="col-md-12 pull">
            <label for="rank_real_time_update">Real-time update</label>
            <input type="checkbox" id="rank_real_time_update" name="rank_real_time_update" value="1" {{$rank_real_time_update == 1 ? 'checked' : ''}}>
        </div>        
        <div class="col-md-12 pull">
            <label for="rank_update_email">Enable Email Send Rank Up</label>
            <input type="checkbox" id="rank_update_email" name="rank_update_email" value="1" {{$rank_update_email == 1 ? 'checked' : ''}}>
        </div>         
        <div class="col-md-12 pull">
            <label for="rank_real_time_update_counter">Update month range (0 = All range)</label>
            <input type="number" class="form-control" id="rank_real_time_update_counter" name="rank_real_time_update_counter" value="{{$rank_real_time_update_counter}}">
        </div>         
        <div class="col-md-1 pull-right">
            <a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="javascript:" onClick="save_include()">Save</a>
        </div> 
    </form>       

  </div>
</div>

<div class="panel panel-default">
  <div class="panel-body">

    <div class="col-md-12">
        <div class="col-md-4">
            <label for="membership_name">No. of levels</label>
            <input type="text" class="form-control" name="stairstep_level_count" value="{{$stair_count}}" id="stairstep_level_count" perma_value="{{$stair_count}}" onChange="change_level_append(this)">
        </div>

        <div class="col-md-1">
            <br>
            <a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="javascript:" onClick="save_stairstep_level()">Save</a>
        </div>
    </div>

    <form class="global-submit" method="post" id="stairstep_level_form" action="/member/mlm/plan/rank/edit/save_level">
        {!! csrf_field() !!}
        <div class="col-md-12" id="stairstep_per_level">
                @foreach($points_settings as $settings)
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <label for="stairstep_settings_level">Level</label>
                            <input type="number" class="form-control" name="stairstep_settings_level[]" value="{{$settings->stairstep_points_level}}" readonly>
                        </div>

                        <div class="col-md-3">
                            <label for="stairstep_settings_amount">Amount</label>
                            <input type="number" class="form-control" name="stairstep_settings_amount[]" value="{{$settings->stairstep_points_amount}}">
                        </div>

                        <div class="col-md-3">
                            <label for="stairstep_settings_percent">Type</label>
                            <select class="form-control" name="stairstep_settings_percent[]">
                                <!-- <option value="0" {{$settings->stairstep_points_percentage == 0 ? "selected" : ""}}>Fixed</option> -->
                                <option value="1" {{$settings->stairstep_points_percentage == 1 ? "selected" : ""}}>Percentage</option>
                            </select>
                        </div>
                    </div>
                @endforeach 
        </div>  
    </form>       

  </div>
</div>

<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr><th colspan="6"><center>RANK BONUS</center></th></tr>
                        <!--<tr>-->
                        <!--    <th>RANK</th>-->
                        <!--    <th>LEVEL</th>-->
                        <!--    <th>REQUIRED PERSONAL SALES</th>-->
                        <!--    <th>REQUIRED GROUP SALES</th>-->
                        <!--    <th>BONUS</th>-->
                        <!--    <th></th>-->
                        <!--</tr>-->
                    </thead>
                    <tbody class="stair_body">
                        {!! $stair_get !!}
                        
                    </tbody>
                </table> 
            </div>  
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
$("#stairstep_level_count").val($("#stairstep_level_count").attr("perma_value"));
function save_include()
{
 $('#save_include').submit();   
}
function edit_stairstep(key)
{  
    $.ajax(
    {
     type: "POST",
      url: "/member/mlm/plan/rank/edit/save",
      data: $("#edit_form"+key).serialize(),
      success: function(data) 
      {
        data = jQuery.parseJSON(data);

        if(data.response_status == "success_edit_stairstep")
        {
            toastr.success(data.response_rank_name+" successfully saved.");
        }
        else
        {
            // console.log(data.response_warning);
            $(data.response_warning).each(function( index, element ) 
            {
                toastr.error(element);
            });
        }
      },
      error: function()
      {
        toastr.error("Some error occurred");
      }
    });
}
function save_stairstep_level()
{
    $('#stairstep_level_form').submit();
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
        html_to_append_u += '<label for="stairstep_settings_level">Level</label>';
        html_to_append_u += '<input type="number" class="form-control" name="stairstep_settings_level[]" value="'+ya+'" readonly>';
        html_to_append_u += '</div>';
        html_to_append_u += '<div class="col-md-3">';
        html_to_append_u += '<label for="stairstep_settings_amount">Amount</label>';
        html_to_append_u += '<input type="number" class="form-control" name="stairstep_settings_amount[]" value="0">';
        html_to_append_u += '</div>';
        html_to_append_u += '<div class="col-md-3">';
        html_to_append_u += '<label for="stairstep_settings_percent">Type</label>';
        html_to_append_u += '<select class="form-control" name="stairstep_settings_percent[]">';
        // html_to_append_u += '<option value="0" >Fixed</option>';
        html_to_append_u += '<option value="1" >Percentage</option>';
        html_to_append_u += '</select>';
        html_to_append_u += '</div>';
        // html_to_append_u += '<div class="col-md-3">';
        // html_to_append_u += '<label>Earned Type</label>';
        // html_to_append_u += '<select class="form-control" name="stairstep_settings_type[]">';
        // html_to_append_u += '<option value="0">Points</option>';
        // html_to_append_u += '<option value="1">Cash</option>';
        // html_to_append_u += '</select>';
        // html_to_append_u += '</div>';
        html_to_append_u += '</div>';
    }
    console.log(html_to_append_u);
    $('#stairstep_per_level').html(html_to_append_u);
}
</script>
<script type="text/javascript" src="/assets/member/mlm_plan/color_picker.js"></script>
<script type="text/javascript">
load_stair();
function load_stair()
{
    $('.stair_body').html('<td colspan="6"><center><div style="margin: 100px auto;" class="loader-16-gray"></div></center></td>');
    $('.stair_body').load('/member/mlm/plan/rank/get',function()
    {
        color_picker_function();
        jscolor.installByClassName("jscolor");
    });
}
function save_stairstep()
{
    $.ajax(
    {
     type: "POST",
      url: "/member/mlm/plan/rank/save",
      data: $("#save_stairstep").serialize(),
      success: function(data) 
      {
        data = jQuery.parseJSON(data);

        if(data.response_status == "success_add_stairstep")
        {
            toastr.success(data.response_rank_name+" successfully saved.");
            load_stair();
        }
        else
        {
            // console.log(data.response_warning);
            $(data.response_warning).each(function( index, element ) 
            {
                toastr.error(element);
            });
        }
      },
      error: function()
      {
        toastr.error("Some error occurred");
      }
    });
}
</script>
@endsection
