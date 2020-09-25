@extends('tablet.layout')
@section('content')
<div class="form-group">
	<div class="col-md-12">		
		<form class="global-submit form-to-submit-add" action="/tablet/login_submit" method="post">
			<input type="hidden" name="_token" value="{{csrf_token()}}">
			<div class="panel panel-default panel-block panel-title-block" id="top">
				<div class="panel-heading">
					<div>
						<i class="fa fa-tablet"></i>
						<h1>
						<span class="page-title">Tablet Login</span>
						<small>
						Login as Sales Agent
						</small>
						</h1>
					</div>
				</div>
			</div>
			<div class="panel panel-default panel-block panel-title-block">
				<div class="panel-body form-horizontal">
					<div class="form-group">
						<div class="col-md-12">
							<div class="tbl-name-class"></div>
							<div class="progress">
							    <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100">
							    </div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-4"></div>
						<div class="col-md-4">
							<strong>Username</strong>
							<input type="text" name="username" class="user-name form-control">
						</div>
						<div class="col-md-4"></div>
					</div>
					<div class="form-group">
						<div class="col-md-4"></div>
						<div class="col-md-4">
							<strong>Password</strong>
							<input type="password" name="password" class="pass-word form-control">
						</div>
						<div class="col-md-4"></div>
					</div>
					<div class="form-group">
						<div class="col-md-4"></div>
						<div class="col-md-4">
							<button type="submit" class="btn-login btn btn-primary form-control">LOGIN</button>
						</div>
						<div class="col-md-4"></div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
@section('script')
<script type="text/javascript">
	function submit_done(data)
	{
		if(data.status == "success-login")
	{
	toastr.success("Success");
	location.href = "/tablet/dashboard";
	}
	else if(data.status == "error")
	{
	toastr.warning(data.status_message);
	$(data.target).html(data.view);
	}
	}
</script>
@endsection