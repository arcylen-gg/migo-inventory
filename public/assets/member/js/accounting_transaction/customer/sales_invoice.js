var sales_invoice = new sales_invoice();
var global_tr_html = $(".div-script tbody").html();
var global_tr_html_pm = $(".div-script-pm tbody").html();
var item_selected = ''; 
var bin_selected = "";
var customer_id = null;
function sales_invoice()
{
	init();

	function init()
	{
		action_load_initialize_select();
		event_click_last_row();
		event_remove_tr();
		action_compute();
		event_compute_class_change();
		action_reassign_number();
		action_date_picker();
		event_load_open_transaction();
		event_keypress_enter();
		event_taxable_check_change();
		event_keypress_enter_OFF_barcode();
		transaction_status();
		click_select_all();
		event_change_qty();

		number_input_change();
		event_click_last_pm_row();
		event_remove_tr_pm();
		action_compute_pm();
		action_reassign_number_pm();
	}
	
	function number_input_change()
	{
		$("body").on("change",".number-input-pm", function()
		{
			action_compute_pm();
		});
	}
	function action_compute_pm()
	{
		var $total = 0;
		$(".tr-pm-row").each(function()
		{
			$total += action_return_to_number($(this).find(".number-input-pm").val());
			$(this).find(".number-input-pm").val(action_add_comma(parseFloat(action_return_to_number($(this).find(".number-input-pm").val())).toFixed(2)));
		});
		$(".total-amount-pm").html(action_add_comma($total.toFixed(2)));
	}
	function event_click_last_pm_row()
	{
		$(document).on("click", "tbody.draggable-pm tr:last td:not(.remove-tr-pm)", function(){
			event_click_last_row_op_pm();
			action_compute_pm();
		});
	}

	function event_remove_tr_pm()
	{
		$(document).on("click", ".remove-tr-pm", function(e){
			var len = $(".draggable-pm .remove-tr-pm").length;
			if($(".draggable-pm .remove-tr-pm").length > 1)
			{
				$(this).parent().remove();
				action_reassign_number_pm();
				action_compute_pm();
			}
			else
			{
				// console.log("success");
			}
		});
	}


	function event_click_last_row_op_pm()
	{
		$("tbody.draggable-pm").append(global_tr_html_pm);
		action_reassign_number_pm();
		action_load_initialize_select();
	}

	function action_reassign_number_pm()
	{
		var num = 1;
		$(".td-pm-id").each(function(){
			$(this).html(num);
			num++;
		});
	}
	function action_load_reference_number($customer_id, $data_value, $text_value)
	{
		if($data_value == 48 || $text_value == "Cheque")
		{
			$(".rcvpymnt-refno").val($(".droplist-customer").find('option:selected').attr('ctr-si-ref-num')); 
		}
		else
		{
			$(".rcvpymnt-refno").val(null); 
		}
	}
	function event_change_qty()
	{
		$('body').on("change",".change-qty", function()
		{
			if($(".range-discount").val())
			{
		    	var parent = $(this).closest('.tr-draggable');
		    	var selected_item = parent.find(".select-item");
		    	var range_disc = selected_item.find("option:selected").attr("sales-range-price");
		    	var arr = [];
		    	if(range_disc)
		    	{
		    		arr = range_disc.split(",");
		    	}
		    	var current_qty = parseFloat($(this).val());

		    	$orig_amount = selected_item.find("option:selected").attr("price");
		    	$new_amount = $orig_amount;
		    	if(arr.length > 0)
		    	{
			    	for (var i = 0, len = arr.length; i < len; i++) 
			    	{
						var qty = arr[i].split("-")[0];
						var amount = arr[i].split("-")[1];
			    		$next_qty = typeof arr[i+1] !== 'undefined' ? arr[i+1].split("-")[0] : 0;
			    		if(qty && amount)
			    		{
				    		if($next_qty != '' && $next_qty != 0)
				    		{
				    			if(current_qty >= qty && current_qty <= $next_qty)
				    			{
				    				$new_amount = amount;
				    			}
				    		}
				    		else
				    		{
					    		if(!$next_qty) 
					    		{
					    			if(current_qty >= qty)
					    			{
					    				$new_amount = amount;
					    			}
			    				}	
				    		}
			    		}
					}
					parent.find(".txt-rate").val(action_add_comma((parseFloat($new_amount)).toFixed(2)));
				}
				action_compute();
			}
		});
	}
	function transaction_status()
	{
		var status = $(".transaction-status").val();
		if(status == 'posted')
		{
			$('input[type="text"], input[type="checkbox"], button, select, textarea').prop("disabled", true);
			$('.open-transaction').addClass('hidden');
		}
	}
	/* CHECK BOX FOR TAXABLE */
	function event_taxable_check_change()
	{
		$(".taxable-check").unbind("change");
		$(".taxable-check").bind("change", function()
		{
			action_change_input_value($(this));
		});
	}
	function click_select_all()
	{
		$("body").on("click",'.select-all-tax-check', function ()
		{
			action_compute();
		})
	}
	function check_change_input_value()
	{
		$(".taxable-check").each( function()
		{
			action_change_input_value($(this));
		})
	}
	
	function action_change_input_value($this)
	{
		if($this.is(":checked"))
		{
			$this.prev().val(1);
		}
		else
		{
			$this.prev().val(0);
		}
	} 
	function event_keypress_enter_OFF_barcode()
	{
		$("body").on("keypress",'.txt-qty', function (e)
		{
		    if (e.which == 13)
		    {
				$txtparent = $(this).parent().parent();
		    	$next_parent = $txtparent.next('.tr-draggable');
		    	var inputs = $next_parent.find('input:visible,textarea:visible,select:visible');
                inputs.eq( inputs.index(this)+ 1 ).focus();
		    }
		});	
	}

	function event_keypress_enter()
	{
		$("body").on("click",'td.item-select-td .input-group', function()
		{
			$parent = $(this).closest(".tr-draggable");
			$parent.find('.item-textbox').removeClass("hidden");
			$parent.find('.item-textbox').focus();
			$(this).addClass("hidden");
		});
		$("body").on("keypress",'.txt-qty', function (e)
		{
		    if (e.which == 13)
		    {
		    	$txtparent = $(this).parent().parent();
		    	console.log($txtparent);
		    	$next_parent = $txtparent.next('.tr-draggable');
		    	$next_parent.find('.item-textbox').removeClass("hidden");
		    	$next_parent.find('td.item-select-td .input-group').addClass("hidden");
		    	$next_parent.find('.item-textbox').focus();
		    	event_click_last_row_op();
		    }
		});		
	}
	
	function event_load_open_transaction()
	{
		if($('.c-id').val())
		{
			action_load_open_transaction($('.c-id').val());
			load_applied_transaction();
		}
	} 
	function action_compute()
	{
		var subtotal = 0;
		var total_taxable = 0;
		var total_not_taxable = 0;
		var vat_ex_sub = 0;

		$(".tr-draggable").each(function()
		{
			/* GET ALL DATA */
			var qty               = $(this).find(".txt-qty").val();
			var rate              = action_return_to_number($(this).find(".txt-rate").val());
			var discount_string   = $(this).find(".txt-discount").val().toString();
			var amount            = $(this).find(".txt-amount");
			var taxable           = $(this).find(".taxable-check");
			var select_all_tax	  = $(this).find(".taxable-input").val();

			var discount_amount = 0;
			if(discount_string.indexOf('%') > 0)
			{
				discount_amount = (parseFloat(discount_string.substring(0, discount_string.indexOf('%'))) / 100);
				discount_amount = parseFloat((rate * qty) * discount_amount);
			}
			else
			{
				discount_amount = parseFloat(discount_string);
			}

			if(!qty)
			{
				qty = 1;
			}

			/* RETURN TO NUMBER IF THERE IS COMMA */
			qty 		= action_return_to_number(qty);
			rate 		= action_return_to_number(rate);
			discount_amount 	= action_return_to_number(discount_amount);

			var total_per_tr = ((qty * rate) - discount_amount).toFixed(2);

			/* action_compute SUB TOTAL PER LINE */
			subtotal += parseFloat(total_per_tr);


			var amount_val = amount.val();

			if(amount_val != '' && amount_val != null && total_per_tr == '') //IF QUANTITY, RATE IS [NOT EMPTY]
			{
				var sub = parseFloat(action_return_to_number(amount_val));
				if(isNaN(sub))
				{
					sub = 0;
				}
				subtotal += sub;
				total_per_tr = sub;
				amount.val(action_add_comma(sub));
			}
			else //IF QUANTITY, RATE IS [EMPTY]
			{
				amount.val(action_add_comma(total_per_tr));
			}
			
			/*CHECK IF TAXABLE*/	
			if(taxable.is(':checked') || select_all_tax == 1)
			{
				console.log('safg');
				total_taxable += parseFloat(total_per_tr);
				console.log(total_taxable + 'total_taxable');
			}
			else
			{
				total_not_taxable += parseFloat(total_per_tr);
				console.log(total_not_taxable + ' total_not_taxable');
			}
			$(this).find(".txt-rate").val(action_add_comma(rate.toFixed(2)));
			
		});

		/* action_compute EWT */
		var ewt_value 			= $(".ewt-value").val();
		ewt_value = parseFloat(ewt_value) * subtotal;

		/* action_compute TAX */
		var tax   = 0;
		var no_tax   = 0;
		var total_tax   = 0;
		tax = (total_taxable * 0.12);
		no_tax = 0;
		total_tax = tax + no_tax;

		/*Vatable Sales*/
		var vatable_sales = 0;
		vatable_sales = subtotal;

		/* DISCOUNT */
		var discount_selection 	= $(".discount_selection").val();
		var discount_txt 		= $(".discount_txt").val();
		var tax_selection 		= $(".tax_selection").val();
		var taxable_discount 	= 0;
		if(discount_txt == "" || discount_txt == null)
		{
			discount_txt = 0;
		}

		discount_total = discount_txt;

		if(discount_selection == 'percent')
		{
			console.log(subtotal +' '+ total_tax+" "+ewt_value+" "+discount_txt+'000000');
			discount_total = ((subtotal + total_tax) - ewt_value) * (discount_txt / 100);
			taxable_discount = total_taxable * (discount_txt / 100);
		}

		discount_total = parseFloat(discount_total);
		console.log(discount_total + 'discount');

		/* action_compute TOTAL */
		var total = 0;
		total     = subtotal + total_tax - discount_total - ewt_value;

		//total += total_tax + vatable_sales ;
		$(".vatable-sales").html(action_add_comma(vatable_sales.toFixed(2)));
		$(".vatable-sales-input").val(action_add_comma(vatable_sales.toFixed(2)));
		$(".vat-amount").html(action_add_comma(total_tax.toFixed(2)));
		$(".vat-amount-input").val(action_add_comma(total_tax.toFixed(2)));

		$(".sub-total").html(action_add_comma(subtotal.toFixed(2)));
		$(".subtotal-amount-input").val(action_add_comma(subtotal.toFixed(2)));
		$(".ewt-total").html(action_add_comma(ewt_value.toFixed(2)));
		$(".discount-total").html(action_add_comma(discount_total.toFixed(2)));
		//$(".tax-total").html(action_add_comma(tax.toFixed(2)));
		$(".total-amount").html(action_add_comma(total.toFixed(2)));
		$(".total-amount-input").val(total.toFixed(2));
		$(".balance-due").html(action_add_comma((total.toFixed(2) - action_return_to_number($(".balance-due-input").val())).toFixed(2)));
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

	function event_compute_class_change()
	{
		$(document).on("change",".compute", function()
		{
			action_compute();
		});
	}
	function convert_to_dec($string)
	{
		$amount = action_return_to_number($string);
		return action_add_comma($amount.toFixed(2));
	}
	function action_load_initialize_select()
	{
		$('.droplist-customer').globalDropList(
		{
			width : "100%",
    		placeholder : "Select Customer...",
			link : "/member/customer/modalcreatecustomer",
			onChangeValue: function()
			{				
				customer_id = $(this).val();
				$(".customer-email").val($(this).find("option:selected").attr("email"));
				$(".customer-billing-address").val($(this).find("option:selected").attr("billing-address"));
				action_load_open_transaction($(this).val());

				$(".popup-link-open-transaction").attr('link','/member/transaction/sales_invoice/load-transaction?c='+$(this).val());
				$(".count-open-transaction").html($(this).find("option:selected").attr("ctr-si"));
				action_load_reference_number(customer_id);
			}
		});

	    $('.droplist-item').globalDropList(
        {
            link : "/member/item/v2/add",
            width : "100%",
            maxHeight: "309px",
            onCreateNew : function()
            {
            	item_selected = $(this);
            },
            onChangeValue : function()
            {
            	action_load_item_info($(this));
            }
        });

	    $(".draggable .tr-draggable:last td select.select-item").globalDropList(
        {
            link : "/member/item/v2/add",
            width : "100%",
            maxHeight: "309px",
            onCreateNew : function()
            {
            	item_selected = $(this);
            },
            onChangeValue : function()
            {
            	action_load_item_info($(this));
            }
        });

        $('.droplist-um').globalDropList(
    	{
    		hasPopup: "false",
    		width : "100%",
    		placeholder : "um..",
    		onChangeValue: function()
    		{
    			action_load_unit_measurement($(this));
    		}

    	});

        $('.droplist-sales-rep').globalDropList(
    	{
    		hasPopup: "false",
    		width : "100%",
    		placeholder : "Sales representative"

    	});

        $(".draggable .tr-draggable:last td select.select-um").globalDropList(
        {
        	hasPopup: "false",
    		width : "100%",
    		placeholder : "um..",
    		onChangeValue: function()
    		{  
    			action_load_unit_measurement($(this));
    		}

        });
        $('.droplist-sub-warehouse').globalDropList({
			link : "/member/item/v2/warehouse/add?bin=true",
            width : "100%",
            placeholder : 'Search Location...',
            onCreateNew : function()
            {
            	bin_selected = $(this);
            },
            onChangeValue : function()
            {
            	
            }
		});
		$(".draggable .tr-draggable:last td select.select-sub-warehouse").globalDropList(
        {
            link : "/member/item/v2/warehouse/add?bin=true",
            width : "100%",
            placeholder : 'Search Location...',
            onCreateNew : function()
            {
            	bin_selected = $(this);
            },
            onChangeValue : function()
            {
            	
            }
        });

        $(".drop-down-payment").globalDropList(
		{
		    link 		: '/member/maintenance/payment_method/add',
		    link_size 	: 'sm',
		    width 		: "100%",
		    placeholder : 'Payment Method',
		    onChangeValue: function()
		    {
		    	data_value = $(this).val();
		    	text_value = $(this).find('option:selected').text();

		    	// if(customer_id)
		    	// {
		    		// action_load_reference_number(customer_id, data_value, text_value);
		    	// }
		    	// else
		    	// {
		    	// 	alert('select customer');
		    	// }
		    }

		});
        $(".draggable-pm .tr-pm-row:last td select.select-pm").globalDropList(
		{
		    link 		: '/member/maintenance/payment_method/add',
		    link_size 	: 'sm',
		    width 		: "100%",
		    placeholder : 'Payment Method',
		    onChangeValue: function()
		    {
		    	data_value = $(this).val();
		    	text_value = $(this).find('option:selected').text();

		    	// if(customer_id)
		    	// {
		    		// action_load_reference_number(customer_id, data_value, text_value);
		    	// }
		    	// else
		    	// {
		    	// 	alert('select customer');
		    	// }
		    }

		});
	}
	function action_load_open_transaction($customer_id)
	{
		if($customer_id)
		{
			// $.ajax({
			// 	url : '/member/transaction/sales_invoice/count-transaction',
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
	function event_remove_tr()
	{
		$(document).on("click", ".remove-tr", function(e){
			var len = $(".tbody-item .remove-tr").length;
			if($(".tbody-item .remove-tr").length > 1)
			{
				$(this).parent().remove();
				action_reassign_number();
				action_compute();

			}
			else
			{
				console.log("success");
			}
		});
	}


	function action_load_unit_measurement($this)
	{
		$parent = $this.closest(".tr-draggable");
		$item   = $this.closest(".tr-draggable").find(".select-item");

		$um_qty = parseFloat($this.find("option:selected").attr("qty") || 1);
		$sales  = parseFloat($item.find("option:selected").attr("price"));
		console.log($sales);
		$qty    = parseFloat($parent.find(".txt-qty").val());

		$parent.find(".txt-rate").val($um_qty * $sales).change();

    	action_compute();
	}
	function event_click_last_row()
	{
		$(document).on("click", "tbody.draggable tr:last td:not(.remove-tr)", function(){
			event_click_last_row_op();
		});
	}
   

	function event_click_last_row_op()
	{
		$("tbody.draggable").append(global_tr_html);
		action_reassign_number();
		action_load_initialize_select();
		action_date_picker();
    	action_compute();
		event_taxable_check_change();
	}

	function action_load_item_info($this)
	{
		$parent = $this.closest(".tr-draggable");
		// $parent.find(".txt-desc").html($this.find("option:selected").attr("sales-info")).change();
		$parent.find(".txt-rate").val($this.find("option:selected").attr("price")).change();
		$parent.find(".txt-qty").val(1).change();
		if($this.find("option:selected").attr("has-um") && $this.find("option:selected").attr("has-um") != 0)
		{
			$parent.find(".txt-qty").attr("disabled",true);
			$parent.find(".select-um").load('/member/item/load_one_um/' +$this.find("option:selected").attr("has-um"), function()
			{
				$parent.find(".txt-qty").removeAttr("disabled");
				$(this).globalDropList("reload").globalDropList("enabled");
				$parent.find('.txt-qty').focus();
				$(this).val($(this).find("option:first").val()).change();
			})
		}
		else
		{
			$parent.find(".select-um").html('<option class="hidden" value=""></option>').globalDropList("reload").globalDropList("disabled").globalDropList("clear");
		}
    	action_compute();
	}
	function action_date_picker()
	{
		$(".draggable .for-datepicker").datepicker({ dateFormat: 'mm/dd/yy', });
	}

	function load_applied_transaction()
	{
		$(".loading-tbody").removeClass("hidden");
		$('.applied-transaction-list').load('/member/transaction/sales_invoice/load-applied-transaction', function()
		{
			console.log("success");
			action_load_initialize_select();
			action_compute();
			event_taxable_check_change();
			action_reassign_number();
			$('.remarks-si').html($('.si-remarks').val());
			
			$(".loading-tbody").addClass("hidden");
		});
		$('.applied-pm').load('/member/transaction/sales_invoice/applied-pm', function()
		{
			action_load_initialize_select();
			number_input_change();
			event_remove_tr_pm();
			action_compute_pm();
			action_reassign_number_pm();
		});
	}
	function action_reassign_number()
	{
		var num = 1;
		$(".invoice-number-td").each(function(){
			$(this).html(num);
			num++;
		});
	}

	this.load_applied_transaction = function()
	{
		load_applied_transaction();
	}
}
function success_update_customer(data)
{
	data.element.modal("hide");
	$(".droplist-customer").load("/member/customer/load_customer", function()
    {                
        $(".droplist-customer").globalDropList("reload");
        $(".droplist-customer").val(data.id).change();
    });
}
/* AFTER ADDING AN  ITEM */
function success_item(data)
{
    item_selected.load("/member/item/load_item_category", function()
    {
        $(this).globalDropList("reload");
		$(this).val(data.item_id).change();
    });
    data.element.modal("hide");
}
function success_invoice(data)
{
	if(data.status == 'success')
	{
		toastr.success(data.status_message);
		location.href = data.status_redirect;
	}
}
function success_apply_transaction(data)
{
	if(data.status == 'success')
	{
		data.element.modal("toggle");
		sales_invoice.load_applied_transaction();
	}
}
/*AFTER ADDING BIN*/
function success_warehouse(data)
{
	bin_selected.load("/member/item/v2/warehouse/load-bin-warehouse?warehouse_id="+data.warehouse_id, function()
	{
		$(this).globalDropList('reload');
		$(this).val(data.warehouse_id).change();

		data.element.modal("hide");
	});
}