@extends('mlm.layout')
@section('content')
<section class="content">

  <div class="row">
    <div class="col-md-3">

    @if($customer_info)
      <!-- Profile Image -->
      <div class="box box-primary">
        <div class="box-body box-profile">
          <img class="profile-user-img img-responsive img-circle" src="{{mlm_profile_link($customer_info)}}" alt="User profile picture">

          <h3 class="profile-username text-center">{{name_format_from_customer_info($customer_info)}}</h3>

        @if($slot_info)
          <p class="text-muted text-center">{{$slot_info->membership_name}}</p>

          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Direct Referral</b> <a class="pull-right">{{$direct_count}}</a>
            </li>
            <li class="list-group-item">
              <b>Active Slot</b> <a class="pull-right">{{$slot_info->slot_no}}</a>
            </li>
            <li class="list-group-item">
              <b>Current Wallet</b> <a class="pull-right">{{currency('PHP', $current_wallet)}}</a>
            </li>
          </ul>
        @endif
        <?php 
          $domain = Request::url();
          $d_e_1 = explode('//', $domain);
          if(count($d_e_1) >= 2)
          {
            $domain_without_http = $d_e_1[1];
          }
          else
          {
            $domain_without_http = $d_e_1[0];
          }
          $d_e_2 = explode('.', $domain_without_http);
          $username = $customer_info->mlm_username;
          $domain = 'digimahouse';
          $com = 'com';
          if(count($d_e_2) == 3)
          {
            $domain = $d_e_2[1];
            $com = $d_e_2[2];
          }
          elseif(count($d_e_2) == 2)
          {
            $domain = $d_e_2[0];
            $com = $d_e_2[1];
          }
          else
          {
            $domain =$domain;
            $com = $com;
          }

          $com_a = explode('/', $com);
          $com = $com_a[0];
          $url_a = 'http://' . $username . '.' . $domain . '.' .$com . '/mlm/register';
        ?>
          <a href="{{$url_a}}" target="_blank" class="btn btn-primary btn-block"><b>Leads Link</b></a>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
      <!-- About Me Box -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">About Me</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <strong><i class="fa fa-book margin-r-5"></i>Date Joined</strong>

          <p class="text-muted">
            {{$customer_info->created_date}}
          </p>

          <hr>

          <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>

          <p class="text-muted">{{address_customer_info($customer_info)}}</p>

        </div>
        <!-- /.box-body -->
      </div>
      @endif
      @if($slot_info)
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">New Referral</h3>

          <div class="box-tools pull-right">
            <span class="label label-success">{{count($new_member)}} New Members</span>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
          <ul class="users-list clearfix">
            @foreach($new_member as $key => $value)
                <li class="clearfix"
                style="width: 50% !important">
                    {{name_format_from_customer_info($value)}}
                  <span class="users-list-date">{{$value->slot_created_date}}</span>
                </li>
            @endforeach
          </ul>
          <!-- /.users-list -->
        </div>
        <!-- /.box-body -->
      </div>
      @endif
      <!-- /.box -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#basic" data-toggle="tab">Basic Info</a></li>
          <li><a href="#contact" data-toggle="tab">Contact Info</a></li>
          <li><a href="#profilepic" data-toggle="tab">Profile Picture</a></li>
          <li><a href="#password" data-toggle="tab">Password</a></li>
          @if(isset($encashment))
            <li><a href="#encashment" data-toggle="tab">Enchashment</a></li>
          @endif
          @if(isset($cards))
            <li><a href="#card" data-toggle="tab">Card</a></li>
          @endif
        </ul>
  
        <div class="tab-content">
          <!-- /.tab-pane -->
          <div class="active tab-pane" id="basic">
            <form method="post" action="/mlm/profile/edit/basic" class="global-submit">
            {!! csrf_field() !!}
                <table class="table table-condensed table-bordered">
                    <tr>
                        <td>
                            <label>Full Name</label>
                            <input id="first-name" name="first-name" class="form-control" disabled="disabled" value="{{$customer_info->title_name}} {{$customer_info->first_name}} {{$customer_info->middle_name}} {{$customer_info->last_name}} {{$customer_info->suffix_name}}">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Birth Date</label>
                            <input type="date" id="datepicker" name="b_day" class="form-control" style="font-family: 'Titillium Web',sans-serif !important;" value="{{$customer_info->b_day}}" placeholder="mm/dd/yyyy">     
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Country</label>
                            <select name="country_id" id="account-location" class="form-control" style="font-family: 'Titillium Web',sans-serif !important;">
                                @foreach($country as $value)
                                <option value="{{$value->country_id}}" @if($customer_info->country_id == $value->country_id) selected @endif >{{$value->country_name}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Province</label>
                            <input id="first-name" name="customer_state" class="form-control"  value="{{isset($customer_address->customer_state) ? $customer_address->customer_state : ''}}">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>City</label>
                            <input id="first-name" name="customer_city" class="form-control" value="{{isset($customer_address->customer_city) ? $customer_address->customer_city : ''}}">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Street</label>
                            <input id="first-name" name="customer_zipcode" class="form-control"  value="{{isset($customer_address->customer_zipcode) ? $customer_address->customer_zipcode : ''}}">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Zip Code</label>
                            <input id="first-name" name="customer_street" class="form-control"  value="{{isset($customer_address->customer_street) ? $customer_address->customer_street : ''}}">
                        </td>
                    </tr>
                    <tr>
                        <td><button class="btn btn-primary col-md-12">Submit</button></td>
                    </tr>
                </table>
            </form>
          </div>
          <div class="tab-pane" id="contact">
              <form method="post" action="/mlm/profile/edit/contact" class="global-submit">
                {!! csrf_field() !!}
                <table class="table table-condensed table-bordered">
                    <tr>
                        <td>
                            <label>Tin Number</label>
                            <input type="text" id="first-name" name="tin_number" class="form-control"  readonly value="{{$customer_info->tin_number}}">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Email</label>
                            <input type="email" id="first-name" name="email" class="form-control email"  value="{{$customer_info->email}}" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Phone</label>
                            <input id="first-name" name="customer_phone" class="form-control" value="{{isset($other_info->customer_phone) ? $other_info->customer_phone : ''}}">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Mobile</label>
                            <input id="first-name" name="customer_mobile" class="form-control" value="{{isset($other_info->customer_mobile) ? $other_info->customer_mobile : ''}}">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Fax</label>
                            <input id="first-name" name="customer_fax" class="form-control" value="{{isset($other_info->customer_fax) ? $other_info->customer_fax : ''}}">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button class="btn btn-primary col-md-12">Submit</button>
                        </td>
                    </tr>
                </table>
              </form>
          </div>
          <div class="tab-pane" id="profilepic">
              <form method="post" action="/mlm/profile/edit/picture" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <table class="table table-condensed table-bordered">
                    <tr>
                        <td>
                            <label>Choose New Profile Image</label>
                            <input type="file" class="form-control" name="profile_picture">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button class="btn btn-primary col-md-12">Submit</button>
                        </td>
                    </tr>
                </table>
              </form>  
          </div>
          <div class="tab-pane" id="password">
            <form method="post" action="/mlm/profile/edit/password" class="global-submit">
                {!! csrf_field() !!}
                <table class="table table-condensed table-bordered">
                    <tr>
                        <td>
                            <label>Old Password</label>
                            <input type="password" id="first-name" name="password_o" class="form-control" value="">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>New Password</label>
                            <input type="password" id="first-name" name="password_n" class="form-control" value="">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Confirm Password</label>
                            <input type="password" id="first-name" name="password_n_c" class="form-control" value="">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button class="btn btn-primary col-md-12">Submit</button>
                        </td>
                    </tr>
                </table>
            </form>    
          </div>
          @if(isset($encashment))
          <div class="tab-pane" id="encashment">
            <div class="clearfix">
                {!! $encashment !!}
            </div>
          </div>
          @endif
          @if(isset($cards))
          <div class="tab-pane" id="card">
            {!! $cards !!}
          </div>
          @endif
          <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

</section>
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