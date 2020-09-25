var payroll_group = new payroll_group();


function payroll_group()
{
	
	init();

	function init()
	{
		$(document).ready(function()
		{
			document_ready();
			remove_tag();
		})
	}

	function document_ready()
	{
		custom_drop_down('.shift_code_id');	
	}

	function custom_drop_down(target)
	{
		$(target).globalDropList(
		{  
		  hasPopup                : "false",
		  link                    : "/member/payroll/shift_template/modal_create_shift_template",
		  link_size               : "lg",
		  width                   : "100%",
		  maxHeight				  : "129px",
		  placeholder             : "Search....",
		  no_result_message       : "No result found!",
		  onChangeValue           : function(){},
		  onCreateNew             : function(){
		  								
		  							},
		});
	}

	
	this.load_tag_employee_editv2 = function()
	{
		$(".tbl-tag").html('<tr><td colspan="3" class="text-center">'+misc('loader') + '</td></tr>');
		$.ajax({
			url 	: 	"/member/payroll/payroll_group/get_payroll_tag_employee_edit",
			type 	: 	"POST",
			data 	: 	{
				_token:misc('_token'),
				group_id : $("#group_id").val()
			},
			success : 	function(result)
			{
				result = JSON.parse(result);
				var html = "";
				console.log(result);
				$(result.new_record).each(function(index, emp)
				{			
						html += tbl_tag2(emp,result.group_id);
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


	this.load_tag_employee = function()
	{
		$(".tbl-tag").html('<tr><td colspan="3" class="text-center">'+misc('loader') + '</td></tr>');
		$.ajax({
			url 	: 	"/member/payroll/payroll_group/get_payroll_tag_employee",
			type 	: 	"POST",
			data 	: 	{
				_token:misc('_token')
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
			url 	: 	"/member/payroll/payroll_group/load_payroll_employee_tag",
			type 	: 	"POST",
			data 	: 	{
				_token:misc('_token'),
				group_id : $("#group_id").val()
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
						url 	: 	"/member/payroll/payroll_group/remove_payroll_tag_employee",
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
		html += '<td><a href="#" class="btn-remove-tag" value="0" data-content="'+data.payroll_employee_id+'"><i class="fa fa-times"></i></a></td>';
		html += '</tr>';

		return html;
	}

	function tbl_tag2(data,id)
	{
		var html ='<tr>';
		html +=	'<td>' + data.payroll_employee_display_name + '<input type="hidden" name="employee_tag[]" value="'+data.payroll_employee_id+'"></td>';
		html +=	'<td class="text-center"><a href="#" class="popup" size="sm" link="/member/payroll/leave/v2/modal_leave_action/'+data.payroll_employee_id+'/remove_payroll_tag/'+id+'"><i class="fa fa-times"></i></a></td>';
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
}