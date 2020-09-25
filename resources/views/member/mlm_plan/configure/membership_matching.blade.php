@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Marketing Plan - Membership Matching</span>
                <small>
                    You can set the computation of your membership matching marketing plan here.
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

<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="table-responsive">
                <table class="table table-condensed">
                    @if(count($membership) >= 1)
                        @foreach($membership as $key => $value)
                        <tr>
                            <td>
                            <form class="global-submit" id="form_membership_matching{{$value->membership_id}}"role="form" action="/member/mlm/plan/matching/add" method="post">
                            {!! csrf_field() !!}
                            <input type="hidden" name="membership_id" value="{{$value->membership_id}}">
                                Membership Name: {{$value->membership_name}}
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <label>No of Combination</label>
                                        <input type="number" class="form-control" membership_id="{{$value->membership_id}}" value="{{$membership_matching_count[$key]}}" min="0" max="99" onChange="change_combination_no(this)">
                                    </div>
                                    <div class="col-md-8">
                                         <a href="javascript:" class="panel-buttons btn btn-custom-blue pull-right" onClick="submit_form({{$value->membership_id}})">Save Settings</a>
                                    </div>
                                </div>
                                <div class="col-md-12 combination_append{{$value->membership_id}}">
                                    @foreach($membership_matching[$key] as $key2 => $value2)
                                        <div class="col-md-12">
                                            <div class="col-md-2"><label>Start of level</label><input type="number" name="start[]" class="form-control" value="{{$value2->matching_settings_start}}" min="0"></div>
                                            <div class="col-md-2"><label>End of level</label><input type="number" name="end[]" class="form-control" value="{{$value2->matching_settings_end}}" min="0"></div>
                                            <div class="col-md-2"><label>Earning</label><input type="number" name="earn[]" class="form-control" value="{{$value2->matching_settings_earnings}}" min="0" ></div>
									        <div class="col-md-2"><label>Gc Every</label><input type="number" name="gc_count[]" class="form-control" value="{{$value2->matching_settings_gc_count}}" min="0" ></div>
									        <div class="col-md-2"><label>Gc Amount</label><input type="number" name="gc_amount[]" class="form-control" value="{{$value2->matching_settings_gc_amount}}" min="0" ></div>
                                        </div>
                                    @endforeach
                                </div>
                            </form>    
                            </td>
                        </tr>
                        @endforeach
                        
                    @else
                        <tr><center>No Membership Found.</center></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<div class="hide combination">
    <div class="col-md-12">
        <div class="col-md-2"><label>Start of level</label><input type="number" name="start[]" class="form-control" value="0" min="0"></div>
        <div class="col-md-2"><label>End of level</label><input type="number" name="end[]" class="form-control" value="0" min="0"></div>
        <div class="col-md-2"><label>Earning</label><input type="number" name="earn[]" class="form-control" value="0" min="0" ></div>
        <div class="col-md-2"><label>Gc Every</label><input type="number" name="gc_count[]" class="form-control" value="0" min="0" ></div>
        <div class="col-md-2"><label>Gc Amount</label><input type="number" name="gc_amount[]" class="form-control" value="0" min="0" ></div>
    </div>
</div>            
@endsection

@section('script')
<script type="text/javascript">
function change_combination_no(ito)
{
    var no = ito.value;
    var id = $(ito).attr('membership_id');
    var html = "";
    for(i= 0; i < no; i++)
    {
        html += $('.combination').html();
    }
    $('.combination_append' + id).html(html);
}
function submit_form(id)
{
    $('#form_membership_matching' + id).submit();
}
</script>
@endsection
