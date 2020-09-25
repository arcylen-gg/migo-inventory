@extends('member.layout')
@section('content')
@if($membership)
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Add New Package</span>
                <small>
                    The customer can add new package using different kinds of membership.
                </small>
            </h1>
            <a href="javascript:" class="panel-buttons btn btn-primary pull-right save_membership btn-custom-primary">Save Package</a>
            <a href="/member/mlm/membership" class="panel-buttons btn btn-default pull-right btn-custom-white">&laquo; Back</a>
        </div>
    </div>
</div>

	<div class="col-md-6 well well-white border-none">
		<table class="table">
			<thead>
				<tr><th><h4 class="section-title">Package Information</h4></th></tr>
				<tr>
					<th>
						<label for="input-horizontal" class="col-lg-4 control-label">Package Name:</label>
						{{$membersip_package->membership_package_name}}
					</th>
				</tr>
				<tr>
					<th>
						<tr>
							<th>Shipping Info</th>
						</tr>
						<tr>
							<th>
								<label for="input-horizontal" class="col-lg-4 control-label">Weight</label>
								<input type="number" class="form-control">
							</th>
						</tr>
						<tr>
							<th>
								<label for="input-horizontal" class="col-lg-4 control-label">Width</label>
								<input type="number" class="form-control">
							</th>
						</tr>
						<tr>
							<th>
								<label for="input-horizontal" class="col-lg-4 control-label">Height</label>
								<input type="number" class="form-control">
							</th>
						</tr>
						<tr>
							<th>
								<label for="input-horizontal" class="col-lg-4 control-label">Length</label>
								<input type="number" class="form-control">
							</th>
						</tr>
					</th>
				</tr>
			</thead>
		</table>	
	</div>
	<div class="col-md-6 well well-white border-none">
	<table class="table">
		<thead>
			<tr><th><h4 class="section-title">Membership Information</h4></th></tr>
			<tr>
				<th>
					<label for="input-horizontal" class="col-lg-4 control-label">Membership Name:</label>
					{{$membership->membership_name}}
				</th>
			</tr>
			<tr>
				<th>
					<label for="input-horizontal" class="col-lg-4 control-label">Membership Price:</label>
					{{$membership->membership_price}}
				</th>
			</tr>
		</thead>
	</table>
	
	</div>
	
	<div class="col-md-12 well well-white border-none">
		<table class="table">
			<thead>
				<tr><th><h4 >Product List</h4></th></tr>
				<tr><th><div class="product_list_ajax col-md-12">@if($product_list) {!! $product_list !!} @else No Product Available. @endif </div>	</th></tr>
			</thead>
		</table>
		<table class="table">
			<thead>
				<tr><th><h4 >Product Set</h4></th></tr>
				<tr><th><div class="product_set"></div>	</th></tr>
			</thead>
		</table>
	</div>
@else
	<div class="well well-white border-none">
		<h4 class="section-title"><center>Invalid Membership</center></h4>
	</div>
@endif
@endsection




@section('script')
<script type="text/javascript" src='/assets/member/js/membership.js'></script>
<script type="text/javascript" src='/assets/member/js/luke.js'></script>
<script type="text/javascript" src='/assets/member/js/mlm/addpackage.js'></script>
<script type="text/javascript">
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