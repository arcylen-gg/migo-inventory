@extends('member.layout')
@section('content')
<form class="form-horizontal" role="form" action="/member/mlm/membership/add/save" id="save_membership_form" method="post">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Add New Membership</span>
                <small>
                    The customer can create slot using different kinds of membership.
                </small>
            </h1>
            <button href="javascript:" class="panel-buttons btn btn-primary pull-right save_membership btn-custom-primary">Save Membership</button>
            <a href="/member/mlm/membership" class="panel-buttons btn btn-default pull-right btn-custom-white">&laquo; Back</a>
        </div>
    </div>
</div>

<div class="col-md-6 col-lg-8 col-md-offset-2">
	<div class="panel panel-default panel-block">
		<div class="list-group">
			<div class="list-group-item" id="input-fields-horizontal">
				<h4 class="section-title">Membership Information</h4>
				
					{!! csrf_field() !!}
					<div class="form-group">
						<label for="input-horizontal" class="col-lg-4 control-label">Membership Name</label>
						<div class="col-lg-8">
							<input id="input-horizontal" name="membership_name" class="form-control" value="{{ old('membership_name') }}" placeholder="E.G Gold, Silver, Platinum">
						</div>
					</div>
					<div class="form-group">
						<label for="input-horizontal-counter" class="col-lg-4 control-label">Membership Price</label>
						<div class="col-lg-8">
							<input id="input-horizontal-counter" name="membership_price" class="form-control input-counter" value="{{ old('membership_price') }}" placeholder="0.00"><span class="character-counter">Enter how much the customer need to pay in order to become a member.</span>
						</div>
					</div>
					<div class="form-group">
						<label for="input-horizontal-counter" class="col-lg-4 control-label">Price Level</label>
						<div class="col-lg-8">
							<select class="select-price-level form-control" name="membership_price_level" required>
								@include('member.load_ajax_data.load_price_level')
							</select>
						</div>
					</div>
			</div>
		</div>
	</div>
</div>

				</form>
@endsection
@section('script')
<script type="text/javascript" src='/assets/member/js/membership.js?version=1'></script>
<script type="text/javascript">
@if(count($errors) > 0)
   @foreach ($errors->all() as $error)
      toastr.error("{{ $error }}");
  @endforeach
@endif	
</script>
@endsection
