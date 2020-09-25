@extends('mlm.layout')
@section('content')

{!! $header !!}

<div class="box box-primary">
  <div class="box-body">
    <ul class="products-list product-list-in-box">
      @if(isset($matching_l))
        @foreach($matching_l as $key => $value)
          <li class="item">
            <div class="product-img">
              {!! mlm_profile($value) !!}  {!! mlm_profile($matching_r[$value->matching_log_slot_2]) !!}
            </div>
            <div class="product-info">
              <a href="javascript:void(0)" class="product-title">Sponsor: {{name_format_from_customer_info($value)}} / {{name_format_from_customer_info($matching_r[$value->matching_log_slot_2])}}
                <h4><span class="label label-success pull-right">{{currency('PHP', $value->matching_log_earning)}}</span></h4></a>
                  <span class="product-description" style="color: black;">
                    Slot: {{$value->slot_no}} / {{$matching_r[$value->matching_log_slot_2]->slot_no }}
                    <br>
                    Membership: {{$value->membership_name}}
                  </span>
            </div>
          </li>
        @endforeach  
    @endif    
    </ul>
  </div>
  <div class="box-footer text-center">
    @if(isset($matching_l))
    <center>{!! $matching_l->render() !!}</center> 
    @endif
  </div>
</div>

@endsection