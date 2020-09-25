@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Marketing Plan - Stairstep</span>
                <small>
                    You can set the computation of your Stairstep marketing plan here.
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

    <form class="global-submit" method="post" id="save_dynamic" action="/member/mlm/plan/stairstep/edit/save_dynamic">
        {!! csrf_field() !!}
        <div class="col-md-12 pull">
            <label for="stairstep_dynamic_compression">Dynamic Compression</label>
            <input type="checkbox" id="stairstep_dynamic_compression" name="stairstep_dynamic_compression" value="1" {{$stairstep_dynamic_compression == 1 ? 'checked' : ''}}>
        </div>         
        <div class="col-md-1 pull-right">
            <a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="javascript:" onClick="save_dynamic()">Save</a>
        </div> 
    </form>       

  </div>
</div>


@endsection

@section('script')
<script type="text/javascript">
load_stair();
$("#stairstep_level_count").val($("#stairstep_level_count").attr("perma_value"))
function load_stair()
{
    $('.stair_body').html('<td colspan="6"><center><div style="margin: 100px auto;" class="loader-16-gray"></div></center></td>');
	$('.stair_body').load('/member/mlm/plan/stairstep/get');
}
function save_dynamic()
{
 $('#save_dynamic').submit();   
}
function edit_stairstep(key)
{
    $('#edit_form' + key).submit();
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
        html_to_append_u += '<option value="0" >Fixed</option>';
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
@endsection
