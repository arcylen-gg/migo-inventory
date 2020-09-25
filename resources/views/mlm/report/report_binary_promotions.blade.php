@extends('mlm.layout')
@section('content')
<div class="row">
    <div class="col-md-12">
        {!! $header !!}
        <div class="panel panel-default panel-block">
            <div class="list-group">
                <div class="list-group-item" id="responsive-bordered-table">
                    <div class="form-group">
                        <h4 class="section-title">
                        @if(isset($plan->marketing_plan_label)) {{$plan->marketing_plan_label}} @endif
                        </h4>
                        <small><span style="color: gray"></span></small>
                        <div class="table-responsive">

                        <table class="table table-condensed table-bordered">
                            <thead>
                            </thead>
                            <tbody>
                                @foreach($promotions as $key => $value)
                                <tr>
                                    <td><img src="{{ $value->item_img ? $value->item_img : "/assets/front/img/default.jpg" }}" style="width: 100%"></td>
                                    <td>
                                        <!-- l -->
                                        <div class="col-md-6">
                                            <div class="col-md-6">
                                                Product Name:
                                            </div>
                                            <div class="col-md-6">{{$value->item_name}}</div>
                                        </div>
                                        <!-- r -->
                                        <div class="col-md-6">
                                            <div class="col-md-6">
                                                Required Binary Points Left:
                                            </div>
                                            <div class="col-md-6">{{$value->binary_promotions_required_left}}</div>
                                        </div>
                                        <!-- l -->
                                        <div class="col-md-6">
                                            <div class="col-md-6">
                                                No. of units to be given
                                            </div>
                                            <div class="col-md-6">{{$value->binary_promotions_no_of_units}}</div>
                                        </div>
                                        <!-- r -->
                                        <div class="col-md-6">
                                            <div class="col-md-6">
                                                Current Left:
                                            </div>
                                            <div class="col-md-6">{{$current_l[$key]}}</div>
                                        </div>
                                        <!-- l -->
                                        <div class="col-md-6">
                                            <div class="col-md-6">
                                                No. of units taken
                                            </div>
                                            <div class="col-md-6">{{$value->binary_promotions_no_of_units_used}}</div>
                                        </div>
                                        <!-- r -->
                                        <div class="col-md-6">
                                            <div class="col-md-6">
                                                Required Binary Points Right:
                                            </div>
                                            <div class="col-md-6">{{$value->binary_promotions_required_right}}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-md-6">
                                                Promo Start Date:
                                            </div>
                                            <div class="col-md-6">{{$value->binary_promotions_start_date}}</div>
                                        </div>
                                        <!-- r -->
                                        <div class="col-md-6">
                                            <div class="col-md-6">
                                                Current Right:
                                            </div>
                                            <div class="col-md-6">{{$current_r[$key]}}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-md-6">
                                                Promo End Date:
                                            </div>
                                            <div class="col-md-6">{{$value->binary_promotions_end_date}}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-md-6">
                                                Required Direct Referral:
                                            </div>
                                            <div class="col-md-6">{{$value->binary_promotions_direct}}</div>
                                        </div>
                                        <div class="col-md-6">
                                            
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-md-6">
                                                Current Direct Referral:
                                            </div>
                                            <div class="col-md-6">{{$direct_count_a[$key]}}</div>
                                        </div>
                                        <div class="col-md-12">
                                            <span class="pull-right">
                                            @if($now >= $value->binary_promotions_start_date)
                                                @if($value->binary_promotions_end_date >=  $now)
                                                    @if($value->binary_promotions_no_of_units > $value->binary_promotions_no_of_units_used)
                                                        @if($current_l[$key] >= $value->binary_promotions_required_left)
                                                            @if($current_r[$key] >= $value->binary_promotions_required_right)
                                                                @if($direct_count_a[$key] >= $value->binary_promotions_direct)
                                                                    @if($claim_count_account[$key] === 0)
                                                                        @if($req_count[$key] == 0)
                                                                            <button class="btn btn-primary" form="form_{{$value->binary_promotions_id}}" onClick="$(this).addClass('hide')">Request</button>
                                                                        @else
                                                                            Already Claimed this promotion.
                                                                        @endif
                                                                    @else
                                                                        Already Claimed this promotion, in this account.
                                                                    @endif
                                                                @else
                                                                    Insufficient, direct referral.
                                                                @endif    
                                                            @else
                                                                Insufficient Points.
                                                            @endif
                                                        @else
                                                            Insufficient Points
                                                        @endif
                                                    @else
                                                        All Stock Taken
                                                    @endif
                                                @else
                                                    Promotion Already Expired
                                                @endif            
                                            @else
                                                Date Not Reached
                                            @endif
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="hide">

                        @foreach($promotions as $key => $value)    
                        @if($now >= $value->binary_promotions_start_date)
                        @if($value->binary_promotions_end_date >=  $now)
                            @if($value->binary_promotions_no_of_units > $value->binary_promotions_no_of_units_used)
                                @if($current_l[$key] >= $value->binary_promotions_required_left)
                                    @if($current_r[$key] >= $value->binary_promotions_required_right)
                                        @if($claim_count_account[$key] == 0)
                                            @if($req_count[$key] == 0)
                                            <form  id="form_{{$value->binary_promotions_id}}" method="post" action="/mlm/report/binary_promotions/request">
                                                {!! csrf_field() !!}
                                                <input type="hidden" name="binary_promotions_id" value="{{$value->binary_promotions_id}}">
                                            </form>
                                            @else
                                            Already Claimed this promotion.
                                            @endif
                                        @else
                                            Already Claimed this promotion in this account
                                        @endif    
                                    @else
                                        Insufficient Points
                                    @endif
                                @else
                                    Insufficient Points
                                @endif
                            @else
                                All Stock Taken
                            @endif
                        @else
                                Promotion Already Expired
                        @endif            
                        @else
                                Date Not Reached
                        @endif
                        @endforeach    
                        </div>
                        </div>
                        </div>
                    </div>
                </div>              
            </div>
        </div> 
        <div>

    </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    @if(isset($s))
        @if($s == 'error')
            toastr.error('{{$m}}');
        @else
            toastr.success('{{$m}}');
        @endif
    @endif
</script>
@endsection