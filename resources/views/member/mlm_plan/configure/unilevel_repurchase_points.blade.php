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
                        <tr><th><center>UNILEVEL BONUS PER MEMBERSHIP</center></th></tr>
                    </thead>
                    <tbody class="unilevel_body">
                    @if($membership)
                        @foreach($membership as $key => $value)
                            <tr>
                                <td>
                                    <form class="global-submit" id="form_uni_{{$value->membership_id}}"method="post" action="/member/mlm/plan/unilevel_repurchase_points/edit/membership/points">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="membership_id" value="{{$value->membership_id}}">
                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                            <label>Membership Name</label>
                                            <input type="text" class="form-control" value="{{$value->membership_name}}">
                                        </div>
                                        <div class="col-md-4">
                                            <label>No. of Levels</label>
                                            <input type="number" class="form-control" value="{{$unilevel_settings_count[$key]}}" membership_id="{{$value->membership_id}}" onChange="change_level(this)">
                                        </div>
                                        <div class="col-md-4">
                                            <a class="pull-right" href="javascript:" onClick="save_form({{$value->membership_id}})">Save</a>
                                        </div>
                                    </div>
                                    <div class="col-md-12 uni_{{$value->membership_id}}">
                                    @foreach($unilevel_settings[$key] as $key2 => $value2)
                                        <div class="col-md-4">
                                            <label>Level</label>
                                            <input type="number" class="form-control" value="{{$value2->unilevel_points_level}}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Amount/Percentage</label>
                                            <input type="number" class="form-control" value="{{$value2->unilevel_points_amount}}">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Type</label>
                                            <select class="form-control">
                                                <option value="0" {{$value2->unilevel_points_percentage == 0 ? 'selected' : ''}}>Fixed</option>
                                                <option value="1" {{$value2->unilevel_points_percentage == 1 ? 'selected' : ''}}>Percentage</option>
                                            </select>
                                        </div>
                                    @endforeach
                                    </div>

                                    </form>

                                </td>
                            </tr>

                        @endforeach
                    @else

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
function save_form(membership_id)
{
    $('#form_uni_' + membership_id).submit();
}
function change_level(ito)
{
    console.log(1);
    var level = $(ito).val();
    var membership_id = $(ito).attr('membership_id');

    var html_a = '';
    for(i = 0; i < level; i++)
    {
        html_a += '<div class="col-md-4">';
        html_a += '    <label>Level</label>';
        html_a += '    <input type="number" name="level[]" class="form-control" value="'+(i+1)+'" readonly>';
        html_a += '</div>';
        html_a += '<div class="col-md-4">';
        html_a += '    <label>Amount/Percentage</label>';
        html_a += '    <input type="number" name="amount[]" class="form-control" value="0">';
        html_a += '</div>';
        html_a += '<div class="col-md-4">';
        html_a += '    <label>Type</label>';
        html_a += '    <select class="form-control" name="type[]">';
        html_a += '        <option value="0">Fixed</option>';
        html_a += '        <option value="1">Percentage</option>';
        html_a += '    </select>';
        html_a += '</div>';
    }
    console.log(html_a);
        
    $('.uni_' +membership_id).html(html_a);
}
</script>
@endsection
