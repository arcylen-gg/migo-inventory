<!-- Change action, name and function-->
<div class="leave_whole">
<form class="global-submit" role="form" action="/member/payroll/leave/modal_save_leave_temp" method="POST">

		<button type="button" class="close" data-dismiss="modal" style="margin-right: 15px;">&times;
		</button>
	
	<div class="tab-content padding-top-10">
		  <div id="active-leave_temp" class="tab-pane fade in active">
		  	
		  	<div class="load-data" target="value-id-1">
				<div id="value-id-1">
				
				  	<table class="table table-condensed table-bordered">
				  		<thead>
				  			<tr>
				  				<th style="text-align: center;">Name</th>
				  				<th style="text-align: center;">Date Created</th> 
				  				<th style="text-align: center;">Leave Total</th> 
				  				<th style="text-align: center;">Used Leave</th> 
				  				<th style="text-align: center;">Remaining Leave</th> 
				  				<th width="10%"></th>
				  			</tr>
				  		</thead>
				  		<tbody>
				  			@foreach($emp as $data)
				  				@foreach($data as $dat)
				  			<tr style="text-align: center;">
				  				<td>{{$dat->payroll_employee_display_name}}</td>
				  				<td>{{$dat->payroll_leave_date_created}}</td>
				  				<td>{{$dat->payroll_leave_temp_hours}}</td>
				  				<td>{{$dat->total_leave_consume}}</td>
				  				<td>{{$dat->remaining_leave}}</td>
				  				<td class="text-center"> 
				  					<div class="dropdown">
										<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
										<span class="caret"></span></button>
										<ul class="dropdown-menu dropdown-menu-custom">
											<li>
												<a href="#" class="popup" link="/member/payroll/leave/v2/modal_leave_action/{{$dat->payroll_leave_employee_id}}/convert/{{$dat->remaining_leave}}" size="sm"><i class="fa fa-money"></i>&nbsp;Convert to Cash</a>
											</li>
											<li>
												<a href="#" class="popup" link="/member/payroll/leave/v2/modal_leave_action/{{$dat->payroll_leave_employee_id}}/resetandaccum/{{$dat->remaining_leave}}" size="sm"><i class="fa fa-plus"></i>&nbsp;Reset and Accumulate</a>
											</li>
											<li>
												<a href="#" class="popup" link="/member/payroll/leave/v2/modal_leave_action/{{$dat->payroll_leave_employee_id}}/reset/{{$dat->remaining_leave}}" size="sm"><i class="fa  fa-recycle"></i>&nbsp;Reset</a>
											</li>
											<li>
												<a href="#" class="popup" link="/member/payroll/leave/v2/modal_leave_action/{{$dat->payroll_leave_employee_id}}/resethistory/{{$dat->remaining_leave}}" size="sm"><i class="fa fa-history"></i>&nbsp;Reset history</a>
											</li>
										</ul>
									</div>
				  				</td>
				  			</tr>
				  				@endforeach
				  			@endforeach
				  		</tbody>
				  	</table>

				  	<div class="pagination">  </div>
		  		</div> 
			</div>

		  </div> 

  </div>

</form>
</div>
<script type="text/javascript" src="/assets/member/js/payroll/modal_create_leave_tempv2.js"></script>