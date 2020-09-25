 <div class="box box-primary">
  <div class="box-body">
    <ul class="products-list product-list-in-box">
      @if(isset($report))
        @foreach($report as $key => $value)
          <li class="item">
            <div class="product-img">
              {!! mlm_profile($value) !!}
            </div>
            <div class="product-info">
              <a href="javascript:void(0)" class="product-title">Sponsor: {{name_format_from_customer_info($value)}}
                <h4><span class="label label-success pull-right">{{currency('PHP', $value->wallet_log_amount)}}</span></h4></a>
                  <span class="product-description" style="color: black;">
                    Slot: {{$value->slot_no}}
                    <br>
                    Notification: {{$value->wallet_log_details}}
                    <br>
                    Level: {{$value->level}}
                  </span>
            </div>
          </li>
        @endforeach  
    @endif    
    </ul>
  </div>
  <div class="box-footer text-center">
    @if(isset($report))
    <center>{!! $report->render() !!}</center> 
    @endif
  </div>
</div>