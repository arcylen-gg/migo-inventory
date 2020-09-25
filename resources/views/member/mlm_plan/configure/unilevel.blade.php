@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Marketing Plan - Unilevel</span>
                <small>
                    You can set the computation of your unilevel marketing plan here.
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
                        <tr><th colspan="3"><center>UNILEVEL BONUS PER MEMBERSHIP</center></th></tr>
                        <tr>
                            <th>MEMEBERSHIP NAME</th>
                            <th>NUMBER OF LEVELS</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="unilevel_body">
                        
                    </tbody>
                </table> 
            </div>  
        </div>
    </div>
</div>  
@endsection

@section('script')
<script type="text/javascript">
load_unilevel_body();
function load_unilevel_body()
{
    $('.unilevel_body').html('<tr><td colspan="3"><center><div style="margin: 100px auto;" class="loader-16-gray"></div></center></td></tr>');    
 $('.unilevel_body').load('/member/mlm/plan/unilevel/get/all');   
}
function editunilevel(membership_id)
{
    $('.tr_to_appen_indirect_per_membership' + membership_id).html('<td colspan="3"><center><div style="margin: 100px auto;" class="loader-16-gray"></div></center></td>');
    $('.tr_to_appen_indirect_per_membership' + membership_id).load('/member/mlm/plan/unilevel/get/single/' +membership_id);
}
function change_level_append(ito)
{
    var membership_id = $(ito).attr('membership_id');
    console.log(membership_id);
    var no_of_level = ito.value;
    var html_to_append_u = "";
    for(i = 0; i < no_of_level; i++)
    {
        var ya = i+1;
        html_to_append_u += '<div class="col-md-12">';
        html_to_append_u += '<div class="col-md-3">';
        html_to_append_u += '<label for="unilevel_settings_level">Level</label>';
        html_to_append_u += '<input type="number" class="form-control" name="unilevel_settings_level[]" value="'+ya+'" readonly>';
        html_to_append_u += '</div>';
        html_to_append_u += '<div class="col-md-3">';
        html_to_append_u += '<label for="unilevel_settings_amount">Amount</label>';
        html_to_append_u += '<input type="number" class="form-control" name="unilevel_settings_amount[]" value="0">';
        html_to_append_u += '</div>';
        html_to_append_u += '<div class="col-md-3">';
        html_to_append_u += '<label for="unilevel_settings_percent">Type</label>';
        html_to_append_u += '<select class="form-control" name="unilevel_settings_percent[]">';
        html_to_append_u += '<option value="0" >Fixed</option>';
        html_to_append_u += '<option value="1" >Percentage</option>';
        html_to_append_u += '</select>';
        html_to_append_u += '</div><div class="col-md-3">';
        html_to_append_u += '<label>Earned Type</label>';
        html_to_append_u += '<select class="form-control" name="unilevel_settings_type[]">';
        html_to_append_u += '<option value="0">Points</option>';
        html_to_append_u += '<option value="1">Cash</option>';
        html_to_append_u += '</select>';
        html_to_append_u += '</div>';
        html_to_append_u += '</div>';
    }
    console.log(html_to_append_u);
    $('#unilevel_per_level' + membership_id).html(html_to_append_u);
}
function submit_form_unilevel(membership_id)
{
    $('#form_unilevel' + membership_id).submit();
}
</script>
@endsection
