@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Marketing Plan - Triangle Repurchase</span>
                <small>
                    You can set the computation of your Triangle Repurchase marketing plan here.
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
                    <thead>
                        <th>Membership</th>
                        <th>Amount</th>
                        <th>Count</th>
                        <th>Income</th>
                        <th></th>
                    </thead>
                    <tbody class="unilevel_body">
                    @if($membership)
                        @foreach($membership as $key => $value)
                        <tr>
                            <td>{{$value->membership_name}}</td>
                            <td><input type="number" class="form-control" name="triangle_repurchase_amount" value="{{$value->settings->triangle_repurchase_amount}}" form="form_id_{{$value->membership_id}}"></td>
                            <td><input type="number" class="form-control" name="triangle_repurchase_count" value="{{$value->settings->triangle_repurchase_count}}" form="form_id_{{$value->membership_id}}"></td>
                            <td><input type="number" class="form-control" name="triangle_repurchase_income" value="{{$value->settings->triangle_repurchase_income}}" form="form_id_{{$value->membership_id}}"></td>
                            <td>
                            <form class="global-submit" method="post" action="/member/mlm/plan/triangle_repurchase/save" id="form_id_{{$value->membership_id}}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="shop_id" value="{{$value->settings->shop_id}}">
                                <input type="hidden" name="membership_id" value="{{$value->settings->membership_id}}">
                                <button class="btn btn-primary">Save</button>
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

</script>
@endsection
