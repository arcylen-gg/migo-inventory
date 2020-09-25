@extends('mlm.layout')
@section('content')
<div class="row">
    <div class="col-md-12">
        {!! $header !!}
        <form method="POST">
        <div class="panel panel-default panel-block">
            <div class="list-group">
                @if(isset($membership))
                <div class="list-group-item" id="responsive-bordered-table">
                    <div class="form-group">
                        <h4 class="section-title">
                        @if(isset($plan->marketing_plan_label)) {{$plan->marketing_plan_label}} @endif
                        </h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <th>Membership</th>
                                    <th>Required Direct Referral</th>
                                    <th>Current</th>
                                    <th>Matched</th>
                                    <th>Bonus Type</th>
                                    <th>Count / Bonus</th>
                                </thead>
                                <tbody>
                                    @foreach($membership as $key => $value)
                                    <tr>
                                        <td>{{$value->membership_name}}</td>
                                        <td>{{$direct_promotion[$key]->settings_direct_promotions_count}}</td>
                                        <td>{{$count_direct[$key] - ($count_matched[$key] * $direct_promotion[$key]->settings_direct_promotions_count)}}</td>
                                        <td>{{$count_matched[$key] * $direct_promotion[$key]->settings_direct_promotions_count}} </td>
                                        <td>{{$direct_promotion[$key]->settings_direct_promotions_type == 0 ? 'Discount Card' : 'GC' }}</td>
                                        <td>{{$direct_promotion[$key]->settings_direct_promotions_bonus}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @else
                    <center>No Active Report.</center>  
                @endif                 
            </div>
        </div> 
        
        </form> 
        <div>

    </div>
    </div>
</div>
@endsection