<h4>Branch Location <button class="btn btn-custom-primary pull-right popup" link="/member/payroll/branch_name/modal_create_branch">Create Location</button></h4>
<ul class="nav nav-tabs">
	<li class="active"><a data-toggle="tab" href="#active-location"><i class="fa fa-star"></i>&nbsp;Active Location</a></li>
	<li><a data-toggle="tab" href="#archived-location"><i class="fa fa-trash-o"></i>&nbsp;Archived Location</a></li>
</ul>
<div class="tab-content padding-10">
	<div id="active-location" class="tab-pane fade in active">
		<div class="load-data" target="value-id-1">
			<div id="value-id-1">
				<table class="table tabel-hover table-condensed table-bordered">
					<thead>
						<tr>
							<th>Location</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach($_active as $active)
						<tr>
							<td>
								{{$active->branch_location_name}}
							</td>
							<td class="text-center">
								<div class="dropdown">
									<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
									<span class="caret"></span></button>
									<ul class="dropdown-menu dropdown-menu-custom">
										<li>
											<a href="#" class="popup" link="/member/payroll/branch_name/modal_edit_branch/{{$active->branch_location_id}}"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
										</li>
										<li>
											<a href="#" class="popup" link="/member/payroll/branch_name/modal_archive_branch/1/{{$active->branch_location_id}}" size="sm"><i class="fa fa-trash-o"></i>&nbsp;Archived</a>
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
	<div id="archived-location" class="tab-pane fade">
		<div class="load-data" target="value-id-2">
			<div id="value-id-2">
				<table class="table tabel-hover table-condensed table-bordered">
					<thead>
						<tr>
							<th>Location</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach($_archive as $archive)
						<tr>
							<td>
								{{$archive->branch_location_name}}
							</td>
							<td class="text-center">
								<div class="dropdown">
									<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
									<span class="caret"></span></button>
									<ul class="dropdown-menu dropdown-menu-custom">
										<li>
											<a href="#" class="popup" link="/member/payroll/branch_name/modal_edit_branch/{{$archive->branch_location_id}}"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
										</li>
										<li>
											<a href="#" class="popup" link="/member/payroll/branch_name/modal_archive_branch/0/{{$archive->branch_location_id}}" size="sm"><i class="fa fa-refresh"></i>&nbsp;Restore</a>
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