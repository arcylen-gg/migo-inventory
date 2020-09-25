<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">Reservee List</h4>
</div>
<div class="modal-body clearfix">
	<div class="form-group">
		<table class="table-responsive table table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Contact Details</th>
					<th>Enrollers Code</th>
					<th></th>
				</tr>				
			</thead>
			<tbody>
				@if(count($_reservee) > 0)
					@foreach($_reservee as $key => $reservee)
					<tr>
						<td>{{$key+1}}</td>
						<td>{{ucwords($reservee->reservee_fname. ' '.$reservee->reservee_mname.' '.$reservee->reservee_lname)}}</td>
						<td>{{$reservee->reservee_contact}}</td>
						<td>
							<div>{{strtoupper($reservee->reservee_enrollers_code)}}</div>
						</td>
						<td>
							<div>
								@if($reservee->customer_id != null)
								Member
								@else
								Guest
								@endif
							</div>
						</td>
					</tr>
					@endforeach
				@else
					<tr><td colspan="4" class="text-center">NO RESERVEE YET</td></tr>
				@endif
			</tbody>
		</table>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
</div>
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>