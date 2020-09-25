@extends("member.member_layout")
@section("member_content")
<div class="profile-container">
	<div class="row clearfix row-no-padding">
		<div class="col-md-4 left match-height load-profile">
			<div class="load-profile-holder">
				<div class="profile-main">
					<div class="img"><img class="img-upload" style="border-radius: 100%;" src="{{ $profile_image }}"></div>
					<div class="name">{{ $profile->first_name }} {{ $profile->middle_name }} {{ $profile->last_name }}</div>
					<div class="sub">{{ $profile->email }}</div>
				</div>
				<div class="profile-status">
					<table>
						<tr>
							<td class="blue">
								<div class="status-number">{{ $customer_summary["display_slot_count"] }}</div>
								<div class="status-label">Slot Owned</div>
							</td>
							<td class="orange">
								<div class="status-number">{{ $wallet->display_current_wallet }}</div>
								<div class="status-label">Current Wallet</div>
							</td>
						</tr>
					</table>
				</div>
				@if($mlm == 1)
				<div class="profile-lead">
					<!-- <a data-toggle="modal" data-target="#leads_modal" href="javascript:">
						<img src="/themes/{{ $shop_theme }}/img/leads.png"> Leads Link
					</a> -->
					<!-- Modal -->
					<div id="leads_modal" class="modal fade leads-modal" role="dialog">
					   <div class="modal-dialog">
					      <!-- Modal content-->
					      <div class="modal-content">
					         <div class="modal-header">
					            {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
					            <h4 class="modal-title">LEADS LINK</h4>
					         </div>
					         <div class="modal-body">
					         	<div class="leads-holder">
					         		<input class="form-control" type="text" name="" value="http://brownandproud/myleadslink/link#000123">
					         	</div>
					         	<div class="leads-button">
					         		<button class="btn btn-grey">Copy Link</button>
					         	</div>
					         </div>
					      </div>
					   </div>
					</div>
				</div>
				@endif
				<div class="profile-about">
					<div class="title">About Me</div>
					<table>
						<tr>
							<td>
								<img src="/themes/{{ $shop_theme }}/img/calendar.png"> Date Joined
							</td>
							<td>{{ $profile->created_date }}</td>
						</tr>
						<tr>
							<td>
								<img src="/themes/{{ $shop_theme }}/img/location.png"> Location
							</td>
							@if($profile_address)
								<td>{{ $profile_address->customer_state }} {{ $profile_address->customer_city }} {{ $profile_address->customer_zipcode }} {{ $profile_address->customer_street }}</td>
							@else
								<td><button class="btn btn-orange" type="button">+ Add Location</button></td>
							@endif
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-8 right match-height">
			<div class="profile-form">
				<ul class="nav nav-tabs">
				   <li class="active"><a data-toggle="tab" href="#basic_info">Basic Info</a></li>
				   <li><a data-toggle="tab" href="#profile_picture">Profile Picture</a></li>
				   @if($allowed_change_pass)
				   <li><a data-toggle="tab" href="#password">Password</a></li>
				   @endif
				</ul>
				<div class="tab-content">
				   <div id="basic_info" class="tab-pane fade in active">
				   		<div class="profile_info_success_message hidden">
							<div class="alert alert-custom-success">
							  <strong>Success!</strong> Your info has been successfully updated.
							</div>
				   		</div>
				   		<div class="profile_info_failed_message hidden">
							<div class="alert">
							  <ul>
							  	
							  </ul>
							</div>
				   		</div>
					   <form class="info-form">
					   	<input type="hidden" name="_token" value="{{ csrf_token() }}">
					   		<div class="row clearfix">
					   			<div class="col-md-6">
					   				<!-- <div class="form-group">
							   			<label>First Name</label>
							   			<input type="text" class="form-control" name="first_name" value="{{ $profile->first_name }}">
							   		</div>
							   		<div class="form-group">
							   			<label>Middle Name</label>
							   			<input type="text" class="form-control" name="middle_name" value="{{ $profile->middle_name }}">
							   		</div>
							   		<div class="form-group">
							   			<label>Last Name</label>
							   			<input type="text" class="form-control" name="last_name" value="{{ $profile->last_name }}">
							   		</div> -->
							   		<div class="form-group">
							   			<label>Birth Date</label>
							   			<div style="margin-top: 5px;">
							   				<div class="date-holder">
												<select name="b_month" class="form-control">
													@for($ctr = 1; $ctr <= 12; $ctr++)
													<option {{ date("m", strtotime($profile->birthday)) == $ctr ? 'selected' : '' }} value="{{ $ctr }}">{{ date("F", strtotime($ctr . "/01/17")) }}</option>
													@endfor
												</select>
											</div>
											<div class="date-holder">
												<select name="b_day" class="form-control">
													@for($ctr = 1; $ctr <= 31; $ctr++)
													<option {{ date("d", strtotime($profile->birthday)) == $ctr ? 'selected' : '' }} value="{{ $ctr }}">{{ $ctr }}</option>
													@endfor
												</select>
											</div>
											<div class="date-holder">
												<select name="b_year" class="form-control">
													@for($ctr = date("Y"); $ctr >= (date("Y")-100); $ctr--)
													<option {{ date("Y", strtotime($profile->birthday)) == $ctr ? 'selected' : '' }} value="{{ $ctr }}">{{ $ctr }}</option>
													@endfor
												</select>
											</div>
							   			</div>
							   		</div>
							   		<div class="form-group">
							   			<label>Country</label>
							   			<select class="form-control" name="country_id">
							   				@foreach($_country as $country)
							   				<option {{ $profile->country_id == $country->country_id ? "selected" : "" }} value="{{ $country->country_id }}">{{ $country->country_name }}</option>
							   				@endforeach
							   			</select>
							   		</div>
				   		   			<div class="form-group">
				   			   			<label>Province</label>
				   			   			<select firstload="true" default="{{ isset($profile_address->state_id) ? $profile_address->state_id : '' }}" class="form-control load-location" name="customer_state" level="1"></select>
				   			   		</div>
				   			   		<div class="form-group">
				   			   			<label>Contact</label>
				   			   			<input type="text" class="form-control" name="contact" value="{{isset($profile->contact) ? $profile->contact : '' }}">
				   			   		</div>
					   			</div>
						   		<div class="col-md-6">
							   		<div class="form-group">
							   			<label>City</label>
							   			<select firstload="true" default="{{ isset($profile_address->city_id) ? $profile_address->city_id : '' }}" class="form-control load-location" name="customer_city" level="2"></select>
							   		</div>
							   		<div class="form-group">
							   			<label>Barangay</label>
							   			<select firstload="true" default="{{ isset($profile_address->barangay_id) ? $profile_address->barangay_id : '' }}" class="form-control load-location" name="customer_zipcode" level="3"></select>
							   		</div>
							   		<div class="form-group">
							   			<label>Full Address</label>
							   			<textarea style="height: 107px" class="form-control" name="customer_street">{{ isset($profile_address->customer_street) ? $profile_address->customer_street : '' }}</textarea>
							   		</div>
						   		</div>
						   		<div class="col-md-12">
						   			<div class="form-group btn-holder">
							   			<button class="btn btn-jca-custom-default" type="submit"><i class="fa fa-pencil"></i> Update</button>
							   		</div>
						   		</div>
					   		</div>
					   </form>
				   </div>
				   <!-- CONTACT INFO -->
				   <div id="contact_info" class="tab-pane fade">
				   		<div class="contact_info_success_message hidden">
							<div class="alert alert-custom-success">
							  <strong>Success!</strong> Sponsor Rule has been successfully updated.
							</div>
				   		</div>
				   		<form method="post" class="reward-configuration-form">
				   			{{ csrf_field() }}
					   		<div class="row clearfix">
					   			<div class="col-md-12">
					   				<div class="form-group">
							   			<label>New Sponsor Rule</label>
							   			<select name="downline_rule" class="form-control">
							   				<option value="auto" {{ $customer->downline_rule == "auto" ? "selected" : "" }}>AUTO PLACEMENT</option>
							   				<option value="manual" {{ $customer->downline_rule == "manual" ? "selected" : "" }}>MANUAL PLACEMENT</option>
							   			</select>
							   		</div>
					   			</div>
						   		<div class="col-md-12">
						   			<div class="form-group btn-holder">
							   			<button type="submit" class="submit-button btn btn-jca-custom-default"><i class="fa fa-pencil"></i> Update</button>
							   		</div>
						   		</div>
					   		</div>
				   		</form>
				   </div>
				   <div id="profile_picture" class="tab-pane fade">
				   	<div class="profile_picture_success_message hidden">
						<div class="alert alert-custom-success">
						  <strong>Success!</strong> Your profile picture has been successfully updated.
						</div>
			   		</div>
			   		<div class="profile_picture_failed_message hidden">
						<div class="alert">
						  <strong>Failed!</strong> Please try again later.
						</div>
			   		</div>
				   	<form class="profile-pic-form" enctype="multipart/form-data">
				   	<input class="get-token" type="hidden" name="_token" value="{{ csrf_token() }}">
				   	<input type="hidden" name="customer_id" value="{{ $profile->customer_id }}">
						<div class="upload-profile-pic">
							<div class="icon">
								<img style="width: 161px; height: 121px; object-fit: cover; object-fit: cover;" class="img-upload" src="/themes/{{ $shop_theme }}/img/cloud.png">
							</div>
							<div class="name">Choose New Profile Image</div>
							<button class="btn btn-cloud" type="button" onClick="$('.upload-profile').trigger('click');">Browse</button>
							<input type="file" class="hide upload-profile" name="profile_image">
							<div class="file file-name">No File Selected</div>
						</div>
					</form>
				   </div>
				   @if($allowed_change_pass)
				   <div id="password" class="tab-pane fade">
					   	<div class="profile_password_success_message hidden">
							<div class="alert alert-custom-success">
							  <strong>Success!</strong> Your password has been successfully updated.
							</div>
				   		</div>
				   		<div class="profile_password_failed_message hidden">
							<div class="alert">
							  <ul>
							  	
							  </ul>
							</div>
				   		</div>
					   <form class="password-form">
					   	<input type="hidden" name="_token" value="{{ csrf_token() }}">
					   		<div class="row clearfix">
					   			<div class="col-md-12">
					   				<div class="form-group">
							   			<label>Old Password</label>
							   			<input type="password" class="form-control" name="old_password">
							   		</div>
					   				<div class="form-group">
							   			<label>Password</label>
							   			<input type="password" class="form-control" name="password">
							   		</div>
							   		<div class="form-group">
							   			<label>Confirm Password</label>
							   			<input type="password" class="form-control" name="password_confirmation">
							   		</div>
					   			</div>
						   		<div class="col-md-12">
						   			<div class="form-group btn-holder">
							   			<button class="btn btn-jca-custom-default" type="submit"><i class="fa fa-pencil"></i>  Update</button>
							   		</div>
						   		</div>
					   		</div>
					   </form>
				   </div>
				   @endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section("member_script")
<script type="text/javascript" src="/assets/front/js/global_checkout.js"></script>
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/profile.js"></script>
@endsection
@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/profile.css">
@endsection