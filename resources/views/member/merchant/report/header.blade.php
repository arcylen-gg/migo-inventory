@if($merchant)
	<h3>{{$report_header}}</h3>
	<h4 style="text-transform: uppercase;">{{$merchant->user_first_name}} {{$merchant->user_last_name}}</h4>

	<div class="col-md-12">
		Start of report : {{$from}}
	</div>
	<div class="col-md-12">
		End of report : {{$to}}
	</div>
	@if($pdf == null)
	<div class="col-md-12">
		<a href="{{$link . '&pdf=pdf'}}" target="_blank" class="btn btn-danger pull-right" >PDF</a>
		<a href="{{$link . '&pdf=excel'}}"  target="_blank" class="btn btn-success pull-right">EXCEL</a>
	</div>
	@endif
	<div class="col-md-12">
		<hr>
	</div>
@endif