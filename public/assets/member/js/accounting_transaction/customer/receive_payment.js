var receive_payment = new receive_payment();
var maximum_payment = 0;
var balance_payable = 0;
var is_amount_receive_modified = false;
var customer_id = null;
var payment_method_text = 'Cash'; //select box text
var payment_method_value = null; // select box value
function receive_payment()
{
	init();

	function init()
	{
		document_ready();
	}

	function document_ready()
	{
		event_line_check_change();
		event_payment_amount_change();
		event_received_amount_change();
		event_button_action_click();

		action_initialize_load();
		action_load_rp_onload();
		action_load_cm_applied();
		event_cm_applied_change();
		remove_cm_applied();

		update_total_amount(formatFloat(action_total_amount_apply()) - formatFloat(action_cm_apply()));

		action_load_customer_selected();
		event_click_check_all();
		event_load_open_transaction();
	} 
	function action_load_customer_selected()
	{
		if($(".drop-down-customer").val() && !$(".rp-id-class").val())
		{
			customer_id = $(".drop-down-customer").val();
			rp_id = $(".rp-id-class").val();

			$(".loading-tbody").removeClass("hidden");
			$(".tbody-item").load("/member/transaction/receive_payment/load-customer-receive-payment?cust="+ (customer_id != '' ? customer_id : 0+"&rp_id="+rp_id), function()
	    	{
				$(".loading-tbody").addClass("hidden");
	    		action_compute_maximum_amount(function(){});
				si_id = $(".si-id").val();
				if(si_id)
				{
					$(".inputinv-"+si_id).val(1);
					$(".checkboxinv-"+si_id).prop("checked",true);
					action_change_input_value($(".checkboxinv-"+si_id));
				}
	    	});
	    }
	} 
	function action_load_cm_applied()
	{	
		check_selected_customer();;
	} 
	function check_selected_customer()
	{
		$val = $(".drop-down-customer").val();
		if($val && !$(".rp-id-class").val())
		{
			$(".tbody-item").load("/tablet/customer/load_rp/"+ ($val != '' ? $val : 0), function()
	    	{
	    		action_compute_maximum_amount();
	    	})
	    	action_load_open_transaction($val);
		}
	}

	function event_click_check_all()
	{
		$("body").on("click",".check-all", function()
		{
			if($(this).prop("checked"))
			{
				$(this).parents(".digima-table").find(".line-checked").prop('checked', true);
				$all = $(this).parents(".digima-table").find(".line-checked");
				$all.each(function()
				{
					$(this).prop("checked", true);
					action_change_input_value($(this));
				});
			}
			else
			{
				$(this).parents(".digima-table").find(".line-checked").prop('checked', false);	
				$all = $(this).parents(".digima-table").find(".line-checked");
				$all.each(function()
				{
					$(this).prop("checked", false);
					action_change_input_value($(this));
				});			
			}
		});
	}
	function remove_cm_applied()
	{
		$("body").on("click",".remove-cm", function()
		{
			$(this).parent().parent().remove();
			update_total_amount(formatFloat(action_total_amount_apply()) - formatFloat(action_cm_apply()));	
		});
	}
	function action_load_rp_onload()
	{
		$id = $(".drop-down-customer").val();
		$rp_id = $(".rp-id-class").val();
		if($id)
		{
			if(!$rp_id)
			{
				$(".tbody-item").load("/member/transaction/receive_payment/load-customer-receive-payment?cust="+$id+"&rp_id=" , function()
		    	{
		    		action_compute_maximum_amount(function(){});
		    	});
			}
	    	action_load_open_transaction($id);
		}
	}
	this.action_initialize_load = function()
	{
		action_initialize_load();
	}

	function action_initialize_load()
	{
		initialize_select_plugin();
		$(".datepicker").datepicker();
		$(".amount-payment").change();
	}

	function initialize_select_plugin()
	{
		$(".drop-down-customer").globalDropList(
		{
		    link 		: '/member/customer/modalcreatecustomer',
		    link_size 	: 'lg',
		    width 		: "100%",
		    placeholder : 'Customer',
		    onChangeValue: function()
		    {
		    	customer_id = $(this).val();
		    	var rp_id = $(".rp-id-class").val();
		    	$('.salesrep_id').val($(this).find('option:selected').attr('salesrep_id'));
		    	$('.salesrep').val($(this).find('option:selected').attr('salesrep'));
		    	var check = $(".for-tablet-only").html();
		    	if(check == null || check == "")
		    	{
			    	$(".tbody-item").load("/member/transaction/receive_payment/load-customer-receive-payment?rp_id="+rp_id+"&cust="+ (customer_id != '' ? customer_id : 0), function()
			    	{
			    		action_compute_maximum_amount(function(){});
			    	})		    		
		    	}
		    	else
	    		{
			    	$(".tbody-item").load("/tablet/customer/load_rp/"+ (customer_id != '' ? customer_id : 0), function()
			    	{
			    		action_compute_maximum_amount(function(){});
			    	})		    		
	    		}
	    		action_load_open_transaction(customer_id);

				$(".popup-link-open-transaction").attr('link','/member/transaction/receive_payment/load-credit?c='+customer_id);
				$(".count-open-transaction").html($(this).find('option:selected').attr('ctr-rp'));

				action_load_reference_number(customer_id);
		    }
		});

		//edrich
		$(".drop-down-payment").globalDropList(
		{
		    link 		: '/member/maintenance/payment_method/add',
		    link_size 	: 'sm',
		    width 		: "100%",
		    placeholder : 'Payment Method',
		    onChangeValue: function()
		    {

				payment_method_value = $(this).val();
		    	payment_method_text = $(this).find('option:selected').text();
		    	
		    	if(customer_id)
		    	{
		    		action_load_reference_number(customer_id);
		    	}
		    	else
		    	{
		    		console.log('select customer');
		    	}
		    }

		});

		$(".drop-down-coa").globalDropList(
		{
		    link 		: '/member/accounting/chart_of_account/popup/add',
		    link_size 	: 'md',
		    width 		: "100%",
		    placeholder : 'Account'
		});
	}

	function action_load_reference_number($customer_id)
	{
		var if_cheque = "Cheque";
		if(payment_method_text)
		{
			if(payment_method_text.trim() == if_cheque.trim())
			{
				$(".rcvpymnt-refno").val($(".drop-down-customer").find('option:selected').attr('ctr-ref-num')); 
			
			}
			else
			{
				$(".rcvpymnt-refno").val(null); 
			}
		}
	}

	function event_load_open_transaction()
	{
		if($('.c-id').val())
		{
			$this = $(".drop-down-customer");

			$(".popup-link-open-transaction").attr('link','/member/transaction/receive_payment/load-credit?c='+$('.c-id').val());
			$(".count-open-transaction").html($this.find('option:selected').attr('ctr-rp'));
			action_load_open_transaction($('.c-id').val());
		}
	} 

	function action_load_open_transaction($customer_id)
	{
		if($customer_id)
		{
			// $.ajax({
			// 	url : '/member/transaction/receive_payment/count-transaction',
			// 	type : 'get',
			// 	data : {customer_id : $customer_id},
			// 	success : function(data)
			// 	{
					$(".open-transaction").slideDown();
			// 	}
			// });
		}
		else
		{
			$(".open-transaction").slideUp();
		}
	}

	/* CHECK BOX FOR LINE ITEM */
	function event_line_check_change()
	{
		$(document).on("change", ".line-checked", function()
		{
			action_change_input_value($(this));
		});
	}
	function action_change_input_value($this)
	{
		if($this.is(":checked"))
		{
			$this.prev().val(1);
			var balance = $this.parents("tr").find(".balance-due").val();
			if(!formatFloat($this.parents("tr").find(".amount-payment").val()) > 0)
			{
				$this.parents("tr").find(".amount-payment").val(balance).change();
			}
		}
		else
		{
			$this.prev().val(0);
			var balance = $this.parents("tr").find(".balance-due").val(); 
			if(formatFloat($this.parents("tr").find(".amount-payment").val()) > 0)
			$this.parents("tr").find(".amount-payment").val('').change();
		}
	}

	function action_compute_maximum_amount(callback = '')
	{
		balance_payable = 0;
		$(".balance-due").each(function()
		{
			maximum_payment += formatFloat($(this).val());
			var $this = $(this).parent().parent('tr');
			var is_checked = $this.find('.line-checked').prop("checked");
			if(is_checked)
			{
				balance_payable += formatFloat($(this).val());
			}
		});
		// callback(1);
	}

	function event_received_amount_change()
	{
		$(document).on("change", ".amount-received", function()
		{
			action_compute_maximum_amount();
			$(this).val(formatMoney_2($(this).val()));

			var amount_receive = formatFloat($(this).val());
			var amount_applied = formatFloat(action_total_amount_apply());

			if( amount_receive > amount_applied)
			{
				action_update_credit_amount(amount_receive - amount_applied);
			}
			action_update_credit_amount(amount_applied - balance_payable)
		})

		$(document).on("keydown", ".amount-received", function()
		{
			is_amount_receive_modified = true;
		})
	}

	function action_update_apply_amount($amount)
	{
		$(".amount-to-apply").val($amount);
		$(".amount-apply").html("PHP "+formatMoney_2($amount))

		update_total_amount(formatFloat($amount) - formatFloat(action_cm_apply()));
	}

	function action_update_credit_amount($amount)
	{
		$(".amount-to-credit").val($amount);
		$(".amount-credit").html("PHP "+formatMoney_2($amount))
	}
	function update_total_amount($amount)
	{
		$(".total-amount-paid").val($amount);
		$(".total-amount-paid-span").html("PHP "+$amount);
	}
	function event_cm_applied_change()
	{
		$("body").on("change",".cm-amount-applied", function()
		{
			update_total_amount(formatFloat(action_total_amount_apply()) - formatFloat(action_cm_apply()));	
		});
	}
	function event_payment_amount_change()
	{
		$(document).on("change",".amount-payment", function(e)
		{
			$(this).val(formatFloat($(this).val()) == 0 ? '' : formatMoney($(this).val()));

			!is_amount_receive_modified ? $(".amount-received").val(action_total_amount_apply()).change() : $(".amount-received").change();
			action_update_apply_amount(action_total_amount_apply());
			// check - uncheck checkbox
			if(formatFloat($(this).val()) > 0)
			{
				$(this).parents("tr").find(".line-checked").prop("checked", true).change();
			}
			else
			{
				$(this).parents("tr").find(".line-checked").prop("checked", false).change();
			}

			// validation for exceeding balace
			if(formatFloat($(this).val()) > formatFloat($(this).parents("tr").find(".balance-due").val()) )
			{
				this.setCustomValidity("You may not pay more than the balance due");
    			return false;
			}
			else
			{
				this.setCustomValidity("");
    			return true;
			}
		})
	}

	function action_cm_apply()
	{
		var line_cm_applied = 0;
		$(".cm-amount-applied").each(function()
		{
			line_cm_applied += formatFloat($(this).val());
		})
		return formatMoney(line_cm_applied);
	}
	function action_total_amount_apply()
	{
		var line_payment_amount = 0;
		$(".amount-payment").each(function()
		{
			line_payment_amount += formatFloat($(this).val());
		})
		return formatMoney(line_payment_amount);
	}

	function formatFloat($this)
	{
		return Number($this.toString().replace(/[^0-9\.]+/g,""));
	}

	function formatMoney_2($this)
	{
		var n = formatFloat($this), 
	    c = isNaN(c = Math.abs(c)) ? 2 : c, 
	    d = d == undefined ? "." : d, 
	    t = t == undefined ? "," : t, 
	    s = n < 0 ? "-" : "", 
	    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
	    j = (j = i.length) > 3 ? j % 3 : 0;
	   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	}
	function formatMoney($this)
	{
		var n = formatFloat($this), 
	    c = isNaN(c = Math.abs(c)) ? 2 : c, 
	    d = d == undefined ? "." : d, 
	    t = t == undefined ? "," : t, 
	    s = n < 0 ? "-" : "", 
	    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
	    j = (j = i.length) > 3 ? j % 3 : 0;
	   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	}

	function event_button_action_click()
	{
		$(document).on("click","button[type='submit']", function()
		{
			$(".button-action").val($(this).attr("data-action"));
		})
	}
	function load_applied_transaction()
	{
		$('.load-applied-credit').load('/member/transaction/receive_payment/load-applied-transaction', function()
		{
			update_total_amount(formatFloat(action_total_amount_apply()) - formatFloat(action_cm_apply()));
		});
	}
	this.load_applied_transaction = function()
	{
		load_applied_transaction();
	}
}

