<div class="form-horizontal">
	<div class="form-group">
		<div class="col-md-12">
		<div class=" dropdown pull-right">
				<button class="btn btn-custom-primary dropdown-toggle " type="button" data-toggle="dropdown"><i class="fa fa-gears"></i>&nbsp;Operation
				<span class="caret"></span></button>
				<ul class="dropdown-menu dropdown-menu-custom">
					<li><a href="#" class="popup" link="/member/payroll/shift_template/modal_create_shift_template" size="lg"><i class="fa fa-plus"></i>&nbsp;Create Template</a></li>
					<li><a href="#" class="popup" size="lg" link="/member/payroll/shift_template/modal_shift_import_template" size="md"><i class="fa fa-upload"></i>&nbsp;Import Time Sheet</a></li>
				</ul>
		</div>
		</div>
	</div>
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#active-payroll-group"><i class="fa fa-star"></i>&nbsp;Active Template</a></li>
		<li><a data-toggle="tab" href="#archive-payroll-group"><i class="fa fa-trash-o"></i>&nbsp;Archived</a></li>
	</ul>
	<div class="tab-content padding-10">
		<div id="active-payroll-group" class="tab-pane fade in active">
			<div class="load-data" target="value-id-1">
				<div id="value-id-1">
					<table class="table table-condensed table-bordered">
						<thead>
							<tr>
								<th>Shift Code</th>
								<th width="15%" class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($_active as $active)
							<tr>
								<td>
									{{$active->shift_code_name}}
								</td>
								<td class="text-center">
									<div class="dropdown">
										<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
										<span class="caret"></span></button>
										<ul class="dropdown-menu dropdown-menu-custom">
											<li>
												<a href="#" class="popup" link="/member/payroll/shift_template/modal_view_shift_template/{{$active->shift_code_id}}" size="lg"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
											</li>
											<li>
												<a href="#" class="popup" link="/member/payroll/shift_template/modal_archive_shift_template/1/{{$active->shift_code_id}}" size="sm"><i class="fa fa-trash-o"></i>&nbsp;Archived</a>
											</li>
										</ul>
									</div>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					<div class="pagination"> </div>
				</div>
			</div>
		</div>
		<div id="archive-payroll-group" class="tab-pane fade">
			<div class="load-data" target="value-id-2">
				<div id="value-id-2">
					<table class="table table-condensed table-bordered">
						<thead>
							<tr>
								<th>Shift Code</th>
								<th width="15%" class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($_archived as $archived)
							<tr>
								<td>
									{{$archived->shift_code_name}}
								</td>
								<td class="text-center">
									<div class="dropdown">
										<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
										<span class="caret"></span></button>
										<ul class="dropdown-menu dropdown-menu-custom">
											<li>
												<a href="#" class="popup" link="/member/payroll/shift_template/modal_view_shift_template/{{$archived->shift_code_id}}" size="lg"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
											</li>
											<li>
												<a href="#" class="popup" link="/member/payroll/shift_template/modal_archive_shift_template/0/{{$archived->shift_code_id}}" size="sm"><i class="fa fa-recycle"></i>&nbsp;Restore</a>
											</li>
										</ul>
									</div>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					<div class="pagination">  </div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function loading_done_paginate (data)
	{
		
	}
</script>

<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>