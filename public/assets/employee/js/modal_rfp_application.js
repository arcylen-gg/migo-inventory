var modal_rfp_application = new modal_rfp_application();
var data = {};
function modal_rfp_application()
{
	init();

	function init()
	{
		ready_document();
	}

	function ready_document()
	{
		$(document).ready(function() 
		{
			action_select_group_approver();
			action_add_category();
			action_remove_category();
			action_change_amount_value();

		});
	}

	function action_add_category()
	{
		$('.add-category').unbind('click');
		$('.add-category').bind('click',function(e)
		{
			event_add_category(e);
		});
	}

	function event_add_category(e)
	{
		$('.dynamic-field').append('<tr><td class="text-center"><input type="text" name="request_payment_description[]" id="request_payment_description" placeholder="Enter Description" class="form-control" required></td><td class="text-center amount-td"><input type="number" name="request_payment_amount[]" id="request_payment_amount" placeholder="Amount" class="form-control request_payment_amount" required></td><td class="text-center remove-category"><button type="button" class="btn btn-md btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>')
	}

	function action_remove_category()
	{
		$('.dynamic-field').on('click', '.remove-category', function(event) 
		{
			$(this).closest('tr').remove();
			event_change_amount_value();
		});
	}

	function action_change_amount_value()
	{
		$('.dynamic-field').on('change', '#request_payment_amount', function(event) 
		{
			event_change_amount_value();
		});
	}

	function event_change_amount_value()
	{
		var amount = 0;
		$('.dynamic-field').children('tr').each(function(index, el) 
		{
			 var val = $(el).children('.amount-td').children('.request_payment_amount').val();
			 if (val != '') 
			 {
			 	amount += parseFloat(val);
			 }
		});
		$('.total-amount').html(amount);
		$('.request_payment_total_amount').val(amount);
	}

	function action_select_group_approver()
	{
		var target = $('.approver_group_list');

		$('.approver_group').unbind('change');
		$('.approver_group').bind('change', function()
		{
			data.approver_group_id = $('.approver_group').val();
			data._token = $('#_token').val();
			data.approver_group_type = 'rfp';
			
			$.ajax(
			{
				url: '/get_group_approver_list',
				type: 'post',
				data: data,
				success : function(data)
				{
					data = JSON.parse(data);
					var html = list_employee_group_by_level(data);
					target.html(html);
				}
			});
		});
	}

	function list_employee_group_by_level(data)
	{
		var html = "<ul class='list-group'>";

		$.each(data, function(level, group_approver) 
		{
			html += '<li> Level '+ level +' Approver/s <ul>'
				$.each(group_approver, function(index, employee_approver) 
				{
					html += '<li>'+ employee_approver['payroll_employee_first_name'] +'</li>';
				});
			html += '</ul></li>'
		});
		
		html += '</ul>';

		return html;
	}
}