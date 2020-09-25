@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Marketing Plan - Indirect Points</span>
                <small>
                    You can set the computation of your indirect points marketing plan here.
                </small>
            </h1>
            <a href="javascript:" class="panel-buttons btn btn-custom-blue pull-right" onClick="update_basic_settings()">Update</a>
            <a href="/member/mlm/plan" class="panel-buttons btn btn-custom-white pull-right"> < Back</a>
        </div>
    </div>
</div>

{!! $basic_settings !!}

<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="table-responsive">
                <table class="table table-condensed">
                    <tbody>
                        @foreach($membership as $key => $value)
                            <tr>
                                <td>
                                    <form class="global-submit" action="/member/mlm/plan/indirect/edit/points" method="post" id="form_indirect_{{$value->membership_id}}">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="membership_id" value="{{$value->membership_id}}">
                                        <input type="hidden" name="level_count" class="level_count_hidden_{{$value->membership_id}}" value="0">
                                        <div clas="col-md-12">
                                            <div class="col-md-12">
                                                <div class="col-md-4"><lalel>Membership Name</lalel><input type="text" class="form-control" readonly value="{{$value->membership_name}}"></div>
                                                <div class="col-md-4"><lalel>Level Count</lalel><input type="number" class="form-control change_level"  membership_id="{{$value->membership_id}}" value="{{$membership_indirect_settings_count[$key]}}"></div>
                                                <div class="col-md-4"><a href="javascript:" class="pull-right" onClick="submit_form_indirect({{$value->membership_id}})">SAVE</a></div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 app_body_{{$value->membership_id}}">
                                            @foreach($membership_indirect_settings[$key] as $key2 => $value2)
                                                <div class="col-md-12">
                                                    <div class="col-md-4"><label>Level</label><input type="number" class="form-control" value="{{$value2->indirect_points_level}}" name="level[]" readonly></div>
                                                    <div class="col-md-4"><label>Points</label><input type="number" class="form-control" value="{{$value2->indirect_points_value}}" name="points[]"></div>
                                                    <div class="col-md-4"></div>
                                                </div>    
                                            @endforeach
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table> 
            </div>  
        </div>
    </div>
</div> 

<div class="col-md-12 base_level hide">
    
</div>
@endsection

@section('script')
<script type="text/javascript">
$(".change_level").unbind("change");
$(".change_level").bind("change",function()
{
    var number_of_level = $(this).val();
    var membership_id = $(this).attr('membership_id');
    var html_a = '';

    for(var i = 0; i < number_of_level; i++ )
    {
        var level_adjust = i + 1;
        html_a += '<div class="col-md-12"><div class="col-md-4"><label>Level</label><input type="number" class="form-control" value="'+level_adjust+'" name="level[]" readonly></div>';
        html_a += '<div class="col-md-4"><label>Points</label><input type="number" class="form-control" value="0" name="points[]"></div>';
        html_a += '<div class="col-md-4"></div></div>';
    }
    $('.level_count_hidden_'+ membership_id).val(number_of_level);
    $('.app_body_' + membership_id).html(html_a);
});
function submit_form_indirect(membership_id)
{
    $('#form_indirect_'+ membership_id).submit();
}
</script>
@endsection
