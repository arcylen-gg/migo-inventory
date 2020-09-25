@extends('mlm.layout')
@section('content')
<div class="row">
    <div class="col-md-12">
        {!! $header !!}
        <form method="POST">
        <div class="panel panel-default panel-block">
            <div class="list-group">
                <div class="list-group-item" id="responsive-bordered-table">
                    <div class="form-group">
                        <h4 class="section-title">
                        @if(isset($plan->marketing_plan_label)) {{$plan->marketing_plan_label}} @endif
                        </h4>
                        <small><span style="color: gray">My Slots in @if(isset($plan->marketing_plan_label)) {{$plan->marketing_plan_label}} @endif.</span></small>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <th></th>
                                    <th>Amount</th>
                                    <th>Discounted Amount</th>
                                    <th>Date</th>
                                    <th>Slots Created</th>
                                </thead>
                            <tbody>
                            @if(isset($invoice))
                                @foreach($invoice as $key => $value)
                                <tr>
                                    <td>#{{$key}}</td>
                                    <td>{{$value->item_subtotal}}</td>
                                    <td>{{$value->item_total}}</td>
                                    <td>{{$value->item_code_date_created}}</td>
                                    <td>{{$value->count}}</td>
                                </tr>
                                    @if(count($repurchase[$key]) >= 1)
                                    <tr>
                                        <td colspan="20">
                                        <div class="col-md-12">Slots created:</div>
                                        @foreach($repurchase[$key] as $key => $value2)
                                            <a class="btn btn-primary" target="_blank" href="/mlm/genealogy/repurchase?slot_repurchase={{$value2->repurchase_slot_id}}">{{$value2->repurchase_slot_id}}</a>

                                        @endforeach
                                        <td>
                                    </tr>
                                    @endif
                                @endforeach
                            @else
                            <tr><td colspan="20"><center>No Slots In @if(isset($plan->marketing_plan_label)) {{$plan->marketing_plan_label}} @endif</center></td></tr>
                            @endif
                            </tbody>
                             </table>
                        </div>
                    </div>
                </div>              
            </div>
        </div> 
        
        </form> 
        <div>

    </div>
    </div>
</div>
@endsection