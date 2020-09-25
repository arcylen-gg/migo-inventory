<button type="button" class="close pull-right modal-loan-summary-close" data-dismiss="modal" style="margin-right: 15px;">×</button>
<ul class="nav nav-tabs" style="margin-top: 20px">
	  <li class="active"><a data-toggle="tab" href="#active-deduction"><i class="fa fa-star"></i>&nbsp;Active</a></li>
	  <li><a data-toggle="tab" href="#archived-deduction"><i class="fa fa-trash-o"></i>&nbsp;Archived</a></li>
</ul>

<div class="tab-content modal-summary padding-top-10" style="margin-bottom: 20px">
	  <div id="active-deduction" class="tab-pane fade in active">
	  	<div class="load-data" target="value-id-1">
			<div id="value-id-1">

				<table class="table table-bordered table-condensed">
					<thead>
					    <tr>
					        <th class="text-center">DEDUCTION NAME</th>
					        <th class="text-center">DATA FILLED</th>
					        <th class="text-center">AMOUNT</th>
					        <th class="text-center">TERMS OF PAYMENT</th>
					        <th class="text-center">TOTAL AMOUNT PAID</th>
					        <th class="text-center">REMAINING BALANCE</th>
					        <th></th>
					    </tr>
					</thead>

					<tbody class="table-warehouse">
					
					    @foreach($_loan_data as $key => $loan_data)
					    <tr>
					        <td class="text-center">{{ $loan_data->payroll_deduction_name }}</td>
					        <td class="text-center">{{ $loan_data->payroll_deduction_date_filed }}</td>
					        <td class="text-center">{{ $loan_data->payroll_deduction_amount }}</td>
					       	<td class="text-center">{{ $loan_data->number_of_payment .' out of '. $loan_data->payroll_deduction_number_of_payments}}</td>
					        <td class="text-center">{{ $loan_data->total_payment }}</td>
					        <td class="text-center">{{ $loan_data->payroll_deduction_amount - $loan_data->total_payment }}</td>
					        <td class="text-center">
								<div class="dropdown">
									<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
									<span class="caret"></span></button>
									<ul class="dropdown-menu dropdown-menu-custom">
										<li>
											<a href="#" class="popup" link="/member/payroll/deduction/v2/modal_edit_deduction/{{$loan_data->payroll_deduction_id}}"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
										</li>
										<li>
											<a href="#" class="popup" link="/member/payroll/reports/modal_loan_summary_report/{{ $loan_data->payroll_employee_id }}/{{ $loan_data->payroll_deduction_id }}" size="lg"><i class="fa fa-pencil"></i>&nbsp;SUMMARY</a>
										</li>
										<li>
											<a href="#" class="popup" link="/member/payroll/deduction/v2/archive_deduction/1/{{$loan_data->payroll_deduction_id}}?payroll_deduction_type={{ str_replace(' ','_',$loan_data->payroll_deduction_type) }}" size="sm"><i class="fa fa-trash-o"></i>&nbsp;Archived</a>
										</li>
									</ul>
								</div>
							</td>
					    </tr>
					    @endforeach

					</tbody>
				</table>
			</div>
		</div>
	</div>


	<div id="archived-deduction" class="tab-pane fade">
	  	<div class="load-data" target="value-id-1">
			<div id="value-id-1">

				<table class="table table-bordered table-condensed">
					<thead>
					    <tr>
					        <th class="text-center">DEDUCTION NAME</th>
					        <th class="text-center">DATE FILLED</th>
					        <th class="text-center">AMOUNT</th>
					        <th class="text-center">TERMS OF PAYMENT</th>
					        <th class="text-center">TOTAL AMOUNT PAID</th>
					        <th class="text-center">REMAINING BALANCE</th>
					        <th></th>
					    </tr>
					</thead>
					<tbody class="table-warehouse">
					    @foreach($_loan_data_archive as $key => $loan_data_archive)
					    <tr>
					        <td class="text-center">{{ $loan_data_archive->payroll_deduction_name }}</td>
					        <td class="text-center">{{ $loan_data_archive->payroll_deduction_date_filed }}</td>
					        <td class="text-center">{{ $loan_data_archive->payroll_deduction_amount }}</td>
					       	<td class="text-center">{{ $loan_data_archive->number_of_payment .' out of '. $loan_data_archive->payroll_deduction_number_of_payments}}</td>
					        <td class="text-center">{{ $loan_data_archive->total_payment }}</td>
					        <td class="text-center">{{ $loan_data_archive->payroll_deduction_amount - $loan_data_archive->total_payment }}</td>
					        <td class="text-center">
								<div class="dropdown">
									<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
									<span class="caret"></span></button>
									<ul class="dropdown-menu dropdown-menu-custom">
										<li>
											<a href="#" class="popup" link="/member/payroll/deduction/v2/modal_edit_deduction/{{ $loan_data_archive->payroll_deduction_id }}"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
										</li>
										<li>
											<a href="#" class="popup" link="/member/payroll/reports/modal_loan_summary_report/{{ $loan_data_archive->payroll_employee_id }}/{{ $loan_data_archive->payroll_deduction_id }}" size="lg"><i class="fa fa-pencil"></i>&nbsp;SUMMARY</a>
										</li>
										<li>
											<a href="#" class="popup" link="/member/payroll/deduction/v2/archive_deduction/0/{{$loan_data_archive->payroll_deduction_id}}?payroll_deduction_type={{str_replace(' ','_',$loan_data_archive->payroll_deduction_type)}}" size="sm"><i class="fa fa-refresh"></i>&nbsp;Restore</a>
										</li>
									</ul>
								</div>
							</td>
					    </tr>
					    @endforeach
					</tbody>
				</table>
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
