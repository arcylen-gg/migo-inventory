var create_commission_calculator = new create_commission_calculator();
var load_table_data = {};

var agent_commission_percent = 0;
var dp_string = 0;
var downpayment = 0;
var tax = 1.12;
var tsp = 0;
var discount = 0;
var total_commission = 0;
var payment_term = 1;

var misc_string = 0;
var misc = 0;

var ndp_string = '';
var ndp = 0;

var tcp_string = '';
var tcp = 0;

var selected_customer='';

function create_commission_calculator()
{
	init();

	function init()
	{
		$(document).ready(function()
		{
			document_ready();
		});
	}

	function document_ready()
	{
		action_initialize_select();
		action_load_datepicker();
		event_compute_all();
		event_change_tcp();
		action_load_date();

	}
	function action_load_datepicker()
	{
		$('.datepicker').datepicker();
	}
	function action_load_date()
	{		
		var start_date 		= $(".datepicker[name='date']").val().split('/');
		var payment_term = 1;
		var year = start_date[2];
		var day = start_date[1];
		var month = start_date[0];
		var new_due_date = add_months(year, month, day, payment_term);
    	$(".datepicker[name='due_date']").val(new_due_date);
	}
	function action_initialize_select()
	{
		$('.select-customer').globalDropList({
			hasPopup : 'true',
			link : '/member/customer/modalcreatecustomer',
			width : '100%',
			onCreateNew : function()
            {
            	selected_customer = $(this);
            },
			onChangeValue : function()
			{
				$(".customer-email").val($(this).find("option:selected").attr("email"));
			}
		});
		$('.select-term').globalDropList({
			hasPopup : 'false',
			link: '/member/maintenance/terms/terms',
			width : '100%',
			onChangeValue : function()
			{
				payment_term = $(this).val();
				var start_date 		= $(".datepicker[name='date']").val().split('/');
				var year = start_date[2];
				var day = start_date[1];
				var month = start_date[0];
				var new_due_date = add_months(year, month, day, payment_term);
            	$(".datepicker[name='due_date']").val(new_due_date);
				event_compute_commission();
			}
		});
		$('.select-property').globalDropList({
			hasPopup : 'true',
			link : "/member/item/v2/add",
			width : '100%',
			link_size : 'lg',
			onChangeValue : function()
			{
				if($(this).val() != '')
				{
					$(".sales-price").val(number_format($(this).find("option:selected").attr("price")));
					tsp = parseFloat($(this).find("option:selected").attr("price"));
					//dd(tsp);
					event_compute_commission();
				}
				else
				{
					$(".sales-price").val('');
					tsp = 0;
					event_compute_commission();
				}
			}
		});
		$('.select-agent').globalDropList({
			hasPopup : 'true',
			link : '/member/cashier/sales_agent/add',
			width : '100%',
			link_size : 'md',
			onChangeValue : function()
			{
				if($(this).val())
				{
					agent_commission_percent = parseFloat($(this).find("option:selected").attr('commission-percent')) / 100;
					event_compute_commission();
				}
			}
		});
	}

	/* AFTER ADDING AN  ITEM */
	function submit_done_item(data)
	{
	    selected_customer.load("/member/customer/load_customer", function()
	    {
	        $(this).globalDropList("reload");
			$(this).val(data.customer_id).change();
			toastr.success("Success");
	    });
	    data.element.modal("hide");
	}
	function number_format(number, tofixed = true)
	{
	    var yourNumber = (parseFloat(number));
		if(tofixed == true)
		{
	    	var yourNumber = (parseFloat(number)).toFixed(2);
		}
	    //Seperates the components of the number
	    var n= yourNumber.toString().split(".");
	    //Comma-fies the first part
	    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	    //Combines the two sections
	    return (n.join("."));
	}
	function event_compute_all()
	{
		$('.compute-all').unbind('keyup');
		$('.compute-all').bind('keyup', function()
		{
			event_compute_commission();
		});
		$('.discount-auto-add-comma').unbind('keyup');
		$('.discount-auto-add-comma').bind('keyup', function()
		{
			$(this).val(auto_comma($(this).val()));	
			event_compute_commission();		
		});
	}
	function event_change_tcp()
	{
		$('.change-tcp').unbind('change');
		$('.change-tcp').bind('change', function()
		{
			// tcp_string = $(this).val();
			// if(tcp_string.indexOf('%') > 0)
			// {
			// 	/*Commission Percent*/
			// 	var tcp_percent = parseFloat(tcp_string.substring(0, tcp_string.indexOf('%')));
			// 	$('.ndp-commission').val((100 - tcp_percent + '%'));
			// 	ndp_string = (100 - tcp_percent + '%');
			// 	$('.ndp-commission').val(ndp_string);
			// }
			// else
			// {
			// 	/*Commission Percent*/
			// 	var tcp_percent = parseFloat(tcp_string);
			// 	$('.ndp-commission').val((100 - tcp_percent));
			// 	ndp_string = (100 - tcp_percent);
			// 	$('.ndp-commission').val(ndp_string);
			// }

			// event_compute_commission();
		});
	}
	function event_compute_commission()
	{
		dp_string = $('.downpayment').val();		

		downpayment = 0;
		discount = parseFloat(($('.discount').val()).replace(',',''));
		if(discount == '')
		{
			discount = 0;
		}
		if(dp_string.indexOf('%') > 0)
		{
			downpayment = (parseFloat(dp_string.substring(0, dp_string.indexOf('%'))) / 100);
		}
		if(dp_string > 0)
		{
			downpayment = (parseFloat(dp_string.substring(0, dp_string)) / 100);
		}

		tsp_string = $('.sales-price').val();	
		/*tsp = parseFloat(($('.sales-price').val()).replace(',',''));*/
		if(tsp_string > 0)
		{
			tsp = (parseFloat(tsp_string));
		}
		
		var amount_downpayment = tsp * downpayment;
		$('.amount-downpayment').val(number_format(amount_downpayment));
		var amount_net_downpayment = amount_downpayment - discount;
		$('.amount-net-downpayment').html('P '+ number_format(amount_net_downpayment));
		$('.amount-monthly-amort-dp').html('P '+ number_format(amount_net_downpayment / payment_term));

		misc_string = $('.misc').val();
		misc = 0;
		if(misc_string.indexOf('%') > 0)
		{
			misc = (parseFloat(misc_string.substring(0, misc_string.indexOf('%'))) / 100);
		}
		else
		{
			misc = parseFloat(misc_string/100);
		}
		var amount_misc = tsp * misc;
		$('.amount-misc').html('P '+ number_format(amount_misc));
		var amount_loanable = tsp - amount_downpayment;
		$('.amount-loanable').html('P '+number_format(amount_loanable));
		var amount_tcp = tsp + amount_misc;
		$('.amount-tcp').html('P '+number_format(amount_tcp));
		var amount_tc = ((tsp - discount) / tax) * agent_commission_percent;
		$('.amount-tc').html('P '+ number_format(amount_tc));
		
		tcp_string = $('.tcp-commission').val();
		ndp_string = $('.ndp-commission').val();
		if(ndp_string.indexOf('%') > 0)
		{
			/*Commission Percent*/
			var ndp_percent = parseFloat(ndp_string.substring(0, ndp_string.indexOf('%')));
			$('.tcp-commission').val((100 - ndp_percent)+'%');
			tcp_string = (100 - ndp_percent)+'%';			
		}
		else
		{
			/*Commission Percent*/
			var ndp_percent = parseFloat(ndp_string);
			$('.tcp-commission').val((100 - ndp_percent));
			tcp_string = String(100 - ndp_percent);
		}


		tcp = 0;
		if(tcp_string.indexOf('%') > 0)
		{
			tcp = (parseFloat(tcp_string.substring(0, tcp_string.indexOf('%'))) / 100);
		}
		else
		{
			tcp = parseFloat(tcp_string/100);
		}
		var amount_tcp1 = amount_tc * tcp;
		$('.amount-tcp1').html('P '+number_format(amount_tcp1));

		ndp = 0;
		if(ndp_string.indexOf('%') > 0)
		{
			ndp = (parseFloat(ndp_string.substring(0, ndp_string.indexOf('%'))) / 100);
		}
		else
		{
			ndp = parseFloat(ndp_string/100);
		}
		var amount_ndp = amount_tc * ndp;
		$('.amount-ndp').html('P '+number_format(amount_ndp));

		$('.input-tcp').val(amount_tcp);
		$('.input-tc').val(amount_tc);
		$('.input-loanable-amount').val(amount_loanable);
		$('.c-amount-tsp').html(number_format(tsp, false));
		$('.c-amount-disc').html(number_format(discount, false));
		$('.c-amount-tax').html(number_format(tax));
		$('.c-amount-commission').html((agent_commission_percent * 100) + '%');
		$('.c-amount-dp').html((downpayment * 100) + '%');
	}
	function event_accept_number_only()
	{
		$(document).on("keypress",".number-input", function(event){
			if(event.which < 46 || event.which > 59) {
		        event.preventDefault();
		    } // prevent if not number/dot

		    if(event.which == 46 && $(this).val().indexOf('.') != -1) {
		        event.preventDefault();
		    } // prevent if already dot

		});

		$(document).on("change",".number-input", function(){
			$(this).val(function(index, value) {		 
			    var ret = '';
			    value = action_return_to_number(value);
			    if(!$(this).hasClass("txt-qty")){
			    	value = parseFloat(value);
			    	value = value.toFixed(2);
			    }
			    if(value != '' && !isNaN(value)){
			    	value = parseFloat(value);
			    	ret = action_add_comma(value).toLocaleString();
			    }

				return ret;
			  });
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
	function action_return_to_number(number = '')
	{
		number += '';
		number = number.replace(/,/g, "");
		if(number == "" || number == null || isNaN(number)){
			number = 0;
		}
		
		return parseFloat(number);
	}
	function auto_comma(Num)
	{ //function to add commas to textboxes
        Num += '';
        Num = Num.replace(',', ''); Num = Num.replace(',', ''); Num = Num.replace(',', '');
        Num = Num.replace(',', ''); Num = Num.replace(',', ''); Num = Num.replace(',', '');
        x = Num.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1))
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        return x1 + x2;
    }
	function add_months($year, $months, $day, $add_months)
	{
		var start_date = new Date($year, $months, $day);
		var new_date_string = start_date.setMonth(parseInt(start_date.getMonth() + parseInt($add_months-1)));
		var dt = new Date(new_date_string);
		var real_date = parseInt(dt.getMonth() + 1)+'/'+dt.getDate()+'/'+dt.getFullYear();
		return real_date;
	}

}
function success_commission(data)
{
	toastr.success('Success');
	location.reload();
}

/* AFTER ADDING A CUSTOMER */
function success_update_customer(data)
{
    $(".select-customer").load("/member/customer/load_customer", function()
    {                
         data.element.modal("hide");
         $(".select-customer").globalDropList("reload");
         $(".select-customer").val(data.id).change();          
    });
}
function success_agent(data)
{
    $(".select-agent").load("/member/cashier/sales_agent/load-agent", function()
    {                
         data.element.modal("hide");
         $(".select-agent").globalDropList("reload");
         $(".select-agent").val(data.id).change();      
    });
}
function success_item(data)
{
    $(".select-property").load("/member/cashier/commission_calculator/load-item", function()
    {
    	data.element.modal("hide");
        $(".select-property").globalDropList("reload");
		$(".select-property").val(data.item_id).change();
    });
}

