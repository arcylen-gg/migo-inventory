<table class="table tabel-hover table-condensed table-bordered">
	<thead>
		<tr>
			<th>Job Title Name</th>
			<th>Department</th>
			<th class="text-center">Action</th>
		</tr>
	</thead>
	<tbody>
		@foreach($_active as $active)
		<tr>
			<td>
				{{$active->payroll_jobtitle_name}}
			</td>
			<td>
				{{$active->payroll_department_name}}
			</td>
			<td class="text-center">
				<div class="dropdown">
					<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
					<span class="caret"></span></button>
					<ul class="dropdown-menu dropdown-menu-custom">
						<li>
							<a href="#" class="popup" link="/member/payroll/jobtitlelist/modal_view_jobtitle/{{$active->payroll_jobtitle_id}}"><i class="fa fa-search"></i>&nbsp;View</a>
						</li>
						<li>
							<a href="#" class="popup" link="/member/payroll/jobtitlelist/modal_edit_jobtitle/{{$active->payroll_jobtitle_id}}"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
						</li>
						<li>
							<a href="#" data-content="{{$active->payroll_jobtitle_id}}" data-archived="{{$active->payroll_jobtitle_archived == 0 ? '1':'0'}}" class="btn-archived" data-action="/jobtitlelist/archived_jobtitle" data-trigger="job title"><i class="fa fa-{{$active->payroll_jobtitle_archived == 0 ? 'trash-o':'recycle'}}"></i>&nbsp;{{$active->payroll_jobtitle_archived == 0 ? 'Archived':'Restore'}}</a>
						</li>
					</ul>
				</div>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>