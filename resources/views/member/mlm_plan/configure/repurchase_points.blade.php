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
                    @if(count($membership) >= 1)
                        @foreach($membership as $key => $value)
                            <tr>
                                <td>
                                    <form class="global-submit" id="form_mem_{{$value->membership_id}}"method="post" action="/member/mlm/plan/repurchase/edit/points">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="membership_id" value="{{$value->membership_id}}">
                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                            <label>Membership</label>
                                            <input type="text" class="form-control" value="{{$value->membership_name}}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Repuchase Points(Percentage)</label>
                                            <input type="text" class="form-control" name="membership_points_repurchase" value="{{$value->membership_points_repurchase}}">
                                        </div>
                                        <div class="col-md-4">
                                            <a href="javascript:" class="pull-right" onClick="submit_form({{$value->membership_id}})">Submit</a>
                                        </div>
                                    </div>
                                    </form>
                                </td>                        
                            </tr>
                        @endforeach
                    @else
                    <tr>
                        <td>
                            <div class="col-md-12">
                                <center>No Membership Available</center>
                            </div>
                        </td>                        
                    </tr>
                    @endif
                </table> 
            </div>  
        </div>
    </div>
</div>  
@endsection

@section('script')
<script type="text/javascript">
function submit_form (membership_id) {
    // body...
    console.log('1');
    $('#form_mem_' + membership_id).submit();
}
</script>
@endsection
