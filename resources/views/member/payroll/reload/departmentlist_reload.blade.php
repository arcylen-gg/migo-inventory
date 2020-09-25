<table class="table tabel-hover table-condensed table-bordered">
	<thead>
		<tr>
			<th>Department Name</th>
			<th class="text-center">Action</th>
		</tr>
	</thead>
	<tbody>
		@foreach($_active as $active)
		<tr>
			<td>
				{{$active->payroll_department_name}}
			</td>
			<td class="text-center">
				<div class="dropdown">
					<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
					<span class="caret"></span></button>
					<ul class="dropdown-menu dropdown-menu-custom">
						<li>
							<a href="#" class="popup" link="/member/payroll/departmentlist/modal_view_department/{{$active->payroll_department_id}}" ><i class="fa fa-search"></i>&nbsp;View</a>
						</li>
						<li>
							<a href="#" class="popup" link="/member/payroll/departmentlist/modal_edit_department/{{$active->payroll_department_id}}" ><i class="fa fa-pencil"></i>&nbsp;Edit</a>
						</li>
						<li>
							<a href="#" data-content="{{$active->payroll_department_id}}" data-archived="{{$active->payroll_department_archived == 0 ? '1':'0'}}" data-action="/departmentlist/archived_department" data-trigger="department" class="btn-archived"><i class="fa fa-{{$active->payroll_department_archived == 0 ? 'trash-o':'recycle'}}"></i>&nbsp;{{$active->payroll_department_archived == 0 ? 'Archived':'Restore'}}</a>
						</li>
					</ul>
				</div>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>