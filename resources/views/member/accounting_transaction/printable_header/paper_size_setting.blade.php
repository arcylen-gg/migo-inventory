<form class="global-submit" method="post" action="/member/transaction/printable_header/paper-size-submit">
<div class="row clearfix">
	{!! csrf_field() !!}
	<div class="form-group" style="padding: 30px">
		<div class="col-md-3">&nbsp;</div>
		<div class="col-md-6" style="border:solid 1px #000;padding:10px">
			<div class="text-center"><strong> Printable Paper Size </strong></div><br>
			<label>Customer Transaction</label>

			@if(count($_customer_transaction) > 0)
				<div class="row clearfix">
					<div class="col-md-2">
						&nbsp;
					</div>
					<div class="col-md-6 text-center">
						Paper Size
					</div>
					<div class="col-md-4 text-center">
						Width
					</div>
				</div>
				@foreach($_customer_transaction as $keycust => $cust)
				<div class="row clearfix">
					<div class="col-md-2">
						<input type="hidden" name="transaction[]" value="{{$keycust}}">
						<label class="radio">
							<input type="radio" class="radio-all radio-{{$keycust}}" transaction-abrev="{{$cust}}"> 
							{{str_replace('_','/',strtoupper($keycust))}}
						</label>
					</div>
					<div class="col-md-6">
						<div class="col-md-4">
							<label class="checkbox">
								<input type="hidden" name="size_h_w[]" class="input-select {{$keycust}}" value="{{isset(explode('/',$_papersize['printable_'.$keycust][0])[1]) ? '1' : '0'}}">
								<input type="checkbox" class="check-select" {{isset(explode('/',$_papersize['printable_'.$keycust][0])[1]) ? 'checked' : ''}} row-name="{{$keycust}}" value="1">W x H
							</label>
						</div>
						<div class="col-md-8">
							@if(isset(explode('/',$_papersize['printable_'.$keycust][0])[1]))
							<div class="input-size {{$keycust}}">
								<table>
									<tr>
										<td>
											<input type="text" class="form-control input-sm" name="size_w[]" value="{{explode('/',$_papersize['printable_'.$keycust][0])[0]}}" placeholder="W (in)">
										</td>
										<td>
											<input type="text" class="form-control input-sm" name="size_h[]" value="{{explode('/',$_papersize['printable_'.$keycust][0])[1]}}" placeholder="H (in)">
										</td>
									</tr>
								</table>
							</div>
							<div class="select-size {{$keycust}} hidden">
								<select class="form-control input-sm papersize-select" name="paper_size[]">
									@if(count($_paper_size) > 0)
										@foreach($_paper_size as $ps)
											<option {{$_papersize['printable_'.$keycust][0] == $ps->paper_size_name ? 'selected' : ''}} size-h="{{$ps->paper_size_height}}" size-w="{{$ps->paper_size_width}}" value="{{$ps->paper_size_name}}">{{$ps->paper_size_name}}</option>
										@endforeach
									@endif
								</select>
							</div>
							@else
							<div class="input-size {{$keycust}} hidden">
								<table>
									<tr>
										<td>
											<input type="text" class="form-control input-sm" name="size_w[]" placeholder="W (in)">
										</td>
										<td>
											<input type="text" class="form-control input-sm" name="size_h[]" placeholder="H (in)">
										</td>
									</tr>
								</table>
							</div>
							<div class="select-size {{$keycust}}">
								<select class="form-control input-sm papersize-select" name="paper_size[]">
									@if(count($_paper_size) > 0)
										@foreach($_paper_size as $ps)
											<option {{$_papersize['printable_'.$keycust][0] == $ps->paper_size_name ? 'selected' : ''}} size-h="{{$ps->paper_size_height}}" size-w="{{$ps->paper_size_width}}" value="{{$ps->paper_size_name}}">{{$ps->paper_size_name}}</option>
										@endforeach
									@endif
								</select>
							</div>
							@endif
						</div>
					</div>
					<div class="col-md-4">
						<select class="form-control input-sm" name="width[]">
							<option {{$_papersize['printable_'.$keycust][1] == '100' ? 'selected' : ''}} value="100">100%</option>
							<option {{$_papersize['printable_'.$keycust][1] == '50' ? 'selected' : ''}} value="50">50%</option>
						</select>
					</div>
				</div>
				@endforeach
			@else
			<div class="text-center">NO TRANSACTION FOUND</div>
			@endif
			<br>
			<label>Vendor Transaction</label>

			@if(count($_vendor_transaction) > 0)
				<div class="row clearfix">
					<div class="col-md-2">
						&nbsp;
					</div>
					<div class="col-md-6 text-center">
						Paper Size
					</div>
					<div class="col-md-4 text-center">
						Width
					</div>
				</div>
				@foreach($_vendor_transaction as $keyven => $ven)
				<div class="row clearfix">
					<div class="col-md-2">
						<input type="hidden" name="transaction[]" value="{{$keyven}}">
						<label class="radio">
							<input type="radio" class="radio-all radio-{{$keyven}}" transaction-abrev="{{$ven}}"> 
							{{str_replace('_','/',strtoupper($keyven))}}
						</label>
					</div>
					<div class="col-md-6">
						<div class="col-md-4">
							<label class="checkbox">
								<input type="hidden" name="size_h_w[]" class="input-select {{$keyven}}" value="{{isset(explode('/',$_papersize['printable_'.$keyven][0])[1]) ? '1' : '0'}}">
								<input type="checkbox" class="check-select" {{isset(explode('/',$_papersize['printable_'.$keyven][0])[1]) ? 'checked' : ''}} row-name="{{$keyven}}" value="1">W x H
							</label>
						</div>
						<div class="col-md-8">
							@if(isset(explode('/',$_papersize['printable_'.$keyven][0])[1]))
							<div class="input-size {{$keyven}}">
								<table>
									<tr>
										<td>
											<input type="text" class="form-control input-sm" name="size_w[]" value="{{explode('/',$_papersize['printable_'.$keyven][0])[0]}}" placeholder="W (in)">
										</td>
										<td>
											<input type="text" class="form-control input-sm" name="size_h[]" value="{{explode('/',$_papersize['printable_'.$keyven][0])[1]}}" placeholder="H (in)">
										</td>
									</tr>
								</table>
							</div>
							<div class="select-size {{$keyven}} hidden">
								<select class="form-control input-sm papersize-select" name="paper_size[]">
									@if(count($_paper_size) > 0)
										@foreach($_paper_size as $ps)
											<option {{$_papersize['printable_'.$keyven][0] == $ps->paper_size_name ? 'selected' : ''}} size-h="{{$ps->paper_size_height}}" size-w="{{$ps->paper_size_width}}" value="{{$ps->paper_size_name}}">{{$ps->paper_size_name}}</option>
										@endforeach
									@endif
								</select>
							</div>
							@else
							<div class="input-size {{$keyven}} hidden">
								<table>
									<tr>
										<td>
											<input type="text" class="form-control input-sm" name="size_w[]" placeholder="W (in)">
										</td>
										<td>
											<input type="text" class="form-control input-sm" name="size_h[]" placeholder="H (in)">
										</td>
									</tr>
								</table>
							</div>
							<div class="select-size {{$keyven}}">
								<select class="form-control input-sm papersize-select" name="paper_size[]">
									@if(count($_paper_size) > 0)
										@foreach($_paper_size as $ps)
											<option {{$_papersize['printable_'.$keyven][0] == $ps->paper_size_name ? 'selected' : ''}} size-h="{{$ps->paper_size_height}}" size-w="{{$ps->paper_size_width}}" value="{{$ps->paper_size_name}}">{{$ps->paper_size_name}}</option>
										@endforeach
									@endif
								</select>
							</div>
							@endif
						</div>
					</div>
					<div class="col-md-4">
						<select class="form-control input-sm" name="width[]">
							<option {{$_papersize['printable_'.$keyven][1] == '100' ? 'selected' : ''}} value="100">100%</option>
							<option {{$_papersize['printable_'.$keyven][1] == '50' ? 'selected' : ''}} value="50">50%</option>
						</select>
					</div>
				</div>
				@endforeach
			@else
			<div class="text-center">NO TRANSACTION FOUND</div>
			@endif
			<br>
			<label>Other Transaction</label>

			@if(count($_warehouse_transaction) > 0)
				<div class="row clearfix">
					<div class="col-md-2">
						&nbsp;
					</div>
					<div class="col-md-6 text-center">
						Paper Size
					</div>
					<div class="col-md-4 text-center">
						Width
					</div>
				</div>
				@foreach($_warehouse_transaction as $keywrh => $wrh)
				<div class="row clearfix">
					<div class="col-md-2">
						<input type="hidden" name="transaction[]" value="{{$keywrh}}">
						<label class="radio">
							<input type="radio" class="radio-all radio-{{$keywrh}}" transaction-abrev="{{$wrh}}"> 
							{{str_replace('_','/',strtoupper($keywrh))}}
						</label>
					</div>
					<div class="col-md-6">
						<div class="col-md-4">
							<label class="checkbox">
								<input type="hidden" name="size_h_w[]" class="input-select {{$keywrh}}" value="{{isset(explode('/',$_papersize['printable_'.$keywrh][0])[1]) ? '1' : '0'}}">
								<input type="checkbox" class="check-select" {{isset(explode('/',$_papersize['printable_'.$keywrh][0])[1]) ? 'checked' : ''}} row-name="{{$keywrh}}">W x H
							</label>
						</div>
						<div class="col-md-8">
							@if(isset(explode('/',$_papersize['printable_'.$keywrh][0])[1]))
							<div class="input-size {{$keywrh}}">
								<table>
									<tr>
										<td>
											<input type="text" class="form-control input-sm" name="size_w[]" value="{{explode('/',$_papersize['printable_'.$keywrh][0])[0]}}" placeholder="W (in)">
										</td>
										<td>
											<input type="text" class="form-control input-sm" name="size_h[]" value="{{explode('/',$_papersize['printable_'.$keywrh][0])[1]}}" placeholder="H (in)">
										</td>
									</tr>
								</table>
							</div>
							<div class="select-size {{$keywrh}} hidden">
								<select class="form-control input-sm papersize-select" name="paper_size[]">
									@if(count($_paper_size) > 0)
										@foreach($_paper_size as $ps)
											<option {{$_papersize['printable_'.$keywrh][0] == $ps->paper_size_name ? 'selected' : ''}} size-h="{{$ps->paper_size_height}}" size-w="{{$ps->paper_size_width}}" value="{{$ps->paper_size_name}}">{{$ps->paper_size_name}}</option>
										@endforeach
									@endif
								</select>
							</div>
							@else
							<div class="input-size {{$keywrh}} hidden">
								<table>
									<tr>
										<td>
											<input type="text" class="form-control input-sm" name="size_w[]" placeholder="W (in)">
										</td>
										<td>
											<input type="text" class="form-control input-sm" name="size_h[]" placeholder="H (in)">
										</td>
									</tr>
								</table>
							</div>
							<div class="select-size {{$keywrh}}">
								<select class="form-control input-sm papersize-select" name="paper_size[]">
									@if(count($_paper_size) > 0)
										@foreach($_paper_size as $ps)
											<option {{$_papersize['printable_'.$keywrh][0] == $ps->paper_size_name ? 'selected' : ''}} size-h="{{$ps->paper_size_height}}" size-w="{{$ps->paper_size_width}}" value="{{$ps->paper_size_name}}">{{$ps->paper_size_name}}</option>
										@endforeach
									@endif
								</select>
							</div>
							@endif
						</div>
					</div>
					<div class="col-md-4">
						<select class="form-control input-sm" name="width[]">
							<option {{$_papersize['printable_'.$keywrh][1] == '100' ? 'selected' : ''}} value="100">100%</option>
							<option {{$_papersize['printable_'.$keywrh][1] == '50' ? 'selected' : ''}} value="50">50%</option>
						</select>
					</div>
				</div>
				@endforeach
			@else
			<div class="text-center">NO TRANSACTION FOUND</div>
			@endif
			
			<br>
			<div class="row clearfix">
				<div class="col-md-12">
					<button type="submit" class="pull-right btn btn-primary btn-custom-primary"> SAVE </button>
				</div>
			</div>
		</div>
		<div class="col-md-8 hidden">
			<div class="actual-paper" style="border: solid 1px #000;padding: 10px;height: 877px">
				<div class="row clearfix">
					<div class="col-md-8">
						<div  style="border: solid 0.5px #8c8e91;padding: 10px" class="text-center">
							<h3>Header Logo & Company Details</h3>
						</div>
					</div>
					<div class="col-md-4 transaction-name">
						<div  style="border: solid 0.5px #8c8e91;padding: 10px" class="text-center">
							<h3 class="display-transaction-name">Transaction Name</h3>
						</div>
					</div>
				</div><br>
				<div class="row clearfix">
					<div class="col-md-12">
						<div  style="border: solid 0.5px #8c8e91;padding: 10px" class="text-center">
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<h3>Transaction Details</h3>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
						</div>
					</div>
				</div>
				<br>
				<div class="row clearfix">
					<div class="col-md-12">
						<div  style="border: solid 0.5px #8c8e91;padding: 10px" class="text-center">
							<h3>Signatories</h3>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</form>
