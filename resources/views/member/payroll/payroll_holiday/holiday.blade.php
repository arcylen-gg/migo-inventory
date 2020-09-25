<div class="form-horizontal">
	<div class="form-group">
		<div class="col-md-12">
			<h4>{{$title}}<button class="btn btn-custom-primary pull-right popup" link="/member/payroll/holiday/modal_create_holiday/v2">Create Holiday</button></h4>
		</div>
	</div>
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#active-holiday"><i class="fa fa-star"></i>&nbsp;Active Holiday</a></li>
		<li><a data-toggle="tab" href="#archived-holiday"><i class="fa fa-trash-o"></i>&nbsp;Archived Holiday</a></li>
	</ul>
	<div class="tab-content padding-10">
		<div id="active-holiday" class="tab-pane fade in active">
			<div class="load-data" target="value-id-1">
				<div id="value-id-1">	
					<table class="table table-bordered table-condensed">
						<thead>
							<tr>
								<th>Holiday Name</th>
								<th>Date</th>
								<th>Category</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($_active as $active)
							<tr>
								<td>
									{{$active->payroll_holiday_name}}
								</td>
								<td>
									{{date('M d, Y', strtotime($active->payroll_holiday_date))}}
								</td>
								<td>
									{{$active->payroll_holiday_category}}
								</td>
								<td class="text-center">
									<div class="dropdown">
										<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
										<span class="caret"></span></button>
										<ul class="dropdown-menu dropdown-menu-custom">
											<li>
												<a href="#" class="popup" link="{!!$edit!!}{{$active->payroll_holiday_id}}" ><i class="fa fa-pencil"></i>&nbsp;Edit</a>
											</li>
											<li>
												<a href="#" class="popup" link="{!!$archived!!}1/{{$active->payroll_holiday_id}}" size="sm"><i class="fa fa-trash-o"></i>&nbsp;Archived</a>
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
		<div id="archived-holiday" class="tab-pane fade">
			<div class="load-data" target="value-id-2">
				<div id="value-id-2">
					<table class="table table-bordered table-condensed">
						<thead>
							<tr>
								<th>Holiday Name</th>
								<th>Date</th>
								<th>Category</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($_archived as $archived)
							<tr>
								<td>
									{{$archived->payroll_holiday_name}}
								</td>
								<td>
									{{date('M d, Y', strtotime($archived->payroll_holiday_date))}}
								</td>
								<td>
									{{$archived->payroll_holiday_category}}
								</td>
								<td class="text-center">
									<div class="dropdown">
										<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
										<span class="caret"></span></button>
										<ul class="dropdown-menu dropdown-menu-custom">
											<li>
												<a href="#" class="popup" link="{!!$edit!!}{{$archived->payroll_holiday_id}}" ><i class="fa fa-pencil"></i>&nbsp;Edit</a>
											</li>
											<li>
												<a href="#" class="popup" link="{!!$archived!!}0/{{$archived->payroll_holiday_id}}" size="sm"><i class="fa fa-refresh"></i>&nbsp;Restore</a>
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
<script type="text/javascript">
	function loading_done_paginate (data)
	{
		console.log(data);
	}
</script>
