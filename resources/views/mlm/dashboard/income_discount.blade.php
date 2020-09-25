<ul class="list-group">  
    <li class="list-group-item form-horizontal">
        <h4>              
            Account Information
        </h4> 
        <div class="form-group">
            <label for="first-name" class="col-md-2 control-label">Account Username</label>
            <div class="col-md-10">
                <input id="first-name" class="form-control" value="{{$customer_info->mlm_username}}" disabled="disabled">
            </div>
        </div>
        <div class="form-group">
            <label for="first-name" class="col-md-2 control-label">Membership</label>
            <div class="col-md-10">
                <input id="first-name" class="form-control" value="@if($discount_card_log != null) Discount Card Holder @endif" disabled="disabled">
            
            </div>
        </div>
        <div class="form-group hide">
            <label for="first-name" class="col-md-2 control-label">Account Wallet</label>
            <div class="col-md-10">
                <input id="first-name" class="form-control" value="" disabled="disabled">
            </div>
        </div>  
    </li>
</ul>
<div class="col-md-12"><hr /></div>
@foreach($all_discount as $key => $value)
<div class="col-md-12">
  <!-- Widget: user widget style 1 -->
  <div class="box box-widget widget-user">
    <!-- Add the bg color to the header using any of the bg-* classes -->
    <div class="widget-user-header bg-aqua-active">
      <h3 class="widget-user-username">@if(isset($content['company_name']))  {{$content['company_name']}} @endif</h3>
      <h5 class="widget-user-desc">DISCOUNT CARD</h5>
    </div>
    <div class="widget-user-image">
      <!-- <img class="img-circle" src="../dist/img/user1-128x128.jpg" alt="User Avatar"> -->
    </div>
    <div class="box-footer">
      <div class="row">
        <div class="col-sm-4 border-right">
          <div class="description-block">
            <h5 class="description-header">{{name_format_from_customer_info($value)}}</h5>
            <span class="description-text">Holder</span>
          </div>
          <!-- /.description-block -->
        </div>
        <!-- /.col -->
        <div class="col-sm-4 border-right">
          <div class="description-block">
            <h5 class="description-header">{{$value->discount_card_log_code}}</h5>
            <span class="description-text">CODE</span>
          </div>
          <!-- /.description-block -->
        </div>
        <!-- /.col -->
        <div class="col-sm-4">
          <div class="description-block">
            <h5 class="description-header">{{date_format(date_create($value->discount_card_log_date_expired), "Y/m/d") }}</h5>
            <span class="description-text">Expiration</span>
          </div>
          <!-- /.description-block -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
  </div>
  <!-- /.widget-user -->
</div> 
@endforeach   