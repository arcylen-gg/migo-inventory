var customer_wis_create = new customer_wis_create()
var load_item = null;
var item_search_delay_timer;
var settings_delay_timer;
var keysearch = {};
var global_tr_html = $(".div-script tbody").html();
var offset_item_tr = $(".div-item-offset tbody").html();

var success_audio = new Audio('/assets/sounds/success.mp3');
var error_audio = new Audio('/assets/sounds/error.mp3');

var $remaining_budget = 0;
var bin_selected ="";
function customer_wis_create()
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
		// action_load_item_table();
		event_search_item();
		event_click_search_result();
		event_remote_item_from_cart();
		event_change_global_discount();
		event_change_quantity();
		event_submit_form();
		action_initialize_select();
		action_lastclick_row();
		event_remove_tr();
		event_compute_class_change();
		event_button_action_click();
		action_date_picker();
		action_compute();
		event_load_transaction();
		action_reassign_number();
		event_load_customer_transaction();
		event_keypress_enter();
		event_keypress_enter_OFF_barcode();
		remove_item_offset();
		compute_monthly_budget();
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
	function compute_monthly_budget()
	{
		$("body").on("change",".change-val", function()
		{
			$total = action_return_to_number($(".total-amount-input").val());
			$montly_budget =action_return_to_number( $(".input-monthly-budget").val());
			$current_mo_budget = action_return_to_number($(".current-budget-month-amount").val());
			$prev_mo_budget = action_return_to_number($(".prev-budget-month-amount").val());
			$adj_mo_budget = action_return_to_number($(".adj-budget-month-amount").val());
			$item_less = action_return_to_number($(".item-less-amount").val());
			$total_adj = action_return_to_number($(".total-adj-amount").val());

			action_compute();
			// $(".current-budget-month-amount").val(action_add_comma(($montly_budget - $total).toFixed(2)));
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
		    	$next_parent = $txtparent.next('.tr-draggable');
		    	$next_parent.find('.item-textbox').removeClass("hidden");
		    	$next_parent.find('td.item-select-td .input-group').addClass("hidden");
		    	$next_parent.find('.item-textbox').focus();
		    	action_lastclick_row_op();
		    	action_compute();
		    }
		});		
	}
	function action_date_picker()
	{
		$(".draggable .datepicker").datepicker({ dateFormat: 'mm-dd-yy', });
	}

	function event_button_action_click()
	{
		$(document).on("click","button[type='submit']", function()
		{
			$(".button-action").val($(this).attr("code"));
		})
		action_compute();
	}

	function action_reassign_number()
	{
		var num = 1;
		$(".invoice-number-td").each(function(){
			$(this).html(num);
			num++;
		});
	}
	function event_remove_tr()
	{		
		$(document).on("click", ".remove-tr", function(e)
		{
			var len = $(".tbody-item .remove-tr").length;
			if($(".tbody-item .remove-tr").length > 1)
			{
				$(this).parent().remove();
			}
		});
	}
	function event_load_customer_transaction()
	{
		$customer_id = $('.droplist-customer').val();
		if($customer_id)
		{
			action_load_open_transaction($customer_id);
			load_applied_transaction();
			event_change_customer($('.droplist-customer'));
		}
	}
	function event_change_customer($thisoption)
	{
		// MONTHLY BUDGET
		$mb = action_return_to_number($thisoption.find("option:selected").attr("monthly-budget"));
		$amb = action_return_to_number($thisoption.find("option:selected").attr("adjusted-monthly-budget"));
		$(".input-monthly-budget").val(action_add_comma($mb.toFixed(2))).change();
		$(".input-adjusted-budget-amount").val(action_add_comma($amb.toFixed(2))).change();
		$getprev = action_return_to_number($thisoption.find("option:selected").attr("previous-budget"));
		$getprevmo = $thisoption.find("option:selected").attr("previous-month");
		if($getprev > 0) // POSITIVE
		{
			$html_prev = "Previous Under Budget for the Month of "+$getprevmo;
		} 
		else
		{
			$html_prev = "Previous Over Budget for the Month of "+$getprevmo;					 
		}
		$(".display-previous-budget-text").html($html_prev);
		$(".input-previous-budget-text").val($html_prev);
		$(".input-previous-budget-amount").val(action_add_comma($getprev.toFixed(2))).change();
		action_compute();

		action_load_open_transaction($thisoption.val());	
		$(".popup-link-open-transaction").attr('link','/member/transaction/wis/load-transaction?c='+$thisoption.val());
		$(".count-open-transaction").html($thisoption.find("option:selected").attr("ctr-wis"));	
	}
	function action_initialize_select()
	{
		$('.droplist-customer').globalDropList(
		{
			width : "100%",
    		placeholder : "Select Customer...",
			link : "/member/customer/modalcreatecustomer",
			onChangeValue: function()
			{
				$(".customer-email").val($(this).find("option:selected").attr("email"));
				$(".customer-billing-address").val($(this).find("option:selected").attr("billing-address"));

				event_change_customer($(this));
			}
		});

		$('.droplist-item').globalDropList({
			link : "/member/item/v2/add",
            width : "100%",
            placeholder : 'Search Item...',
            onCreateNew : function()
            {
            	// item_selected = $(this);
            	// console.log($(this));
            },
            onChangeValue : function()
            {
            	if($(this).val() != '')
            	{
            		action_load_item_info($(this));
            	}
            }
		});
		$(".select-item-offset").globalDropList({
			link : "/member/item/v2/add",
            width : "100%",
            placeholder : 'Search Item...',
            onCreateNew : function()
            {
            	// item_selected = $(this);
            	// console.log($(this));
            },
            onChangeValue : function()
            {
            	if($(this).val() != '')
            	{
            		item_offset_select($(this));
            	}
            }
		});
		$(".select-truck").globalDropList(
	    { 
	      hasPopup                : "true",      
	      link                    : "/member/pis/truck_list/add",
	      link_size               : "md",
	      width                   : "100%",
	      placeholder             : "Search truck...",
	      no_result_message       : "No result found!"
	    });
		$(".draggable .tr-draggable:last td select.select-item").globalDropList(
        {
            link : "/member/item/add",
            width : "100%",
            placeholder : 'Search Item...',
            onCreateNew : function()
            {
            	// item_selected = $(this);
            },
            onChangeValue : function()
            {
            	if($(this).val() != '')
            	{
            		action_load_item_info($(this));
            	}
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
			link : "/member/item/v2/warehouse/add",
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
            link : "/member/item/v2/warehouse/add",
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
	}
	function action_load_unit_measurement($this)
	{
		$parent = $this.closest(".tr-draggable");
		$item   = $this.closest(".tr-draggable").find(".select-item");

		$um_qty = parseFloat($this.find("option:selected").attr("qty") || 1);
		$sales  = parseFloat($item.find("option:selected").attr("price"));
		$qty    = parseFloat($parent.find(".txt-qty").val());

		$parent.find(".txt-rate").val($um_qty * $sales).change();

    	action_compute();
	}

	function event_compute_class_change()
	{
		$(document).on("change",".compute", function()
		{
			action_compute();
		});
	}
	function action_load_open_transaction($customer_id)
	{
		if($customer_id)
		{
			// $.ajax({
			// 	url : '/member/transaction/wis/count-transaction',
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
	function action_load_item_info($this)
	{
		// $parent = $this.closest(".tr-draggable");
		// $parent.find(".txt-desc").val($this.find("option:selected").attr("sales-info")).change();
		// $parent.find(".txt-remaining-qty").html($this.find("option:selected").attr("inventory-count") + " pc(s)").change();

		$parent = $this.closest(".tr-draggable");
		$parent.find(".txt-desc").html($this.find("option:selected").attr("sales-info")).change();
		$parent.find(".txt-rate").val($this.find("option:selected").attr("price")).change();
		$parent.find(".txt-qty").val(1).change();
		if($this.find("option:selected").attr("has-um") && $this.find("option:selected").attr("has-um") != 0) 
		{
			$parent.find(".txt-qty").attr("disabled",true);
			$parent.find(".select-um").load('/member/item/load_one_um/' +$this.find("option:selected").attr("has-um"), function()
			{
				$parent.find(".txt-qty").removeAttr("disabled");
				$(this).globalDropList("reload").globalDropList("enabled");
				$parent.find(".txt-qty").focus();
				$(this).val($(this).find("option:first").val()).change();
			})
		}
		else
		{
			$parent.find(".select-um").html('<option class="hidden" value=""></option>').globalDropList("reload").globalDropList("disabled").globalDropList("clear");
		}
    	action_compute();
	}
	function action_compute()
	{

		var subtotal = 0;
		var total_taxable = 0;


		$(".tr-draggable").each(function()
		{
			/* GET ALL DATA */
			var qty               = $(this).find(".txt-qty").val();
			var rate              = $(this).find(".txt-rate").val();
			var amount            = $(this).find(".txt-amount");

			if(!qty)
			{
				qty = 1;
			}

			/* RETURN TO NUMBER IF THERE IS COMMA */
			qty 		= action_return_to_number(qty);
			rate 		= action_return_to_number(rate);
			var total_per_tr = (qty * rate).toFixed(2);

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

			$(this).find(".txt-rate").val(action_add_comma(rate.toFixed(2)));
		});

		/* action_compute TOTAL */
		var total = 0;
		total     = subtotal;

		$(".total-amount").html(action_add_comma(total.toFixed(2)));
		$(".total-amount-input").val(total.toFixed(2));

		$monthly_budget = action_return_to_number($(".input-monthly-budget").val());
		$prev_budget = action_return_to_number($(".input-previous-budget-amount").val());
		
		$budget = total - $monthly_budget;
		$adjusted = $prev_budget - $budget;
		var d = new Date();
		if($budget > 0) // POSITIVE
		{
			$budget_text = "Over Budget for the Month of "+ getmonth(d.getMonth());
			$(".display-budget-text").html($budget_text);
			$(".input-budget-text").val($budget_text);
			$(".input-budget-amount").val(action_add_comma($budget.toFixed(2)));
		}
		else // NEGATIVE
		{
			$budget_text = "Under Budget for the Month of "+getmonth(d.getMonth());
			$(".display-budget-text").html($budget_text);
			$(".input-budget-text").val($budget_text);
			$(".input-budget-amount").val(action_add_comma($budget.toFixed(2)));

		}

		if($adjusted > 0) // POSITIVE
		{
			$adjusted_text = "Total Under Budget for the Month of "+getmonth(d.getMonth());
			$(".display-adjusted-budget-text").html($adjusted_text);
			$(".input-adjusted-budget-text").val($adjusted_text);
			$(".input-adjusted-budget-amount").val(action_add_comma($adjusted.toFixed(2)));

			$total_adjusted_text = "Total Adjusted Under Remaining Budget for the Month of "+ getmonth(d.getMonth());
			$(".display-total-adjusted-text").html($total_adjusted_text);
			$(".input-total-adjusted-text").html($total_adjusted_text);
			$(".input-total-adjusted-amount").val(action_add_comma($budget.toFixed(2)));
		}
		else // NEGATIVE
		{
			$adjusted_text = "Total Over Budget for the Month of "+getmonth(d.getMonth());	
			$(".display-adjusted-budget-text").html($adjusted_text);
			$(".input-adjusted-budget-text").val($adjusted_text);
			$(".input-adjusted-budget-amount").val(action_add_comma($adjusted.toFixed(2)));		

			$total_adjusted_text = "Total Adjusted Over Remaining Budget for the Month of "+ getmonth(d.getMonth());
			$(".display-total-adjusted-text").html($total_adjusted_text);
			$(".input-total-adjusted-text").val($total_adjusted_text);
			$(".input-total-adjusted-amount").val(action_add_comma($budget.toFixed(2)));
		}
	}
	function getmonth(num) 
	{
		var month = new Array();
		month[0] = "January";
		month[1] = "February";
		month[2] = "March";
		month[3] = "April";
		month[4] = "May";
		month[5] = "June";
		month[6] = "July";
		month[7] = "August";
		month[8] = "September";
		month[9] = "October";
		month[10] = "November";
		month[11] = "December";

		return month[num];
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
	function action_lastclick_row()
	{
		$(document).on("click", "tbody.draggable tr:last td:not(.remove-tr)", function(){
			action_lastclick_row_op();
		});
	}
	function action_lastclick_row_op()
	{
		$("tbody.draggable").append(global_tr_html);
		action_initialize_select();
		action_reassign_number();
	}

	function item_offset_select($this)
	{
		$("tbody.listing-offset-item").append(offset_item_tr);
		$lasttr = $("tbody.listing-offset-item tr:last");	
		$lasttr.find(".offset-input-itemid").val($this.val()).change();
		$lasttr.find(".offset-input-itemprice").val($this.find("option:selected").attr("price")).change();
		$lasttr.find(".offset-itemname").html($this.find("option:selected").attr("item-name"));
		$price = action_return_to_number($this.find("option:selected").attr("price"));
		$lasttr.find(".offset-itemprice").html(action_add_comma($price.toFixed(2)));
		$lasttr.attr("item-id",$this.val());
		$lasttr.addClass("item-id-"+$this.val());
		if($(".item-id-"+$this.val()).length > 1)
		{
			$(".listing-offset-item tr.item-id-"+$this.val()+":last").remove();
		}
		$lasttr.find(".remove-btn-offset").attr("item-id", $this.val());
		compute_offset_item();
		$this.val("").change();
	}
	function compute_offset_item()
	{
		$amount = 0;
		$(".offset-input-itemprice").each(function()
		{
			$amount += action_return_to_number($(this).val());
		});
		$(".offset-item-total").val("("+action_add_comma($amount.toFixed(2))+")");
	}
	function remove_item_offset()
	{
		$("body").on("click",".remove-btn-offset", function()
		{
			$(".listing-offset-item tr.item-id-"+$(this).attr("item-id")).remove();
			compute_offset_item();
		});
	}
	function event_submit_form()
	{
		$('.save-button').unbind('click')
		$('.save-button').bind('click', function()
		{
			$('.form-to-submit-add').submit();
		});
	}
	function table_loading()
	{
		$(".load-item-table-pos").css("opacity", 0.3);
	}
	function event_change_quantity()
	{
		$("body").on("keyup", ".quantity-item", function(e)
		{
			var item_id = $(e.currentTarget).attr('item-id');
			var quantity = $(e.currentTarget).val();

			$.ajax(
			{
				url 		: "/member/item/warehouse/wis/change-quantity?item_id=" + item_id + "&qty=" + quantity,
				dataType 	: "json",
				type 		: "get",
				success 	: function(data)
				{
					action_load_item_table();
				}
			});
		});
	}
	function event_change_global_discount()
	{
		$(".cart-global-discount").keyup(function()
		{
			table_loading();
			clearTimeout(settings_delay_timer);

		    settings_delay_timer = setTimeout(function()
		    {
		       	action_set_cart_info("global_discount", $(".cart-global-discount").val());
		    }, 500);
		});
	}
	function action_set_cart_info($key, $value)
	{
		table_loading();

		if($value == "" || $value == null)
		{
			$value = 0;
		}

		$.ajax(
		{
			url 		: "/member/cashier/pos/set_cart_info/" + $key + "/" + $value,
			dataType 	: "json",
			type 		: "get",
			success 	: function(data)
			{
				action_load_item_table();
			}
		});
	}
	function event_remote_item_from_cart()
	{
		$("body").on("click", ".remove-item-from-cart", function(e)
		{
			$item_id = $(e.currentTarget).closest(".item-info").attr("item_id");

			$(e.currentTarget).html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
			table_loading();


			$.ajax(
			{
				url:"/member/item/warehouse/wis/create-remove-item",
				dataType:"json",
				data: {"item_id":$item_id},
				type:"get",
				success: function(data)
				{
					action_load_item_table();
				}
			});
		});
	}
	function event_search_item()
	{
		$("body").on('keyup', '.event_search_item' ,function(e)
		{
			if(e.which == 13) //ENTER KEY
			{
				action_scan_item($(".event_search_item").val());
				action_hide_search();
			}
			else if(e.which == 38) //UP KEY
			{
				event_search_item_cursor_next(true);
			}
			else if(e.which == 40) //DOWN KEY
			{
				event_search_item_cursor_next();
			}
			else /* SEARCH MODE */
			{
				if($(".event_search_item").val() == "")
				{
					action_hide_search();
				}
				else
				{
					keysearch.item_keyword = $(".event_search_item").val();
					keysearch._token = $(".token").val();
					if(load_item)
					{
						load_item.abort();
					}

					clearTimeout(item_search_delay_timer);

				    item_search_delay_timer = setTimeout(function()
				    {
				       $(".pos-search-container").html(get_loader_html(10)).show();
				       action_ajax_search_item();
				    }, 500);
				}
			}
		});

		$("body").click(function(event)
		{
			if(!$(event.target).is('.pos-item-search-result'))
			{
			    action_hide_search();
			}
		});

	}
	function event_search_item_cursor_next(reverse = false)
	{
		var current_cursor = $(".pos-item-search-result.cursor");

		if(current_cursor.length < 1)
		{
			$(".pos-item-search-result:first").addClass("cursor");
		}
		else
		{
			if(reverse == true)
			{
				$(".pos-item-search-result.cursor").prev(".pos-item-search-result").addClass("cursor");
			}
			else
			{
				$(".pos-item-search-result.cursor").next(".pos-item-search-result").addClass("cursor");
			}
			
			current_cursor.removeClass("cursor");
		}

		$active_item_id = $(".pos-item-search-result.cursor").attr("item_id");
		$(".event_search_item").val($active_item_id);
	}
	function event_click_search_result()
	{
		$("body").on("click", ".pos-item-search-result", function(e)
		{
			$item_id = $(e.currentTarget).attr("item_id");
			action_scan_item($item_id);
			action_hide_search();
		});
	}
	function action_scan_item($item_id)
	{
		$(".event_search_item").val("");
		$(".event_search_item").attr("disabled", "disabled");
		$(".button-scan").find(".scan-load").show();
		$(".button-scan").find(".scan-icon").hide();

		scandata = {};
		scandata.item_id = $item_id;
		scandata._token = $(".token").val();

 		$.ajax(
		{
			url			: "/member/item/warehouse/wis/scan-item",
			dataType	: "json",
			type 		: "post",
			data 		: scandata,
			success 	: function(data)
			{
				$(".event_search_item").removeAttr("disabled");
				$(".button-scan").find(".scan-load").hide();
				$(".button-scan").find(".scan-icon").show();

				if(data.status == "success")
				{
					toastr.success("<b>SUCCESS!</b><br>" + data.message);
					success_audio.play();
					action_load_item_table();
				}
				else if(data.status == "error")
				{
					toastr.error("<b>ERROR!</b><br>" + data.message);
					error_audio.play();
				}

				$(".event_search_item").focus();
			},
			error : function(data)
			{
				$(".event_search_item").removeAttr("disabled");
				$(".button-scan").find(".scan-load").hide();
				$(".button-scan").find(".scan-icon").show();
				toastr.error("An error occured during scan - please contact system administrator");
				$(".event_search_item").focus();
			}
		});
	}
	function action_ajax_search_item()
	{
		load_item = $.ajax(
		{
			url:"/member/cashier/pos/search_item",
			type:"post",
			data: keysearch,
			success: function(data)
			{
				$(".pos-search-container").html(data);
			}
		});
	}
	function action_hide_search()
	{
		$(".pos-search-container").hide();
		clearTimeout(item_search_delay_timer);
	}
	function action_load_item_table()
	{
		if($(".load-item-table-pos").text() != "")
		{
			table_loading();
		}
		else
		{
			$(".load-item-table-pos").html(get_loader_html());
		}

		
		$(".load-item-table-pos").load("/member/item/warehouse/wis/table-item", function()
		{
			action_update_big_totals();
			$(".load-item-table-pos").css("opacity", 1);
		});
	}
	function action_update_big_totals()
	{
		$(".big-total").find(".grand-total").text($(".table-grand-total").val());
		$(".big-total").find(".amount-due").text($(".table-amount-due").val());
	}
	function get_loader_html($padding = 50)
	{
		return '<div style="padding: ' + $padding + 'px; font-size: 20px;" class="text-center"><i class="fa fa-spinner fa-pulse fa-fw"></i></div>';
	}
	function load_applied_transaction()
	{
		$(".loading-tbody").removeClass("hidden");

		$('.applied-transaction-list').load('/member/transaction/wis/load-applied-transaction', function()
		{
			action_initialize_select();
			action_compute();
			action_reassign_number();
			$('.remarks-wis').html($('.inv-remarks').val());
			
			$(".loading-tbody").addClass("hidden");
			if($(".monthly-budget-input").val())
			{
				$(".monthly-budget-div").removeClass("hidden");
			}
		});
	}
	function event_load_transaction()
	{
		$trans_id = $(".sales-id").val();
		if($trans_id)
		{
			$.ajax({
				url : '/member/transaction/wis/ajax-apply-transaction',
				type : 'get',
				data : {apply_transaction : $trans_id},
				success : function ()
				{
					load_applied_transaction();
				}
			});
		}
	}
	this.load_applied_transaction = function()
	{
		load_applied_transaction();
	}
}
function success_update_customer(data)
{
	$(".droplist-customer").load("/member/customer/load_customer", function()
    {                
        $(".droplist-customer").globalDropList("reload");
        $(".droplist-customer").val(data.id).change();

    	data.element.modal("hide");
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
function submit_done(data)
{
    if(data.type == "truck")
    {        
        toastr.success("Success");
        $(".select-truck").load("/member/pis/sir/create .select-truck option", function()
        {                
             $(".select-truck").globalDropList("reload"); 
             $(".select-truck").val(data.id).change();              
        });
        $('#global_modal').modal('toggle');
        $('.multiple_global_modal').modal('hide');  
    }
}
function new_price_level_save_done(data)
{
	$("#global_modal").modal("hide");
	$(".price-level-select").append('<option value="' + data.price_level_id + '">' + data.price_level_name + '</option>');
	$(".price-level-select").globalDropList("reload");
	$(".price-level-select").val(data.price_level_id).change();
}
function success_create_customer_wis(data)
{
	if(data.status == 'success')
	{
		toastr.success("Success");
       	location.href = data.redirect_to;
	}
}
function success_apply_transaction(data)
{
	if(data.status == 'success')
	{
		data.element.modal("toggle");
		customer_wis_create.load_applied_transaction();
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
