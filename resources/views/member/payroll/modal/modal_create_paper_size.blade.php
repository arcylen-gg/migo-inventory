<form class="global-submit form-horizontal" role="form" action="/member/payroll/custom_payslip/modal_save_paper_size" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Paper Sizes</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
			<div class="col-md-4">
				<div class="form-group">
					<div class="col-md-12">
						<small>Paper size name</small>
						<input type="text" name="paper_size_name" class="form-control" placeholder="Paper size name">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<small>Paper width (cm)</small>
						<input type="number" step="any" name="paper_size_width" class="form-control text-right">
					</div>	
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<small>Paper height (cm)</small>
						<input type="number" step="any" name="paper_size_height" class="form-control text-right">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<button class="btn btn-primary btn-custom-primary btn-block" type="submit"">Add Paper</button>
					</div>
				</div>
			</div>
		
			<div class="col-md-8">
				<table class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>Paper Name</th>
							<th>Width (cm)</th>
							<th>Height (cm)</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach($_paper as $paper)
						<tr>
							<td>
								{{$paper->paper_size_name}}
							</td>
							<td class="text-right">
								{{$paper->paper_size_width}}
							</td>
							<td class="text-right">
								{{$paper->paper_size_height}}
							</td>
							<td></td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		
	</div>
</form>