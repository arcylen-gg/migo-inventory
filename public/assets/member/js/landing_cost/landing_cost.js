var landing_cost = new landing_cost();

function landing_cost()
{
	init();

	function init()
	{
		action_compute();
		event_change_txt();
	}
	function action_compute()
	{
		$costprice = $(".cost-input-value").val();
		$(".tr-cost").each(function()
		{
			$type = $(this).find('.cost-type').val();
			$value = action_return_to_number($(this).find('.cost-value').val());

			if($type == 'percentage')
			{
				$value = parseFloat($costprice) * (parseFloat($value)/100);
			}

			$(this).find('.cost-amount').html(action_add_comma((parseFloat($value)).toFixed(2))).change();
			$(this).find('.input-cost-amount').val((parseFloat($value)).toFixed(2)).change();
		});
		$total_cost = 0;
		$(".cost-amount").each(function()
		{
			$total_cost += parseFloat(action_return_to_number($(this).html()));
		});
		$(".landing-cost-amount").html('PHP '+action_add_comma($total_cost.toFixed(2)));
	}

	function action_return_to_number(number = '')
	{
		number += '';
		number = number.replace(/,/g, "");
		if(number == "" || number == null || isNaN(number)){
			number = 0;
		}
		
		return parseFloat(number);
	}
	function event_change_txt()
	{
		$(document).on("change",".compute",function()
		{
			action_compute();
		});
	}
	function action_add_comma(number)
	{
		number += '';
		if(number == ''){
			return '';
		}

		else{
			return number.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		}
	}
}