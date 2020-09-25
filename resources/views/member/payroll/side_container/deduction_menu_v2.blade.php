<div class="form-horizontal">
	<div class="form-group">
		<div class="col-md-12">
			<h4>Deduction<button class="btn btn-custom-primary pull-right popup" link="/member/payroll/deduction/v2/modal_create_deduction">Create Deduction</button></h4>
		</div>
	</div>



	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#active-deduction_category"><i class="fa fa-star"></i>&nbsp;Active</a></li>
		<li><a data-toggle="tab" href="#archived-deduction_category"><i class="fa fa-trash-o"></i>&nbsp;Archived</a></li>
	</ul>

	<div class="tab-content padding-top-10">
		<div id="active-deduction_category" class="tab-pane fade in active">
			<div class="load-data" target="value-id-1">
				<div id="value-id-1">


					<table class="table table-bordered table-condensed">
						<thead>
							<tr>
								<th>Deduction Category</th>
								<th>Total Amount</th>
								<th>Total Payment</th>
								<th>Balance</th>
								<th class="text-center"></th>
							</tr>
						</thead>
						<tbody>

							@foreach($_all_deductions_by_category as $deduction)
							<tr>
								<td>
									{{$deduction->payroll_deduction_type}}
								</td>
								<td>
									{{$deduction->total_amount}}
								</td>
								<td>
									{{$deduction->total_payment}}
								</td>
								<td>
									{{$deduction->balance}}
								</td>
								<td class="text-center">  
									<label><a href="javascript: action_load_link_to_modal('/member/payroll/deduction/v2/modal_view_deduction_employee_config?deduction_category={{str_replace(' ', '_', $deduction->payroll_deduction_type)}}','lg')"> VIEW EMPLOYEE</a></label>     
								</td>
							</tr>
							@endforeach
							<div class="pagination"> {!! $_all_deductions_by_category->render() !!} </div>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div id="archived-deduction_category" class="tab-pane fade">
			<div class="load-data" target="value-id-1">
				<div id="value-id-1">
					<table class="table table-bordered table-condensed">
						<thead>
							<tr>
								<th>Deduction Category</th>
								<th>Total Amount</th>
								<th>Total Payment</th>
								<th>Balance</th>
								<th class="text-center"></th>
							</tr>
						</thead>
						<tbody>

							@foreach($_all_deductions_by_category_archive as $deduction)
							<tr>
								<td>
									{{$deduction->payroll_deduction_type}}
								</td>
								<td>
									{{$deduction->total_amount}}
								</td>
								<td>
									{{$deduction->total_payment}}
								</td>
								<td>
									{{$deduction->balance}}
								</td>
								<td class="text-center">  
									<label><a href="javascript: action_load_link_to_modal('/member/payroll/deduction/v2/modal_view_deduction_employee_config?deduction_category={{str_replace(' ', '_', $deduction->payroll_deduction_type)}}','lg')"> VIEW EMPLOYEE</a></label>     
								</td>
							</tr>
							@endforeach
							<div class="pagination"> {!! $_all_deductions_by_category->render() !!} </div>
						</tbody>
					</table>
				</div>
			</div>
		</div>


	</div>
</div>

<script type="text/javascript">
	function loading_done_paginate (data)
	{
		console.log(data);
	}
</script>

<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>