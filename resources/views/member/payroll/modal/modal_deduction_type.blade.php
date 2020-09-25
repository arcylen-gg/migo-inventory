<form class="custom-form" role="form" action="/member/payroll/deduction/modal_save_deduction_type" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">{{$type}}</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" class="payroll_deduction_category" name="payroll_deduction_category" value="{{$type}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
			<div class="col-md-12">
				<small>Type Name</small>
				<div class="input-group">
					<input type="text" name="payroll_deduction_type_name" class="form-control payroll_deduction_type_name" required>
					<span class="input-group-btn">
						<button class=" btn btn-custom-primary btn-submit-type" type="submit">Save</button>
					</span>
				</div>

			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<ul class="nav nav-tabs">
				  <li class="active"><a data-toggle="tab" href="#active-type"><i class="fa fa-star"></i>&nbsp;Active</a></li>
				  <li><a data-toggle="tab" href="#archive-type"><i class="fa fa-trash-o"></i>&nbsp;Archived</a></li>
				</ul>

				<div class="tab-content tab-pane-div">
				  <div id="active-type" class="tab-pane fade in active">
				    <table class="table table-bordered table-condensed">
				    	<thead>
				    		<tr>
				    			<th>Type Name</th>
				    			<th width="5%"></th>
				    		</tr>
				    	</thead>
				    	<tbody class="padding-tb-1">
				    		@foreach($_active as $active)
				    		<tr>
				    			<td>
				    				<input type="text" name="" class="border-none width-100 padding-5 txt-deduction-type" value="{{$active->payroll_deduction_type_name}}" data-content="{{$active->payroll_deduction_type_id}}">
				    			</td>
				    			<td class="text-center">
				    				<a href="#" class="btn-archived" data-archived="1" data-content="{{$active->payroll_deduction_type_id}}"><i class="fa fa-times"></i></a>
				    			</td>
				    		</tr>
				    		@endforeach
				    	</tbody>
				    </table>
				  </div>
				  <div id="archive-type" class="tab-pane fade">
				    <table class="table table-bordered table-condensed">
				    	<thead>
				    		<tr>
				    			<th>Type Name</th>
				    			<th width="5%"></th>
				    		</tr>
				    	</thead>
				    	<tbody class="padding-tb-1">
				    		@foreach($_archived as $archived)
				    		<tr>
				    			<td>
				    				<input type="text" name="" class="border-none width-100 padding-5 txt-deduction-type" value="{{$archived->payroll_deduction_type_name}}" data-content="{{$archived->payroll_deduction_type_id}}">
				    			</td>
				    			<td class="text-center">
				    				<a href="#" class="btn-archived" data-archived="0" data-content="{{$archived->payroll_deduction_type_id}}"><i class="fa fa-refresh"></i></a>
				    			</td>
				    		</tr>
				    		@endforeach
				    	</tbody>
				    </table>
				  </div>
				
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
	</div>
</form>

<script type="text/javascript" src="/assets/member/js/payroll/modal_deduction_type.js"></script>