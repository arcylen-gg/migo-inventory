<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">Shift Schedule</h4>
	
</div>
<form class="modal-body form-horizontal form-schedule-employee" action="/member/payroll/payroll_period_list/save_shift_per_employee" method="POST">

	<input type="hidden" value="{{$id}}" id="payroll_period_id" name="payroll_period_id">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<div class="form-group">
		<!-- <div class="col-md-4">
			<div class="list-group">
			  <a href="#" class="list-group-item active">First item</a>
			  <a href="#" class="list-group-item">Second item</a>
			  <a href="#" class="list-group-item">Third item</a>
			</div>
		</div> -->
		<div class="col-md-12">
			<div class="form-group">
				<div class="col-md-12">
					<small>Select By Company</small>
					<select class="form-control form-select company-select">
						<option value="0">Select</option>
						@foreach($_company as $company)
						<option value="{{$company->payroll_company_id}}">{{$company->payroll_company_name}}</option>
						@endforeach
					</select>
				</div>				
			</div>
			<div class="form-group">
				<div class="col-md-6">
					<small>Select By Department</small>
					<select class="form-control department-select">
						<option value="0">Select Department</option>
						@foreach($_department as $department)
						<option value="{{$department->payroll_department_id}}">{{$department->payroll_department_name}}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-6">
					<small>Select By Job Title</small>
					<select class="form-control form-select jobtitle-select">
						<option value="0">Select By Job Title</option>
						@foreach($_jobtitle as $jobtitle)
						<option value="{{$jobtitle->payroll_jobtitle_id}}">{{$jobtitle->payroll_jobtitle_name}}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-6">
					<small>Select By Employee</small>
					<select class="select-employee select-employee-shift form-control" id="select-employee" name="payroll_employee_id">
						<option value="">Select By Employee</option>
						@foreach($_employee as $employee)
						<option value="{{$employee->payroll_employee_id}}" data-group="{{$employee->payroll_group_id}}">{{$employee->payroll_employee_title_name.' '.$employee->payroll_employee_first_name.' '.$employee->payroll_employee_middle_name.' '.$employee->payroll_employee_last_name.' '.$employee->payroll_employee_suffix_name}}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-6">
					<small>Shift Template</small>
					<select class="form-control shift-template select-employee-shift">
						<option value="">Select Template</option>
						@foreach($_shift as $shift)
						<option value="{{$shift->shift_code_id}}">{{$shift->shift_code_name}}</option>
						@endforeach
					</select>
				</div>
			</div>
			<hr>
			<div class="form-group">
				<div class="col-md-12 div-schedule-tbl"></div>
			</div>
		</div>
	</div>
</form>
<div class="modal-footer">
	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Done</button>
	<!-- <button class="btn btn-primary btn-custom-primary" type="button"">Submit</button> -->
</div>

