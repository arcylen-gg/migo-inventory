<table class="table table-condensed">
	<tr>
		<td>This account has <h4><span style="color: green">{{currency('PHP',$sum)}}</span></h4></td>
	</tr>
	<tr>
		<td>{!! $info !!}</td>
	</tr>
	<tr>
		<td>
			@if(count($get_wallet) >= 1)
			<table class="table table-condensed">
				<tr>
					<th colspan="4"><center>Transaction List</center></th>
				</tr>
				<tr>
					<th>Date</th>
					<th>Amount</th>
					<th>Student ID</th>
					<th>Student Name</th>
				</tr>
			@foreach($get_wallet as $key => $value)
				<tr>
					<td>{{$value->merchant_school_date}}</td>
					<td>{{$value->merchant_school_amount}}</td>
					@if($value->merchant_school_s_id == null)
					<td colspan="2"><center>(Top-Up)</center></td>
					@else
					<td>{{$value->merchant_school_s_id}}</td>
					<td>{{$value->merchant_school_s_name}}</td>
					@endif
				</tr>
			@endforeach
			</table>
			@else
			<center>---No Available Transaction---</center>
			@endif	
		</td>
	</tr>
	<tr>
		<td>
			<hr>
		</td>
	</tr>
	@if($sum >= 1)
	<tr>
		<td>
			<form class="global-submit" method="post" action="/member/mlm/merchant_school/consume">
			{!! csrf_field() !!}
			<input type="hidden" value="{{$customer_id}}" name="customer_id">
				<table class="table table-condensed">
					<th>Student Id</th>
					<th>Student Name</th>
					<tr>
						<td><input type="text" class="form-control" name="merchant_school_s_id"></td>
						<td><input type="text" class="form-control" name="merchant_school_s_name"></td>
					</tr>
				</table>
				<table class="table table-condensed">
					<tr>
						<td>
							<label>Wallet Amount</label>
							<input type="number" class="form-control" name="merchant_school_amount">
						</td>
						<td>
							<label>Additional Cash</label>
							<input type="number" class="form-control" name="merchant_school_cash">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<label>Remarks</label>
							<textarea class="form-control" name="merchant_school_remarks"></textarea>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<label>Announcement</label>
							<textarea class="form-control" name="merchant_school_anouncement"></textarea>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<button class="btn btn-primary col-md-12">Consume</button>
						</td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
	@endif
</table>