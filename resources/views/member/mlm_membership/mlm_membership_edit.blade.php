@extends('member.layout')
@section('content')
@if($membership)
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Edit Membership</span>
                <small>
                    The customer can create slot using different kinds of membership.
                </small>
            </h1>
            <a href="javascript:" class="panel-buttons btn btn-primary pull-right update_membership_btn btn-custom-primary">Update Membership</a>
            <a href="/member/mlm/membership" class="panel-buttons btn btn-default pull-right btn-custom-white">&laquo; Back</a>
        </div>
    </div>
</div>

<div class="col-md-6 col-lg-8 col-md-offset-2">
	<div class="panel panel-default panel-block">
		<div class="list-group">
			<div class="list-group-item" id="input-fields-horizontal">
				<h4 class="section-title">Membership Information</h4>
				<form class="form-horizontal" role="form" id="update_membership" method="post">
					{!! csrf_field() !!}
					<input type="hidden" name="membership_id" value="{{$membership->membership_id}}">
					<div class="form-group">
						<label for="input-horizontal" class="col-lg-4 control-label">Membership Name</label>
						<div class="col-lg-8">
							<input id="input-horizontal" class="form-control" name="membership_name" value="{{$membership->membership_name}}" placeholder="E.G Gold, Silver, Platinum">
						</div>
					</div>
					<div class="form-group">
						<label for="input-horizontal-counter" class="col-lg-4 control-label">Membership Price</label>
						<div class="col-lg-8">
							<input id="input-horizontal-counter" class="form-control input-counter" name="membership_price" value="{{$membership->membership_price}}" placeholder="0.00"><span class="character-counter">Enter how much the customer need to pay in order to become a member.</span>
						</div>
					</div>
					<div class="form-group">
						<label for="input-horizontal-counter" class="col-lg-4 control-label">Price Level</label>
						<div class="col-lg-8">
							<select class="select-price-level form-control" name="membership_price_level">
								@include('member.load_ajax_data.load_price_level',['price_level_id' => $membership->membership_price_level])
							</select>
						</div>
					</div>
				</form>
			</div>
		</div>
		
		<div class="list-group">
			<div class="list-group-item" id="input-fields-horizontal">
				<h4 class="section-title">Package Information</h4>
	            <div class="table-responsive">
	                <table class="table table-condensed">
	                    <thead style="text-transform: uppercase">
	                        <tr>
	                            <th>Package Name</th>
	                            <th class="text-center">Inclusive Products</th>
	                            <td class="text-right"><a class="popup add-new-packge" link="/member/mlm/membership/edit/{{ $membership->membership_id }}/add_package" href="javascript:" >ADD NEW PACKAGE</a></td>
	                        </tr>
	                    </thead>
	                    <tbody class="packages_body" >
	                    	{!! $packages_view !!}
	                    </tbody>
	                </table>
	            </div>
			</div>
		</div>
	</div>

	@if($use_product_as_membership == 1)
		<div class="panel panel-default panel-block">
			<form class="global-submit" method="post" action="/member/mlm/membership/edit/add/member/product">
			{!! csrf_field() !!}
				<table class="table table-bordered">
					@foreach($ec_product as $key => $value)
						<tr>
							<td>{{$value->eprod_name}}
								<input type="hidden" name="eprod_id[{{$key}}]" value="{{$value->eprod_id}}">
							</td>
							<td>
								<select class="form-control" name="membership_id[{{$key}}]">
									<option value="0">None</option>
								@foreach($membership_product as $mem_key => $mem_value)
									<option value="{{$mem_value->membership_id}}" @if(isset($value->ec_product_membership)){{$value->ec_product_membership == $mem_value->membership_id ? 'selected' : ''}} @endif>{{$mem_value->membership_name}}</option>
								@endforeach
								</select>
							</td>
						</tr>
					@endforeach
					<tr>
						<td>
							
						</td>
						<td>
							<button class="btn btn-primary pull-right">Submit</button>
						</td>	
					</tr>
				</table>
			</form>
		</div>
	@endif
</div>
<input type="hidden" class="membership_id" value="{{$membership->membership_id}}">
@else
<div class="col-md-12"><center>Invalid Membership.</center></div>
@endif

@endsection

@section('script')
<script type="text/javascript" src='/assets/member/js/membership.js'></script>
<script type="text/javascript" src='/assets/member/js/luke.js'></script>
<script type="text/javascript">
var membership_id = $('.membership_id').val();
$(document).ready(function () {
    if(window.location.href.indexOf("addnewpackage") > -1) {
       $('.add-new-packge').click();
    }
});
function submit_done(data)
{
	if(data.response_status == "warning")
	{
		var erross = data.warning_validator;
		$.each(erross, function(index, value) 
		{
		    toastr.error(value);
		}); 
	}
	else if(data.response_status == "success")
	{
		toastr.success("Edit Successful");
		reload_package();
	}
	else if(data.response_status =="successd")
	{
		toastr.success("Edit Successful");
		reload_package();
	}
}
function reload_package()
{
	$('#global_modal').modal("hide");
	$('.packages_body').html('<tr><td colspan="3"><center><div style="margin: 100px auto;" class="loader-16-gray"></div></center></td></tr>');
	$('.packages_body').load('/member/mlm/membership/view/package/' + membership_id);
}

@if(count($errors) > 0)
   @foreach ($errors->all() as $error)
      toastr.error("{{ $error }}");
  @endforeach
@endif

@if (Session::has('success'))
   toastr.success("{{ Session::get('success') }}");
@endif	
@if (Session::has('warning'))
   toastr.warning("{{ Session::get('warning') }}");
@endif	
</script>
@endsection
