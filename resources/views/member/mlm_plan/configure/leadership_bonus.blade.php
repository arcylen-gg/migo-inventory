@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Marketing Plan - LEADERSHIP BONUS</span>
                <small>
                    You can set the computation of your leadership bonus marketing plan here.
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
                    

                    <tr>
                        <td>
                            <div class="col-md-6">
                                <div class="col-md-5">Membership</div>
                                <div class="col-md-5">Leadership Points</div>
                                <div class="col-md-2"></div>
                            </div>    
                        </td>
                    </tr>
                    @if($membership)
                        @foreach($membership as $key => $value)
                            <tr>
                                <td>
                                    <form action="/member/mlm/plan/leadership/edit/points" id="mem_{{$value->membership_id}}" class="global-submit" method="post">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="membership_id" value="{{$value->membership_id}}">
                                        <div class="col-md-6">
                                            <div class="col-md-5">{{$value->membership_name}}</div>
                                            <div class="col-md-5"><input type="number" class="form-control" name="membership_points_leadership" value="{{$value->membership_points_leadership}}"></div>
                                            <div class="col-md-2"><a href="javascript:" class="pull-right" onClick="save_form_leadership({{$value->membership_id}})">Save</a></div>
                                        </div>
                                    </form>
                                </td>
                            </tr>

                        @endforeach
                    @else

                    @endif
                </table>
            </div>
        </div>
    </div>
</div> 
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="table-responsive">
                <table class="table table-condensed">
                    <tr>
                        <td class="bg-warning">
                            <center>Beta: 1 combination per membership only. Exceeding will cause error, ask for luke's assitance before editting.</center>
                        </td>
                    </tr>
                    @if($membership)
                        @foreach($membership as $key => $value)
                            <tr>
                                <td>
                                    <form class="global-submit" action="/member/mlm/plan/leadership/edit/matching" method="post" id="membership_matching{{$value->membership_id}}">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="membership_id" value="{{$value->membership_id}}">
                                        <div class="col-md-12">
                                            <div class="col-md-4">MEMBERSHIP NAME</div>
                                            <div class="col-md-4">COMBINATION COUNT</div>
                                            <div class="col-md-4"></div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-4"><input type="text" class="form-control" value="{{$value->membership_name}}" readonly></div>
                                            <div class="col-md-4">
                                                <input type="number" class="form-control" name="count" min="0" max="1" value="{{$leadership_bonus_settings_count[$key]}}" membership_id={{$value->membership_id}} onchange="change_combination_count(this)">
                                            </div>
                                            <div class="col-md-4">
                                                <a href="javascript:" class="pull-right" onClick="save_membership_matching({{$value->membership_id}})">Save</a>
                                            </div>
                                        </div>

                                        <div class="col-md-12 leadership_count_body{{$value->membership_id}}">
                                        @if(count ($leadership_bonus_settings_get[$key]) >= 1)
                                            @foreach($leadership_bonus_settings_get[$key] as $ke2 => $value2)
                                                <div class="col-md-2">
                                                    <label>Start</label>
                                                    <input type="number" class="form-control" name="start[]" value="{{$value2->leadership_settings_start}}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>End</label>
                                                    <input type="number" class="form-control" name="end[]" value="{{$value2->leadership_settings_end}}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Points Needed</label>
                                                    <input type="number" class="form-control" name="required[]" value="{{$value2->leadership_settings_required_points}}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Earnings</label>
                                                    <input type="number" class="form-control" name="earnings[]" value="{{$value2->leadership_settings_earnings}}">
                                                </div>
                                            @endforeach
                                        @else
                                        <div class="col-md-12"><center>Set your combination count.</center></div>
                                        @endif    
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>       
<div class="col-md-12 base_append hide">
    <div class="col-md-2">
            <label>Start</label>
            <input type="number" class="form-control" name="start[]" value="0">
        </div>
        <div class="col-md-2">
            <label>End</label>
            <input type="number" class="form-control" name="end[]" value="0">
        </div>
        <div class="col-md-4">
            <label>Points Needed</label>
            <input type="number" class="form-control" name="required[]" value="0">
        </div>
        <div class="col-md-4">
            <label>Earnings</label>
            <input type="number" class="form-control" name="earnings[]" value="0">
        </div>
</div>                                  
@endsection

@section('script')
<script type="text/javascript">
    function save_form_leadership (membership_id) {
        // body...
        $('#mem_' + membership_id).submit();
    }
    function change_combination_count(ito)
    {
        var base_append = $('.base_append').html();
        var membership_id = $(ito).attr('membership_id');
        var count = ito.value;

        var html_append = "";
        for(var i = 0; i < count; i++)
        {
            html_append += base_append;
        }

        $('.leadership_count_body' + membership_id).html(html_append);
    }
    function save_membership_matching(membership_id)
    {
        $('#membership_matching' + membership_id).submit();
    }
</script>
@endsection
