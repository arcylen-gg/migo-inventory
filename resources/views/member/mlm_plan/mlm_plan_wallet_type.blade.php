
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">WALLET TYPE</h4>
</div>
<div class="modal-body">
	<div class="table-responsive">
		<table class="table table-responsive table-condensed">
			<thead>
				
				<th>Wallet Type</th>
				<th>Encashment</th>
				<th>Repurchase</th>
				<th>GC ONLY</th>
				<th>Active</th>
				<th></th>
			</thead>
			<tbody>
			
			@foreach($wallet_type as $key => $value)
			<form class="global-submit" role="form" action="/member/mlm/plan/wallet/type/add" method="post" id="form_add_new_type{{$key}}"></form>	
			<input type="hidden" name="_token" value="{{ csrf_token() }}" form="form_add_new_type{{$key}}">
				<tr>
					<td>
						<input type="text" class="form-control" name="wallet_type_key" value="{{$value->wallet_type_key}}" placeholder="Enter name of your wallet type" form="form_add_new_type{{$key}}">
						<input type="hidden" name="wallet_type_id" value="{{$value->wallet_type_id}}" form="form_add_new_type{{$key}}">
					</td>
					<td>
						<select class="form-control" name="wallet_type_enable_encash" form="form_add_new_type{{$key}}">
							<option value="1" {{$value->wallet_type_enable_encash == 1 ? 'selected' : ''}}>Enable</option>
							<option value="0" {{$value->wallet_type_enable_encash == 0 ? 'selected' : ''}}>Disable</option>
						</select>
					</td>
					<td>
						<select class="form-control" name="wallet_type_enable_product_repurchase" form="form_add_new_type{{$key}}">
							<option value="1" {{$value->wallet_type_enable_product_repurchase == 1 ? 'selected' : ''}}>Enable</option>
							<option value="0" {{$value->wallet_type_enable_product_repurchase == 0 ? 'selected' : ''}}>Disable</option>
						</select>
					</td>
					<td>
						<select class="form-control" name="wallet_type_other" form="form_add_new_type{{$key}}">
							<option value="1" {{$value->wallet_type_other == 1 ? 'selected' : ''}}>Enable</option>
							<option value="0" {{$value->wallet_type_other == 0 ? 'selected' : ''}}>Disable</option>
						</select>
					</td>
					<td>
						<select class="form-control" name="wallet_type_archive" form="form_add_new_type{{$key}}">
							<option value="0" {{$value->wallet_type_archive == 0 ? 'selected' : ''}}>Active</option>
							<option value="1" {{$value->wallet_type_archive == 1 ? 'selected' : ''}}>Inactive</option>
						</select>
					</td>
					<td>
						<input type="hidden" name="submit_type" value="edit" form="form_add_new_type{{$key}}">
						<button class="btn btn-def-white btn-custom-white"  name="submit_type" value="edit" form="form_add_new_type{{$key}}">✓</button>
						<!-- <button class="btn btn-def-white btn-custom-white"  name="submit_type" value="archive" form="form_add_new_type{{$key}}" style="color:red;">×</button> -->
					</td>
				</tr>
		@endforeach		
				<tr>
				<form class="global-submit" role="form" action="/member/mlm/plan/wallet/type/add" method="post" id="form_add_new_type"></form>	
				<input type="hidden" name="_token" value="{{ csrf_token() }}" form="form_add_new_type">
					<td>
						<input type="text" class="form-control" name="wallet_type_key" value="" placeholder="Enter name of your wallet type" form="form_add_new_type">
					</td>
					<td>
						<select class="form-control" name="wallet_type_enable_encash" form="form_add_new_type">
							<option value="1">Enable</option>
							<option value="0">Disable</option>
						</select>
					</td>
					<td>
						<select class="form-control" name="wallet_type_enable_product_repurchase" form="form_add_new_type">
							<option value="1">Enable</option>
							<option value="0">Disable</option>
						</select>
					</td>
					<td>
						<select class="form-control" name="wallet_type_other" form="form_add_new_type">
							<option value="1">Enable</option>
							<option value="0">Disable</option>
						</select>
					</td>
					<td>
						
					</td>
					<td>
						<input type="hidden" name="submit_type" value="add" form="form_add_new_type">
						<button class="btn btn-def-white btn-custom-white" form="form_add_new_type" name="submit_type" value="add">✓</button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
</div>