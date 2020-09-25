@extends('member.layout')
@section('css')
@endsection
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
	<div class="panel-heading">
		<div>
			<i class="fa fa-building-o"></i>
			<h1>
			<span class="page-title">Company List</span>
			<small>
			List of Company
			</small>
			</h1>
			<button class="btn btn-custom-white panel-buttons pull-right popup" link="/member/payroll/company_list/modal_create_company?is_sub=true">Create Sub-Company/Branch</button>
			<button class="btn btn-custom-primary panel-buttons pull-right popup" link="/member/payroll/company_list/modal_create_company">Create Company</button>
			<input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
		</div>
	</div>
</div>
<div class="company-list">
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#active-company"><i class="fa fa-star" aria-hidden="true"></i>&nbsp;Active Company</a></li>
		<li><a data-toggle="tab" href="#archived-company"><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;Archived Company</a></li>
	</ul>
	<div class="tab-content tab-pane-div">
		<div id="active-company" class="tab-pane fade in active">
			<div class="load-data" target="value-id-1">
				<div id="value-id-1">
					<table class="table table-condensed table-bordered table-hover">
						<thead>
							<tr>
								<th class="text-center">Company Name</th>
								<th class="text-center">Company Code</th>
								<th class="text-center">Company RDO</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($_active as $active)
							<tr>
								<td>
									{{$active['company']->payroll_company_name}}
								</td>
								<td class="text-center">
									{{$active['company']->payroll_company_code}}
								</td>
								<td class="text-center">
									{{$active['company']->rdo_code}}
								</td>
								<td class="text-center">
									<div class="dropdown">
										<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
										<span class="caret"></span></button>
										<ul class="dropdown-menu dropdown-menu-custom">
											<li>
												<a href="#" class="popup" link="/member/payroll/company_list/view_company_modal/{{$active['company']->payroll_company_id}}" ><i class="fa fa-search"></i>&nbsp;View</a>
											</li>
											<li>
												<a href="#" class="popup" link="/member/payroll/company_list/edit_company_modal/{{$active['company']->payroll_company_id}}" ><i class="fa fa-pencil"></i>&nbsp;Edit</a>
											</li>
											<li>
												<a href="#" class="popup" link="/member/payroll/company_list/modal_archived_company/1/{{$active['company']->payroll_company_id}}" size="sm"><i class="fa fa-trash-o"></i>&nbsp;Archived</a>
											</li>
										</ul>
									</div>
								</td>
							</tr>
								@foreach($active['branch'] as $branch)
								<tr>
									<td class="indent-18">
										<i class="fa fa-caret-right"></i>&nbsp;{{$branch->payroll_company_name}}
									</td>
									<td class="text-center">
										{{$branch->payroll_company_code}}
									</td>
									<td class="text-center">
										{{$branch->rdo_code}}
									</td>
									<td class="text-center">
										<div class="dropdown">
											<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
											<span class="caret"></span></button>
											<ul class="dropdown-menu dropdown-menu-custom">
												<li>
													<a href="#" class="popup" link="/member/payroll/company_list/view_company_modal/{{$branch->payroll_company_id}}" ><i class="fa fa-search"></i>&nbsp;View</a>
												</li>
												<li>
													<a href="#" class="popup" link="/member/payroll/company_list/edit_company_modal/{{$branch->payroll_company_id}}" ><i class="fa fa-pencil"></i>&nbsp;Edit</a>
												</li>
												<li>
													<a href="#" class="popup" link="/member/payroll/company_list/modal_archived_company/1/{{$branch->payroll_company_id}}" size="sm"><i class="fa fa-trash-o"></i>&nbsp;Archived</a>
												</li>
											</ul>
										</div>
									</td>
								</tr>
								@endforeach
							@endforeach
						</tbody>
					</table>
					<div class="pagination"> {!! $_parent->render() !!} </div>
				</div>
			</div>
		</div>
		<div id="archived-company" class="tab-pane fade">
			<div class="load-data" target="value-id-2">
				<div id="value-id-2">
					<table class="table table-condensed table-bordered table-hover">
						<thead>
							<tr>
								<th class="text-center">Company Name</th>
								<th class="text-center">Company Code</th>
								<th class="text-center">Company RDO</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($_archived as $archived)
							<tr>
								<td>
									{{$archived->payroll_company_name}}
								</td>
								<td class="text-center">
									{{$archived->payroll_company_code}}
								</td>
								<td class="text-center">
									{{$archived->rdo_code}}
								</td>
								<td class="text-center">
									<div class="dropdown">
										<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
										<span class="caret"></span></button>
										<ul class="dropdown-menu dropdown-menu-custom">
											<li>
												<a href="#" class="popup" link="/member/payroll/company_list/view_company_modal/{{$archived->payroll_company_id}}" ><i class="fa fa-search"></i>&nbsp;View</a>
											</li>
											<li>
												<a href="#" class="popup" link="/member/payroll/company_list/edit_company_modal/{{$archived->payroll_company_id}}" ><i class="fa fa-pencil"></i>&nbsp;Edit</a>
											</li>
											<li>
												<a href="#" class="popup" link="/member/payroll/company_list/modal_archived_company/0/{{$archived->payroll_company_id}}" size="sm"><i class="fa fa-recycle"></i>&nbsp;Re-use</a>
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
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/payroll/companylist.js"></script>

<script type="text/javascript">
	function loading_done_paginate (data)
	{
		console.log(data);
	}
</script>
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>

@endsection