function success_receive_payment(data)
{
	if(data.status == 'success')
	{
		toastr.success(data.status_message);
		location.href = data.status_redirect;
	}
}
/*function reload_receive_payment(data)
{
	if(data.status == 'reload')
	{
		location.href = data.status_redirect;
	}
}*/
function success_apply_transaction(data)
{
	if(data.status == 'success')
	{
		data.element.modal("toggle");
		receive_payment.load_applied_transaction();
	}
	
}
function submit_done(data)
{
	if(data.status == "success" || data.response_status == "success")
	{
		if(data.type == "payment_method")
		{
			$(".drop-down-payment").load("/member/maintenance/load_payment_method", function()
			{
				$(this).globalDropList("reload");
				$(this).val(data.payment_method_id).change();
			});
			data.element.modal("toggle");
		}
		else if(data.type == "account")
		{
			$(".drop-down-coa").load("/member/accounting/load_coa?filter[]=Bank", function()
			{
				$(this).globalDropList("reload");
				$(this).val(data.id).change();
			});
			data.element.modal("toggle");
		}
		else if(data.redirect)
        {
        	toastr.success(data.message);
        	location.href = data.redirect;
    	}
    	else
    	{
    		$(".rcvpymnt-container").load(data.url+" .rcvpymnt-container .rcvpymnt-load-data", function()
			{
				receive_payment.action_initialize_load();
				toastr.success(data.message);
			});
    	}
	}
}