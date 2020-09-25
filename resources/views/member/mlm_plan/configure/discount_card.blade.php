@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Marketing Plan - Discount Card</span>
                <small>
                    You can set the computation of your Discount Card marketing plan here.
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
                        <tr><th><center>DISCOUNT CARD</center></th></tr>
                    </thead>
                    <tbody class="unilevel_body">
                    @if($membership)
                        @foreach($membership as $key => $value)
                            <tr>
                                <td>
                                    <form class="global-submit" action="/member/mlm/plan/discountcard/add" method="post">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="membership_id" value="{{$value->membership_id}}">
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            {{$value->membership_name}}
                                        </div>
                                        @if($discount_card_settings[$key] != null)
                                        <div class="col-md-3">
                                            <input type="number" class="form-control" value="{{$discount_card_settings[$key]->discount_card_count_membership}}" name="discount_card_count_membership">
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-control" name="discount_card_membership">
                                            @foreach($membership as $key2 => $value2)
                                            <option value="{{$value2->membership_id}}" @if($discount_card_settings[$key]->discount_card_membership == $value2->membership_id) selected @endif >{{$value2->membership_name}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-primary col-md-12">Save</button>
                                        </div>
                                        @else
                                         <div class="col-md-3">
                                            <input type="number" class="form-control" value="0" name="discount_card_count_membership">
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-control" name="discount_card_membership">
                                            @foreach($membership as $key2 => $value2)
                                            <option value="{{$value2->membership_id}}" >{{$value2->membership_name}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-primary col-md-12">Save</button>
                                        </div>
                                        @endif
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

</script>
@endsection
