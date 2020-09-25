<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">DETAILS</h4>
</div>
<div class="modal-body clearfix">
	@if(!$details)
		Details not found
	@else
		<table class="table table-bordered table-striped table-hover">
			<tbody>
				@foreach($details as $key => $detail)
					<tr>
						<td>{{ ucwords(str_replace("_", " ", $key)) }}</td>
						<td>{{ $detail }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		<div class="col-md-12">
			<center>
			<img width="auto" height="auto" style="max-height: 300px;" src="{{$image_url}}">
			</center>
		</div>
	@endif
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
	<button class="btn btn-primary btn-custom-primary" type="button">Submit</button>
</div>