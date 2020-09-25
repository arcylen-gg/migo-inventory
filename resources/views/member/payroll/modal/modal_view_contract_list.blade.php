<form class="global-submit" role="form" action="/member/payroll/jobtitlelist/modal_save_jobtitle" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Company Detail List</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">

	</div>
	<div class="modal-body contract-modal-body">
		<ul class="nav nav-tabs">
		  <li class="active"><a data-toggle="tab" href="#active-list"><i class="fa fa-star"></i>&nbsp;Active</a></li>
		  <li><a data-toggle="tab" href="#archived-list"><i class="fa fa-trash-o"></i>&nbsp;Archived</a></li>
		</ul>

		<div class="tab-content padding-10 tab-pane-div">
		  <div id="active-list" class="tab-pane fade in active">
		    <table class="table table-condensed table-bordered table-hover">
		    	<thead>
		    		<tr>
		    			<th>Deparment</th>
		    			<th>Position</th>
		    			<th>Effective Date</th>
		    			<th>End Date</th>
		    			<th>Status</th>
		    			<th class="text-center">Action</th>
		    		</tr>
		    	</thead>
		    	@foreach($_active as $active)
		    	<tr>
		    		<td>
		    			{{$active->payroll_department_name}}
		    		</td>
		    		<td>
		    			{{$active->payroll_jobtitle_name}}
		    		</td>
		    		<td>
		    			{{$active->payroll_employee_contract_date_hired != '0000-00-00' ? date('M d, Y',strtotime($active->payroll_employee_contract_date_hired)):''}}
		    		</td>
		    		<td>
		    			{{$active->payroll_employee_contract_date_end != '0000-00-00' ? date('M d, Y',strtotime($active->payroll_employee_contract_date_end)) : ''}}
		    		</td>
		    		<td>
		    			{{$active->employment_status}}
		    		</td>
		    		<td class="text-center">
		    			<div class="dropdown">
							<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
							<span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-custom">
								<li>
									<a href="#" class="popup" link="/member/payroll/employee_list/modal_edit_contract/{{$employee_id}}/{{$active->payroll_employee_contract_id}}"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
								</li>
								<li>
									<a href="#" class="popup" link="/member/payroll/employee_list/modal_archive_contract/1/{{$active->payroll_employee_contract_id}}" size="sm"><i class="fa fa-trash-o"></i>&nbsp;Archived</a>
								</li>
							</ul>
						</div>
		    		</td>
		    	</tr>
		    	@endforeach
		    </table>
		  </div>
		  <div id="archived-list" class="tab-pane fade">
		    <table class="table table-condensed table-bordered table-hover">
		    	<thead>
		    		<tr>
		    			<th>Deparment</th>
		    			<th>Position</th>
		    			<th>Effective Date</th>
		    			<th>End Date</th>
		    			<th>Status</th>
		    			<th class="text-center">Action</th>
		    		</tr>
		    	</thead>
		    	@foreach($_archived as $archived)
		    	<tr>
		    		<td>
		    			{{$archived->payroll_department_name}}
		    		</td>
		    		<td>
		    			{{$archived->payroll_jobtitle_name}}
		    		</td>
		    		<td>
		    			{{$archived->payroll_employee_contract_date_hired != '0000-00-00' ? date('M d, Y',strtotime($archived->payroll_employee_contract_date_hired)):''}}
		    		</td>
		    		<td>
		    			{{$archived->payroll_employee_contract_date_end != '0000-00-00' ? date('M d, Y',strtotime($archived->payroll_employee_contract_date_end)) : ''}}
		    		</td>
		    		<td>
		    			{{$archived->employment_status}}
		    		</td>
		    		<td class="text-center">
		    			<div class="dropdown">
							<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
							<span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-custom">
								<li>
									<a href="#" class="popup" link="/member/payroll/employee_list/modal_edit_contract/{{$employee_id}}/{{$archived->payroll_employee_contract_id}}"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
								</li>
								<li>
									<a href="#" class="popup" link="/member/payroll/employee_list/modal_archive_contract/0/{{$archived->payroll_employee_contract_id}}" size="sm"><i class="fa fa-refresh"></i>&nbsp;Restore</a>
								</li>
							</ul>
						</div>
		    		</td>
		    	</tr>
		    	@endforeach
		    </table>
		  </div>
		
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
	</div>
</form>