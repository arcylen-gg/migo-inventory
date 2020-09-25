@extends('member.layout')
@section('css')
@endsection
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
	<div class="panel-heading">
		<div>
			<i class="fa fa-gears"></i>
			<h1>
			<span class="page-title">Payroll Configuration</span>
			<small>
			Payroll Settings
			</small>
			</h1>
			<input type="hidden" name="_token" value="{{csrf_token()}}" id="_token">
		</div>
	</div>
</div>
<div class="form-horizontal">
	<div class="form-gorup">
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-body background-white">
					<div class="list-group">
						@foreach($_link as $link)
							<a href="{{$link['link']}}" class="list-group-item  a-navigation-configuration">{{$link['access_name']}}</a>
						@endforeach
					</div>
					
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="panel panel-default">
				<div class="panel-body background-white configuration-div"></div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')

<script type="text/javascript" src="/assets/member/js/payroll/payrollconfiguration.js"></script>
<script type="text/javascript" src="/assets/external/jquery.timeentry.package-2.0.1/jquery.plugin.min.js"></script>
<script type="text/javascript" src="/assets/external/jquery.timeentry.package-2.0.1/jquery.timeentry.min.js"></script>
@endsection