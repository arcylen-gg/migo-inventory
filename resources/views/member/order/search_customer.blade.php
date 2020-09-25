<div class="customer-info">
  <!--<div class="form-group" data-toggle="modal" data-target="#CreateCustomerModal">-->
  <div class="form-group popup" link="/member/customer/modalcreatecustomer" size="lg">
    <div class="col-md-2 text-center">
      <img src="/assets/images/noprofile.png">
    </div>
    <div class="col-md-10">
      <label class="margin-top-5">Create customer</label>
    </div>
  </div>
</div>
  @foreach($_customer as $customer)
<div class="customer-info customer-click" data-content="{{$customer->customer_id}}">
  <div class="form-group">
    <div class="col-md-2 text-center">
      <img src="{{$customer->profile}}" class="margin-top-2">
    </div>
    <div class="col-md-10">
      <label><b>{{$customer->first_name.' '.$customer->last_name}}</b></label><br>
      <span class="f-gray margin-top-n9 f12 position-abs">{{$customer->email}}</span>
    </div>
  </div>
</div>
  @endforeach