<script type="text/javascript">
	submit_schedule();
	sel_jobtitle();
	select_employee();
	// combo_box_employee();

	// $(".form-select").globalDropList({
	// 	hasPopup                : "false",
	// 	link_size               : "lg",
	// 	width                   : "100%",
	// 	maxHeight				: "129px",
	// 	placeholder             : "Select Employee",
	// 	no_result_message       : "No result found!",
	// 	onChangeValue           : function(){
	// 		select_employee_ajax();
	// 	},
	// 	onCreateNew             : function(){},
	// });


	// $(".department-select").globalDropList({
	// 	hasPopup                : "false",
	// 	link_size               : "lg",
	// 	width                   : "100%",
	// 	maxHeight				: "129px",
	// 	placeholder             : "Select Employee",
	// 	no_result_message       : "No result found!",
	// 	onChangeValue           : function(){
	// 		select_change_action($(this).val());
	// 		select_employee_ajax();
	// 		$(".jobtitle-select").globalDropList("reload");
	// 		$(".jobtitle-select").globalDropList({
	// 			placeholder : '...loading'
	// 		});
	// 	},
	// });

	// $(".shift-template").globalDropList({
	// 	hasPopup                : "false",
	// 	link_size               : "lg",
	// 	width                   : "100%",
	// 	maxHeight				: "129px",
	// 	placeholder             : "Select Employee",
	// 	no_result_message       : "No result found!",
	// 	onChangeValue           : function(){

	// 		select_shift_template();
	// 	},
	// });

	// function combo_box_employee()
	// {
	// 	$(".select-employee").globalDropList({
	// 		hasPopup                : "false",
	// 		link_size               : "lg",
	// 		width                   : "100%",
	// 		maxHeight				: "129px",
	// 		placeholder             : "Select Employee",
	// 		no_result_message       : "No result found!",
	// 		onChangeValue           : function(){

	// 			select_shift_template();
	// 		},
	// 	});
	// }


	$(".company-select").unbind("change");
	$(".company-select").bind("change", function()
	{
		select_employee_ajax();
	});

	$(".department-select").unbind("change");
	$(".department-select").bind("change", function()
	{
		select_employee_ajax();
		select_change_action($(this).val());
	});


	function sel_jobtitle()
	{
		$(".jobtitle-select").unbind("change");
		$(".jobtitle-select").bind("change", function()
		{
			select_employee_ajax();
		});
	}

	function select_employee()
	{
		$(".select-employee-shift").unbind("change");
		$(".select-employee-shift").bind("change", function(){
			select_shift_template();
			console.log('test');
		});

		// $(".select-employee").unbind("change");
		// $(".select-employee").bind("change", function()
		// {
		// 		console.log($(this).val());
		// });
	}

	function submit_schedule()
	{
		$(".form-schedule-employee").unbind("submit");
		$(".form-schedule-employee").bind("submit", function(e)
		{
			e.preventDefault();
			var btn_html = $(".submit-schedule-btn").html();

			var formdata = $(this).serialize();
			var action = $(this).attr("action");
			var method = $(this).attr("method");
			$(".submit-schedule-btn").html(misc('spinner'));
			$.ajax({
				url 	: 	action,
				type 	: 	method,
				data 	: 	formdata,
				success : 	function(result)
				{	
					toastr.success("Employee Shift has been updated.");
					submit_schedule();
					$(".submit-schedule-btn").html(btn_html);
				},
				error 	: function(err)
				{
					error_function();
					$(".submit-schedule-btn").html(btn_html);
				}
			});
		});
	}

	function select_employee_ajax()
	{
		var payroll_period_id = $("#payroll_period_id").val();
		var company = $(".company-select").val();
		var department = $(".department-select").val();
		var jobtitle = $(".jobtitle-select").val();
		var formdata = {
			payroll_period_id:payroll_period_id,
			company:company,
			department:department,
			jobtitle:jobtitle,
			_token:misc('_token')
		};

		$(".select-employee").html('<option value="">loading employee...</option>');

		$.ajax({
			url 	: 	'/member/payroll/payroll_period_list/ajax_employee_schedule',
			type 	: 	'POST',
			data 	: 	formdata,
			success : 	function(result)
			{

				var html = '<option value="0">Select By Employee</option>';

				try
				{
					result = JSON.parse(result);
				}
				catch(err){}

				$(result).each(function(index, data)
				{
					// console.log(data);

					html += '<option value="'+data.payroll_employee_id+'" data-group="'+data.payroll_group_id+'">'+data.payroll_employee_title_name + ' ' + data.payroll_employee_first_name + ' ' + data.payroll_employee_middle_name + ' ' + data.payroll_employee_last_name + ' ' + data.payroll_employee_suffix_name + '</option>';
				});

				$(".select-employee").html(html);
				
				// $(".select-employee").globalDropList("reload");
				select_employee();
			},
			error 	: 	function(err)
			{
				error_function();
			}
		});
		
	}

	function select_shift_template()
	{
		var employee_id = $(".select-employee").val();
		console.log('employee_id : ' + employee_id);
		// var employee_id = document.getElementById('select-employee').value;
		var shift_template_id = $(".shift-template").val();
		var group 		= $('.select-employee option:selected').attr('data-group');
		var action 		= '/member/payroll/payroll_period_list/shift_template_refence';
		var method 		= 'POST';
		var formdata 	= {
			payroll_period_id:$("#payroll_period_id").val(),
			employee_id:employee_id,
			shift_template_id:shift_template_id,
			_token:misc('_token'),
			group:group
		};
		var target = '.div-schedule-tbl';
		console.log('group : ' + group);
		console.log('employee_id : ' + employee_id);

		if(employee_id != '' && employee_id != 0 && employee_id != null && group != undefined)
		{
			load_configuration(action, method, target, formdata);
		}
		else
		{
			$(target).html('');
		}
	}
	
	function select_change_action(payroll_department_id = 0, selected = 0)
	{
		// var payroll_department_id = $(".department-select").val();
		var pre_data = '<option value="">loading job title...</option>';
		$(".jobtitle-select").html(pre_data);
		$.ajax(
			{
				url 	: 	"/member/payroll/jobtitlelist/get_job_title_by_department",
				type 	: 	"POST",
				data 	: 	{
					payroll_department_id:payroll_department_id,
					_token:misc('_token')
				},
				success : 	function(result)
				{
					var json = JSON.parse(result);

					var html = '<option value="0">Select By Job Title</option>';

					$(json).each(function(index, data)
					{
						html += '<option value="'+data.payroll_jobtitle_id+'">'+data.payroll_jobtitle_name+'</option>';
					});
					$(".jobtitle-select").html(html);
					// $(".jobtitle-select").globalDropList("destroy");
					// $(".jobtitle-select").globalDropList({
					// 	hasPopup    : "false",
					// 	placeholder : 'Job Title',
					// 	width 		: '100%'
					// });
					// $(".jobtitle-select").globalDropList("reload");
					// $(".jobtitle-select").val(selected).change();
					
				},
				error 	: 	function(err)
				{
					error_function();
				}
			});
	}

	function misc(str){
		var spinner = '<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span>';
		var plus = '<i class="fa fa-plus" aria-hidden="true"></i>';
		var times = '<i class="fa fa-times" aria-hidden="true"></i>';
		var pencil = '<i class="fa fa-pencil" aria-hidden="true"></i>';
		var loader = '<div class="loader-16-gray"></div>'
		var _token = $("#_token").val();

		switch(str){
			case "spinner":
				return spinner
				break;

			case "plus":
				return plus
				break;

			case "loader":
				return loader
				break;

			case "_token":
				return _token
				break;
			case "times":
				return times
				break;
			case "pencil":
				return pencil
				break;
		}
	}


	function load_configuration(action = "", method = "POST", target = ".configuration-div", formdata = [], toaster_str = '')
	{
		$(target).html(misc('loader'));
		$.ajax({
			url 	: 	action,
			type 	:  	method,
			data 	: 	formdata,
			success : 	function(result)
			{
				$(target).html(result);
				if(toaster_str != '')
				{
					toastr.success(toaster_str);
				}
			},
			error  	: 	function()
			{
				error_function();
			}
		});
	}

	function error_function()
	{
		toastr.error("Error, please contact the administrator.");
	}

	function misc(str){
		var spinner = '<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span>';
		var plus = '<i class="fa fa-plus" aria-hidden="true"></i>';
		var times = '<i class="fa fa-times" aria-hidden="true"></i>';
		var pencil = '<i class="fa fa-pencil" aria-hidden="true"></i>';
		var loader = '<div class="loader-16-gray"></div>'
		var _token = $("#_token").val();

		switch(str){
			case "spinner":
				return spinner
				break;

			case "plus":
				return plus
				break;

			case "loader":
				return loader
				break;

			case "_token":
				return _token
				break;
			case "times":
				return times
				break;
			case "pencil":
				return pencil
				break;
		}
	}
</script>
