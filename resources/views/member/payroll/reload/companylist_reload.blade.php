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
				{{$active->payroll_company_name}}
			</td>
			<td class="text-center">
				{{$active->payroll_company_code}}
			</td>
			<td class="text-center">
				{{$active->rdo_code}}
			</td>
			<td class="text-center">
				<div class="dropdown">
					<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
					<span class="caret"></span></button>
					<ul class="dropdown-menu dropdown-menu-custom">
						<li>
							<a href="#" class="popup" link="/member/payroll/company_list/view_company_modal/{{$active->payroll_company_id}}" ><i class="fa fa-search"></i>&nbsp;View</a>
						</li>
						<li>
							<a href="#" class="popup" link="/member/payroll/company_list/edit_company_modal/{{$active->payroll_company_id}}" ><i class="fa fa-pencil"></i>&nbsp;Edit</a>
						</li>
						<li>
							<a href="#" data-content="{{$active->payroll_company_id}}" data-archived="{{$active->payroll_company_archived == 1 ? '0':'1'}}" class="btn-archived"><i class="fa fa-{{$active->payroll_company_archived == 1 ? 'recycle':'trash-o'}}"></i>&nbsp;{{$active->payroll_company_archived == 1 ? 'Re-activate':'Archived'}}</a>
						</li>
					</ul>
				</div>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>