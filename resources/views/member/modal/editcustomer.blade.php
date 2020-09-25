<input type="hidden" name="_token" value="{{csrf_token()}}">
<input type="hidden" name="customer_id" value="{{$customer->customer_id}}">
<div class="form-horizontal">
  <div class="form-group">
      <div class="col-md-6">
        <span>First name</span>
        <input type="text" class="form-control new_first_name" name="first_name" value="{{$customer->first_name}}" required>
      </div>
      <div class="col-md-6">
        <span>Last name</span>
        <input type="text" class="form-control new_last_name" name="last_name" required value="{{$customer->last_name}}">
      </div>
  </div>
  <div class="form-group">
    <div class="col-md-12">
      <span>Email</span>
      <input type="email" class="form-control new_email" name="email" required value="{{$customer->email}}">
    </div>
  </div>
  <div class="checkbox">
    <label><input type="checkbox"  class="new_accepts_marketing" name="accepts_marketing" value="1" {{$customer->accept_marketing == 1? 'checked:"checked"':''}}>Customer accepts marketing</label>
  </div>
  <div class="checkbox">
    <label><input type="checkbox" class="new_tax_exempt" name="tax_exempt" value="1" {{$customer->taxt_exempt == 1? 'checked:"checked"':''}}>Customer is tax exempt</label>
  </div>
  <hr>
  <h4>SHIPPING ADDRESS</h4>
  <div class="form-group">
    <div class="col-md-6">
      <span>Company</span>
      <input type="text" class="form-control new_company" name="company" value="{{$customer->company}}">
    </div>
    <div class="col-md-6">
      <span>Phone</span>
      <input type="text" class="form-control new_phone" name="phone" required value="{{$customer->phone}}">
    </div>
  </div>
  <div class="form-group">
    <div class="col-md-6">
      <span>Address</span>
      <input type="text" class="form-control new_address" name="address" required value="{{$customer->_address}}">
    </div>
    <div class="col-md-6">
      <span>Address con't</span>
      <input type="text" class="form-control new_address_cont" name="address_cont" value="{{$customer->_address_cont}}">
    </div>
  </div>
  <div class="form-group">
    <div class="col-md-6">
      <span>City</span>
      <input type="text" class="form-control new_city" name="city" required value="{{$customer->city}}">
    </div>
    <div class="col-md-6">
      <span>Country</span>
      <select class="form-control new_country" name="country" required>
        @foreach($_country as $country)
          <option value="{{$country->country_id}}" {{$customer->country_id == $country->country_id ? 'selected="selected"':''}}>{{$country->country_name}}</option>
        @endforeach
        
      </select>
      
    </div>
  </div>
  <div class="form-group">
    <div class="col-md-6">
      <span>Province</span>
      <input type="text" class="form-control new_province" name="province" required value="{{$customer->province}}">
    </div>
    <div class="col-md-6">
      <span>Postal/zip code</span>
      <input type="text" class="form-control new_zip_code" name="zip_code" required value="{{$customer->zip_code}}">
    </div>
  </div>

</div>