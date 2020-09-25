@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Marketing Plan - Direct Promotions</span>
                <small>
                    You can set the computation of your Direct Promotion marketing plan here.
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
                <table class="table table-condensed table-bordered">
                    <thead style="text-transform: uppercase">
                        <tr><th colspan="3"><center>DIRECT SPONSOR BONUS PER MEMBERSHIP</center></th></tr>
                        <tr>
                            <th>MEMEBERSHIP NAME</th>
                            <th>NO OF DIRECT</th>
                            <th>PROMOTION BONUS</th>
                            <th>BONUS TYPE</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($membership))
                            @if(!empty($membership))
                                @foreach($membership as $key => $mem)
                                    <tr>
                                        <td>{{$mem->membership_name}}</td>
                                        <td>
                                            <input type="number" class="form-control" name="settings_direct_promotions_count" value="{{$direct_promotions[$key]->settings_direct_promotions_count}}" form="form_{{$mem->membership_id}}" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" name="settings_direct_promotions_bonus" value="{{$direct_promotions[$key]->settings_direct_promotions_bonus}}" form="form_{{$mem->membership_id}}" required>
                                        <td>
                                            <select class="form-control" name="settings_direct_promotions_type" value="{{$direct_promotions[$key]->settings_direct_promotions_type}}" form="form_{{$mem->membership_id}}" required>
                                                <option value='0' {{$direct_promotions[$key]->settings_direct_promotions_type == 0 ? 'selected' : ''}}>Discount Card</option>
                                                <option value='1' {{$direct_promotions[$key]->settings_direct_promotions_type == 1 ? 'selected' : ''}}>GC</option>
                                            </select>
                                        </td>
                                        <td>
                                        <form class="global-submit" id="form_{{$mem->membership_id}}" method="post" action="/member/mlm/plan/direct_promotions/save">
                                            {!! csrf_field() !!}
                                            <input type="hidden" name="membership_id" value="{{$mem->membership_id}}" form="form_{{$mem->membership_id}}">
                                            <button class="btn btn-primary">Save</button>
                                        </form>
                                            
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                            <tr>
                                <td colspan="3"><center>No Active Membership</center></td>
                            </tr>
                            @endif
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
