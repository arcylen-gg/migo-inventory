@extends('mlm.layout')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Profile</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool btn-box-tool-a" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
      {!! $cus_info !!}
      </div>
    </div>
  </div>
</div> 
@if(isset($cards))
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">CARD</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool btn-box-tool-a" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
      {!! $card !!}
      </div>
    </div>
  </div>
</div>    
@endif
<div class="row">
<div class="col-md-6">
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">BASIC INFORMATION</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool  btn-box-tool-a" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
      </div>
    </div>
    <!-- /.box-header -->
    <form method="post" action="/mlm/profile/edit/basic" class="global-submit">
    {!! csrf_field() !!}
    <div class="box-body">
      <ul class="products-list product-list-in-box">
        <li class="item">
          <div class="product-info" style="margin-left: 0px !important">
            <a href="javascript:void(0)" class="product-title">Full Name
                <span class="product-description">
                  <input id="first-name" name="first-name" class="form-control" disabled="disabled" value="{{$customer_info->title_name}} {{$customer_info->first_name}} {{$customer_info->middle_name}} {{$customer_info->last_name}} {{$customer_info->suffix_name}}">
                </span>
            </a>  
            <a href="javascript:void(0)" class="product-title">Birth Date
                <span class="product-description">
                  <input type="date" id="datepicker" name="b_day" class="form-control" style="font-family: 'Titillium Web',sans-serif !important;" value="{{$customer_info->b_day}}" placeholder="mm/dd/yyyy">     
                </span>
            </a>  
            <a href="javascript:void(0)" class="product-title">Country
                <span class="product-description">
                  <select name="country_id" id="account-location" class="form-control" style="font-family: 'Titillium Web',sans-serif !important;">
                    @foreach($country as $value)
                    <option value="{{$value->country_id}}" @if($customer_info->country_id == $value->country_id) selected @endif >{{$value->country_name}}</option>
                    @endforeach
                  </select>
                </span>
            </a>
            <a href="javascript:void(0)" class="product-title">Province
                <span class="product-description">
                  <input id="first-name" name="customer_state" class="form-control"  value="{{isset($customer_address->customer_state) ? $customer_address->customer_state : ''}}">
                </span>
            </a> 
            <a href="javascript:void(0)" class="product-title">City
                <span class="product-description">
                  <input id="first-name" name="customer_city" class="form-control" value="{{isset($customer_address->customer_city) ? $customer_address->customer_city : ''}}">
                </span>
            </a>     
            <a href="javascript:void(0)" class="product-title">Zip Code
                <span class="product-description">
                  <input id="first-name" name="customer_zipcode" class="form-control"  value="{{isset($customer_address->customer_zipcode) ? $customer_address->customer_zipcode : ''}}">
                </span>
            </a> 
            <a href="javascript:void(0)" class="product-title">Street
                <span class="product-description">
                  <input id="first-name" name="customer_street" class="form-control"  value="{{isset($customer_address->customer_street) ? $customer_address->customer_street : ''}}">
                </span>
            </a> 
          </div>
        </li>
      </ul>
    </div>
    
    <!-- /.box-body -->
    <div class="box-footer text-center">
      <button class="btn btn-primary col-md-12">Submit</button>
    </div>
    </form>
    <!-- /.box-footer -->
  </div>
