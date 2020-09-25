var shifting = new shifting();

function shifting()
{
	init();

	function init()
	{
		event_add_sub_time(); //GUILLERMO
		event_check(); //JIMAR
		event_time_entry(); //GUILLERMO
		event_remove_time_entry();
		action_set_time_entry(); //GUILLERMO
		event_check_flexitime(); //KIM BRIEL
		action_show_break_hour(); // KIM BRIEL
		
	}
	function event_add_sub_time()
	{
		$(".add-sub-time").click(function(e)
		{
			action_append_sub_time($(e.currentTarget));
		})
	}
	function event_check()
	{
		$(".restday-check").unbind("change");
		$(".restday-check").bind("change", function () {
			var parent = $(this).parents('tr').find('.extraday-check');
			if($(this).is(":checked"))
			{
				parent.prop("checked", false);
			}

		});

		$(".extraday-check").unbind("change");
		$(".extraday-check").bind("change", function () {
			var parent = $(this).parents('tr').find('.restday-check');
			if($(this).is(":checked"))
			{
				parent.prop("checked", false);
			}

		});
	}
	function event_time_entry()
	{
		$(".time-entry.in").unbind("change");
		$(".time-entry.in").bind("change", function(e)
		{
			$(e.currentTarget).closest(".main-con").find(".time-entry.out").timeEntry('destroy');
			$(e.currentTarget).closest(".main-con").find(".time-entry.out").timeEntry({'minTime':$(e.currentTarget).val(), ampmPrefix: ' ', defaultTime: new Date(0, 0, 0, 12, 0, 0)});
		});

		$(".time-entry.out").unbind("change");
		$(".time-entry.out").bind("change", function(e)
		{
			$(e.currentTarget).closest(".main-con").find(".time-entry.in").timeEntry('destroy');
			$(e.currentTarget).closest(".main-con").find(".time-entry.in").timeEntry({'maxTime':$(e.currentTarget).val(), ampmPrefix: ' ', defaultTime: new Date(0, 0, 0, 12, 0, 0)});
		});
	}

	function event_check_flexitime()
	{

		$(".flexitime-check").change(function(e) 
		{
			if(e.currentTarget.checked) 
			{
				console.log("a");
        		$(e.currentTarget).closest(".main-time").find(".break_hours").removeClass("hidden");
   			}
   			else
   			{
   				console.log("b");
   				$(e.currentTarget).closest(".main-time").find(".break_hours").addClass("hidden");
   				$(e.currentTarget).closest(".main-time").find(".break_hours").val("0");
   			}
		});
	}

	this.load_tag_employee = function()
	{
		$(".tbl-tag").html('<tr><td colspan="3" class="text-center">'+misc('loader') + '</td></tr>');
		$.ajax({
			url 	: 	"/member/payroll/shift_template/get_shift_tag_employee",
			type 	: 	"POST",
			data 	: 	{
				_token:misc('_token'),
				shift_id : $("#shift_id").val()
			},
			success : 	function(result)
			{
				result = JSON.parse(result);
				var html = "";
				console.log(result);
				$(result.new_record).each(function(index, emp)
				{			
						html += tbl_tag2(emp,result.shift_id);
				});
				$(".tbl-tag").html(html);
				remove_tag();
			},
			error 	: 	function(err)
			{
				error_function();
			}
		});
	}


	this.load_add_tag_employee = function()
	{
		$(".tbl-tag").html('<tr><td colspan="3" class="text-center">'+misc('loader') + '</td></tr>');
		$.ajax({
			url 	: 	"/member/payroll/shift_template/get_shift_add_tag_employee",
			type 	: 	"POST",
			data 	: 	{
				_token:misc('_token'),
			},
			success : 	function(result)
			{
				result = JSON.parse(result);
				var html = "";
				console.log(result);
				$(result.new_record).each(function(index, emp)
				{			
						html += tbl_tag(emp);
				});
				$(".tbl-tag").html(html);
				remove_tag();
			},
			error 	: 	function(err)
			{
				error_function();
			}
		});
	}

	this.load_edit_tag_employee = function()
	{
		$(".tbl-tag").html('<tr><td colspan="3" class="text-center">'+misc('loader') + '</td></tr>');
		$.ajax({
			url 	: 	"/member/payroll/shift_template/load_shift_employee_tag",
			type 	: 	"POST",
			data 	: 	{
				_token:misc('_token'),
				shift_id : $("#shift_id").val()
			},
			success : 	function(result)
			{	
				result = JSON.parse(result);
				var html = "";
				console.log(result);
				$(result.new_record).each(function(index, emp)
				{			
						html += tbl_tag2(emp);
				});
				$(".tbl-tag").html(html);
				remove_tag();
			},
			error 	: 	function(err)
			{
				error_function();
			}
		});
	}

	function remove_tag()
		{
			$(".btn-remove-tag").unbind("click");
			$(".btn-remove-tag").bind("click", function()
			{
				var content 	= $(this).data("content");
				var con 		= confirm("Do you really want to remove this employee from tagging?");
				var parent 		= $(this).parents("tr");
				var element 	= $(this);
				var html 		= element.html();

				if(con)
				{	
					element.html(misc('spinner'));
					$.ajax({
						url 	: 	"/member/payroll/shift_template/remove_shift_tag_employee",
						type 	: 	"POST",
						data 	: 	{
							_token:misc('_token'),
							content:content
						},
						success : 	function(result)
						{
							parent.remove();
						},
						error 	: 	function(err)
						{
							error_function();
							element.html(html);
							remove_tag()
						}
					});
				}
			});
		}

	function tbl_tag(data)
	{

		var html = '<tr>';
		html += '<td>' + data.payroll_employee_title_name + ' ' + data.payroll_employee_first_name + ' ' + data.payroll_employee_middle_name  + ' ' + data.payroll_employee_last_name  + ' ' + data.payroll_employee_suffix_name  + ' <input type="hidden" name="employee_tag[]" value="'+data.payroll_employee_id+'"></td>';
		html += '<input type="hidden" name="'+data.payroll_employee_id+'" class="text-center form-control break ">';
		html += '<td><a href="#" class="btn-remove-tag" data-content="'+data.payroll_employee_id+'"><i class="fa fa-times"></i></a></td>';
		html += '</tr>';

		return html;
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


	function event_remove_time_entry()
	{
		$(".remove-time-entry").unbind("click");
		$(".remove-time-entry").bind("click", function(e)
		{
			$(e.currentTarget).closest(".main-con").remove();
		});
	}

	function event_flexitime_check()
	{
		$(".flexitime-check").change(function(e){
			if (e.currentTarget.checked) 
			{
				$(e.currentTarget).closest(".main-time").find(".-check").removeClass("hidden");
			}
			else
			{
				$(e.currentTarget).closest(".main-time").find(".flexitime-check").addClass("hidden");
				$(e.currentTarget).closest(".main-time").find(".flexitime-check").val("0");
			}
		});
	}

	function action_set_time_entry()
	{
		$(".time-entry.in").timeEntry('destroy');
		$(".time-entry.out").timeEntry('destroy');
		$(".time-entry.in").timeEntry({ampmPrefix: ' ', defaultTime: new Date(0, 0, 0, 0, 0, 0)});
		$(".time-entry.out").timeEntry({ampmPrefix: ' ', defaultTime: new Date(0, 0, 0, 12, 0, 0)});
	}

	function tbl_tag2(data,id)
	{
		var html ='<tr>';
		html +=	'<td>' + data.payroll_employee_display_name + '<input type="hidden" name="employee_tag[]" value="'+data.payroll_employee_id+'"></td>';
		html +=	'<td class="text-center"><a href="#" class="popup" size="sm" link="/member/payroll/leave/v2/modal_leave_action/'+data.payroll_employee_id+'/remove_shift_tag/'+id+'"><i class="fa fa-times"></i></a></td>';
		html += '</tr>';

		return html;

	}

	function action_show_break_hour()
	{
		$(".flexitime-check").each(function(e)
		{
			// console.log($(this).is(":checked"));
			if($(this).is(":checked")) 
			{
				$(this).closest(".main-time").find(".break_hours").removeClass("hidden");
			}
			else
			{
				$(this).closest(".main-time").find(".break_hours").addClass("hidden");
				$(this).closest(".main-time").find(".break_hours").val("0");
			}
		});
	}

	
	function action_append_sub_time($target)
	{
		$day = $target.closest(".main-time").attr("day");

		$append = '<tr class="editable sub-time main-con">' +
							'<td></td>' +
							'<td></td>' +
							'<td class="editable">' +
								'<input type="text" placeholder="NO TIME" name="work_start[' + $day + '][]" class="text-table time-entry in" >' +
							'</td>' +
							'<td class="editable">' +
								'<input type="text" placeholder="NO TIME" name="work_end[' + $day + '][]" class="text-table time-entry out" >' +
							'</td>' +
							'<td>' +
								'<button type="button" class="btn btn-default remove-time-entry"><i class="fa fa-close"></i></button>' +
							'</td>' +
							'<td class="text-center"></td>' +
							'<td class="text-center"></td> ' +
							'<td class="text-center"></td>' +
						'</tr>';

		$target.closest(".main-time").after($append);

		action_set_time_entry();
		event_time_entry();
		event_remove_time_entry();
	}
}