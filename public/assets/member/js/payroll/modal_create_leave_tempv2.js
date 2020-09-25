var modal_create_leave_tempv2 = new modal_create_leave_tempv2();
var ajaxdata = {};

function modal_create_leave_tempv2()
{
	init();

	function init()
	{
		default_with_pay_val();
		load_monthly_leave_report();

		$(document).ready(function()
		{
			document_ready();

		})
	}

	function document_ready()
	{
		load_monthly_leave_report();
	}

	function load_monthly_leave_report()
	{    
		$(".payroll_schedule_leave_end").on("change", function(e)
		{
			ajaxdata.company_id = $('.select-company-name').val();
			ajaxdata._token 	= $("._token").val();
			ajaxdata.category	= $("#category").val();
			ajaxdata.date_start = $("#start").val();
			ajaxdata.date_end   = $(this).val();
			$('#spinningLoader').show();
			$(".load-filter-data").hide();
	        setTimeout(function(e){
			$.ajax(
			{
				url:"/member/payroll/leave/v2/monthly_leave_report_filter",
				type:"post",
				data: ajaxdata,
				
				success: function(data)
				{
					$('#spinningLoader').hide();
					$(".load-filter-data").show();
					$(".load-filter-data").html(data);
					// alert(data);
				}
			});
			}, 700);
		});

		$(".payroll_schedule_leave_start").on("change", function(e)
		{
			ajaxdata.company_id = $('.select-company-name').val();
			ajaxdata._token 	= $("._token").val();
			ajaxdata.category	= $("#category").val();
			ajaxdata.date_end   = $("#end").val();
			ajaxdata.date_start = $(this).val();
			$('#spinningLoader').show();
			$(".load-filter-data").hide();
	        setTimeout(function(e){
			$.ajax(
			{
				url:"/member/payroll/leave/v2/monthly_leave_report_filter",
				type:"post",
				data: ajaxdata,
				
				success: function(data)
				{
					$('#spinningLoader').hide();
					$(".load-filter-data").show();
					$(".load-filter-data").html(data);
					// alert(data);
				}
			});
			}, 700);
		});

		$('.select-company-name').change(function()
        {
            ajaxdata.company_id = this.value;
            ajaxdata._token 	= $("._token").val();
			ajaxdata.category	= $("#category").val();
			ajaxdata.date_end   = $("#end").val();
			ajaxdata.date_start = $("#start").val();

			$('#spinningLoader').show();
			$(".load-filter-data").hide();
          	setTimeout(function(e){
			$.ajax(
			{
				url:"/member/payroll/leave/v2/monthly_leave_report_filter",
				type:"post",
				data: ajaxdata,
				
				success: function(data)
				{
					$('#spinningLoader').hide();
					$(".load-filter-data").show();
					$(".load-filter-data").html(data);
					// alert(data);
				}
			});
			}, 700);
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
					url 	: 	"/member/payroll/leave/v2/remove_leave_tag_employeev2",
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

	this.load_employee_tagv2 = function()
	{
		$(".tbl-tag").html('<tr><td colspan="3" class="text-center">'+misc('loader') + '</td></tr>');
		$.ajax({
			url 	: 	"/member/payroll/leave/v2/get_leave_tag_employeev2",
			type 	: 	"POST",
			data 	: 	{
				_token:misc('_token')
			},
			success : 	function(result)
			{
				result = JSON.parse(result);
				var html = "";

				$(result.new_record).each(function(index, emp)
				{			
							html += tbl_tag(emp);
				});
				$(".tbl-tag").html(html);
				event_time_entry();
				remove_tag();
			},
			error 	: 	function(err)
			{
				error_function();
			}
		});
	}

	this.load_for_edit_leave_temp = function()
	{
		reload_leave_employee();
		$(".tbl-tag").html('<tr><td colspan="3" class="text-center">'+misc('loader') + '</td></tr>');
		$.ajax({
			url 	: 	"/member/payroll/leave/v2/get_leave_tag_employeev2",
			type 	: 	"POST",
			data 	: 	{
				_token:misc('_token')
			},
			success : 	function(result)
			{
				result = JSON.parse(result);
				var html = "";

				$(result.new_record).each(function(index, emp)
				{			
							html += tbl_tag(emp);
				});
				$(".tbl-tag").html(html);
				event_time_entry();
				remove_tag();
			},
			error 	: 	function(err)
			{
				error_function();
			}
		});
	}



/*	this.reload_leave_employee = function()
	{
		reload_leave_employee();
	}*/

	function reload_leave_employee()
	{
		var action = "/member/payroll/leave/v2/reload_leave_employeev2";
		var method = "POST";
		var formdata = {
			_token:misc('_token'),
			payroll_leave_temp_id:$("#payroll_leave_temp_id").val()
		};
		var target = ".leave_whole";
		$(target).html(misc('loader'));
		$.ajax({
			url	 	: 	action,
			type	: 	method,
			data 	: 	formdata,
			success : 	function(result)
			{
				$(target).html(result);
			},
			error 	: 	function(err)
			{
				error_function();
			}
		});	
	}

	function error_function()
	{
		toastr.error("Error, something went wrong.");
	}

	function tbl_tag(data)
	{

		var html = '<tr>';
		html += '<td>' + data.payroll_employee_title_name + ' ' + data.payroll_employee_first_name + ' ' + data.payroll_employee_middle_name  + ' ' + data.payroll_employee_last_name  + ' ' + data.payroll_employee_suffix_name  + ' <input type="hidden" name="employee_tag[]" value="'+data.payroll_employee_id+'"></td>';
		html += '<td class="text-center edit-data zerotogray" width="25%"><input type="text" name="leave_hours_'+data.payroll_employee_id+'" placeholder="00:00" class="text-center form-control break time-entry time-target time-entry-24 is-timeEntry"></td>';
		html += '<td><a href="#" class="btn-remove-tag" data-content="'+data.payroll_employee_id+'"><i class="fa fa-times"></i></a></td>';
		html += '</tr>';

		return html;
	}

	function event_time_entry()
	{
		$(".time-entry").timeEntry('destroy');
		$(".time-entry-24").timeEntry('destroy');
		$(".time-entry.time-in").timeEntry({ampmPrefix: ' ', defaultTime: new Date(0, 0, 0, 0, 0, 0)});
		$(".time-entry.time-out").timeEntry({ampmPrefix: ' ', defaultTime: new Date(0, 0, 0, 12, 0, 0)});
		$(".time-entry-24").timeEntry({unlimitedHours: true, defaultTime: new Date(0, 0, 0, 0, 0, 0)});
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

	function default_with_pay_val()
	{
		$("body").on("click", ".payroll_leave_temp_with_pay", function(e){
			alert("Currently we support with pay value of 'Yes'!");
			return false;
		});
	}
}