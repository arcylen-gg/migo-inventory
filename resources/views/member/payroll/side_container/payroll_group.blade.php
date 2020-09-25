<div class="form-horizontal">
	<div class="form-group">
		<div class="col-md-12">
			<h4>Payroll Group<button class="btn btn-custom-primary pull-right popup" link="/member/payroll/payroll_group/modal_create_payroll_group" size="lg">Create Payroll Group</button></h4>
		</div>
	</div>
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#active-payroll-group"><i class="fa fa-star"></i>&nbsp;Active Payroll Group</a></li>
		<li><a data-toggle="tab" href="#archive-payroll-group"><i class="fa fa-trash-o"></i>&nbsp;Archived</a></li>
	</ul>
	<div class="tab-content padding-10">
		<div id="active-payroll-group" class="tab-pane fade in active">
			<div class="load-data" target="value-id-1">
				<div id="value-id-1">
					<table class="table table-condensed table-bordered">
						<thead>
							<tr>
								<th>Paryoll Code</th>
								<th>Computation</th>
								<th>Period</th>
								<th>13 Month</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
						@foreach($_active as $active)
						<tr>
							<td>
								{{$active->payroll_group_code}}
							</td>
							<td>
								{{$active->payroll_group_salary_computation}}
							</td>
							<td>
								{{$active->payroll_group_period}}
							</td>
							<td>
								{{$active->payroll_group_13month_basis}}
							</td>
							<td class="text-center">
								<div class="dropdown">
									<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
									<span class="caret"></span></button>
									<ul class="dropdown-menu dropdown-menu-custom">
										<li>
											<a href="#" class="popup" link="/member/payroll/payroll_group/modal_edit_payroll_group/{{$active->payroll_group_id}}" size="lg"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
										</li>
										<li>
											<a href="#" class="popup" size="sm" link="/member/payroll/payroll_group/confirm_archived_payroll_group/1/{{$active->payroll_group_id}}"><i class="fa fa-trash-o"></i>&nbsp;Archived</a>
										</li>
									</ul>
								</div>
							</td>
						</tr>
						@endforeach
						</tbody>
					</table>
					<div class="pagination"> {!! $_active->render() !!} </div>
				</div>
			</div>
		</div>
		<div id="archive-payroll-group" class="tab-pane fade">
			<div class="load-data" target="value-id-2">
				<div id="value-id-2">
					<table class="table table-condensed table-bordered">
						<thead>
							<tr>
								<th>Paryoll Code</th>
								<th>Computation</th>
								<th>Period</th>
								<th>13 Month</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
						@foreach($_archived as $archived)
						<tr>
							<td>
								{{$archived->payroll_group_code}}
							</td>
							<td>
								{{$archived->payroll_group_salary_computation}}
							</td>
							<td>
								{{$archived->payroll_group_period}}
							</td>
							<td>
								{{$archived->payroll_group_13month_basis}}
							</td>
							<td class="text-center">
								<div class="dropdown">
									<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
									<span class="caret"></span></button>
									<ul class="dropdown-menu dropdown-menu-custom">
										<li>
											<a href="#" class="popup" link="/member/payroll/payroll_group/modal_edit_payroll_group/{{$archived->payroll_group_id}}" size="lg"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
										</li>
										<li>
											<a href="#" class="popup" size="sm" link="/member/payroll/payroll_group/confirm_archived_payroll_group/0/{{$archived->payroll_group_id}}"><i class="fa fa-refresh"></i>&nbsp;Restore</a>
										</li>
									</ul>
								</div>
							</td>
						</tr>
						@endforeach
						</tbody>
					</table>
					<div class="pagination"> {!! $_archived->render() !!} </div>
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