</div>
<div class="col-md-6">
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">CONTACT INFORMATION</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool  btn-box-tool-a" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
      </div>
    </div>
    <!-- /.box-header -->
    <form method="post" action="/mlm/profile/edit/contact" class="global-submit">
    {!! csrf_field() !!}
    <div class="box-body">
      <ul class="products-list product-list-in-box">
        <li class="item">
          <div class="product-info" style="margin-left: 0px !important">

            <a href="javascript:void(0)" class="product-title">Tin Number
              <span class="product-description"><input type="text" id="first-name" name="tin_number" class="form-control"  readonly value="{{$customer_info->tin_number}}"></span>
            </a>
            <a href="javascript:void(0)" class="product-title">Email
              <span class="product-description"><input type="email" id="first-name" name="email" class="form-control email"  value="{{$customer_info->email}}" readonly></span>
            </a>
            <a href="javascript:void(0)" class="product-title">Phone
              <span class="product-description"><input id="first-name" name="customer_phone" class="form-control" value="{{isset($other_info->customer_phone) ? $other_info->customer_phone : ''}}"></span>
            </a>
            <a href="javascript:void(0)" class="product-title">Mobile
              <span class="product-description"><input id="first-name" name="customer_mobile" class="form-control" value="{{isset($other_info->customer_mobile) ? $other_info->customer_mobile : ''}}"></span>
            </a>
            <a href="javascript:void(0)" class="product-title">Fax
              <span class="product-description"><input id="first-name" name="customer_fax" class="form-control" value="{{isset($other_info->customer_fax) ? $other_info->customer_fax : ''}}"></span>
            </a>
          </div>
        </li>
      </ul>
    </div>
    <div class="box-footer text-center">
      <button class="btn btn-primary col-md-12">Submit</button>
    </div>
    </form>
  </div>
</div>

<div class="col-md-6">
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">PROFILE PICTURE</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool  btn-box-tool-a" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
      </div>
    </div>
    <!-- /.box-header -->
    <form method="post" action="/mlm/profile/edit/picture" enctype="multipart/form-data">
        {!! csrf_field() !!}
    <div class="box-body">
      <ul class="products-list product-list-in-box">
        <li class="item">
          <div class="product-info" style="margin-left: 0px !important">
            <a href="javascript:void(0)" class="product-title">Choose New Profile Image
            <span class="product-description"><input type="file" class="form-control" name="profile_picture"></span>
            </a>
          </div>
        </li>
      </ul>
    </div>
    <div class="box-footer text-center">
      <button class="btn btn-primary col-md-12">Submit</button>
    </div>
    </form>
  </div>
</div>

<div class="col-md-6">
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">PASSWORD</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool  btn-box-tool-a" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
      </div>
    </div>
    <!-- /.box-header -->
    <form method="post" action="/mlm/profile/edit/password" class="global-submit">
    {!! csrf_field() !!}
    <div class="box-body">
      <ul class="products-list product-list-in-box">
        <li class="item">
          <div class="product-info" style="margin-left: 0px !important">
            <a href="javascript:void(0)" class="product-title">Old Password
            <span class="product-description"><input type="password" id="first-name" name="password_o" class="form-control" value=""></span>
            </a>
            <a href="javascript:void(0)" class="product-title">New Password
            <span class="product-description"><input type="password" id="first-name" name="password_n" class="form-control" value=""></span>
            </a>
            <a href="javascript:void(0)" class="product-title">Confirm New Password 
            <span class="product-description"><input type="password" id="first-name" name="password_n_c" class="form-control" value=""></span>
            </a>
          </div>
        </li>
      </ul>
    </div>
    <div class="box-footer text-center">
      <button class="btn btn-primary col-md-12">Submit</button>
    </div>
    </form>
  </div>
</div>
@if(isset($encashment))
<div class="col-md-6">
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">ENCASHMENT</h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool btn-box-tool-a" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
      </div>
    </div>
    <div class="box-body">
    {!! $encashment !!}
    </div>
    
    <div class="box-footer text-center">
     
    </div>
  </div>
</div> 
@endif


</div>
@endsection
@section('js')
<script type="text/javascript">
  $(function() { 
    $('.btn-box-tool-a').click(); 
  });
  function submit_done (data) {
    // body...
    console.log(data);
    if(data.status == 'success')
    {
      toastr.success(data.message);
    }
    if(data.status == 'warning')
    {
      toastr.warning(data.message);
    }
    else if(data.response_status == "warning_2")
    {
      var erross = data.warning_validator;
      $.each(erross, function(index, value) 
      {
          toastr.error(value);
      }); 
    }
  }
</script>
@endsection