var write_check = new write_check();
var global_tr_html = $(".div-script tbody").html();
var global_tr_html_acct = $(".acct-div-script tbody").html();
var item_selected = ''; 
var bin_selected = ''; 

function write_check()
{
	init();

	function init()
	{
		action_load_initialize_select();
		action_compute();
		action_date_picker();
		action_reassign_number();
		event_remove_tr();

		event_compute_class_change();
		event_taxable_check_change();
		event_accept_number_only();
		event_click_last_row();
		event_remove_tr_acct();

		event_chenge_cost_rate();
		event_keypress_enter();
		load_check_po();
		event_keypress_enter_OFF_barcode();
		transaction_status();

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
	function load_check_po()
	{
		if($(".po-id").val())
		{
			load_applied_transaction();
			action_load_open_transaction($(".po-vendor-id").val());
		}
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
		    	event_click_last_row_op();
		    }
		});
	}
	function event_chenge_cost_rate() 
	{
		$("body").on("change",".new-cost", function()
		{
			$parent = $(this).closest(".tr-draggable");
			$item_id = $parent.find(".select-item").val();
			if($item_id && $item_id != 0 && $(".item-new-cost").val() == "new_cost")
			{
				$item_cost   = $parent.find(".select-item").find("option:selected").attr("cost");
				$um_qty   = $parent.find(".select-um").find("option:selected").attr("qty");
				$allow_item_new_cost = $(".item-new-cost").val();
				$cost_entry = parseFloat($(this).val());
				$nwum_qty = 1;
				if($um_qty)
				{
					$nwum_qty = $um_qty;
				}
				$new_cost = $cost_entry / parseFloat($nwum_qty);

				if($item_cost != $new_cost && $allow_item_new_cost == 1)
				{
					// alert("Change cost item# "+$item_id);
					action_load_link_to_modal("/member/item/v2/item_cost_change?d="+$item_id+"&new_cost="+$new_cost);
				}
			}
		});
	}
	function action_load_initialize_select()
	{
		$('.droplist-vendor').globalDropList(
		{ 
			width : "100%",
			link : "/member/vendor/add",
			onChangeValue: function()
			{
				$(".customer-email").val($(this).find("option:selected").attr("email"));
				load_purchase_order_vendor($(this).find("option:selected").attr("value"));

			}
		}); 
		$('.drop-down-name').globalDropList(
		{ 
			width : "100%",
			hasPopup : "false",
		    placeholder : 'Customer or Vendor',
			onChangeValue: function()
			{
				$(".customer-email").val($(this).find("option:selected").attr("email"));
				$(".wc-ref-name").val($(this).find("option:selected").attr("reference"));
				$(".customer-mailing-address").val($(this).find("option:selected").attr("address"));

				if($(this).find("option:selected").attr("reference") == "vendor")
				{
					load_purchase_order_vendor($(this).find("option:selected").attr("value"));

					action_load_open_transaction($(this).val());
					$(".popup-link-open-transaction").attr('link','/member/transaction/write_check/load-transaction?vendor='+$(this).val());
					$(".count-open-transaction").html($(this).find("option:selected").attr("ctr-wc"));
				}
				else
				{
					$(".open-transaction").slideUp();
				}
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
		$(".drop-down-customer").globalDropList(
		{
		    link 		: '/member/customer/modalcreatecustomer',
		    link_size 	: 'lg',
		    width 		: "100%",
		    placeholder : 'Customer',
		    onChangeValue: function()
		    {
		    	var customer_id = $(this).val();
		    	$(".tbody-item").load("/member/customer/load_rp/"+ (customer_id != '' ? customer_id : 0), function()
		    	{
		    		action_compute_maximum_amount();
		    	})
		    }
		});
	    $('.droplist-item').globalDropList(
        {
            link : "/member/item/add",
            width : "100%",
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
    	// $('.droplist-um:not(.has-value)').globalDropList("disabled");

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
        // $('.droplist-um:not(.has-value)').globalDropList("disabled");
		$(".drop-down-payment").globalDropList(
		{
		    link 		: '/member/maintenance/payment_method/add',
		    link_size 	: 'lg',
		    width 		: "100%",
		    placeholder : 'Payment Method'
		});
		$(".drop-down-coa").globalDropList(
		{
		    link 		: '/member/accounting/chart_of_account/popup/add',
		    link_size 	: 'md',
		    width 		: "100%",
		    placeholder : 'Account',
            onChangeValue : function()
            {
            	action_load_coa_info($(this));
            }
		});

		$(".draggable.tbody-acct .tr-draggable:last td select.select-coa").globalDropList(
        {            
		    link 		: '/member/accounting/chart_of_account/popup/add',
		    link_size 	: 'md',
		    width 		: "100%",
		    placeholder : 'Account',
            onChangeValue : function()
            {
            	action_load_coa_info($(this));
            }
        });
	}
	function action_load_coa_info($this)
	{
		$parent = $this.closest(".tr-draggable");
		$parent.find(".acct-desc").html($this.find("option:selected").attr("acct-desc")).change();		
	}
	function action_load_open_transaction($vendor_id)
	{
		if($vendor_id)
		{
			// $.ajax({
			// 	url : '/member/transaction/write_check/count-transaction',
			// 	type : 'get',
			// 	data : {vendor_id : $vendor_id},
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
	function action_compute()
	{
		var subtotal = 0;
		var total_taxable = 0;
		var acct_amount = 0;
		var disc = 0;

		$(".acct-amount").each(function()
		{
			acct_amount += parseFloat(action_return_to_number($(this).val()));
			//alert(acct_amount);
		});

		$(".tr-draggable").each(function()
		{
			/* GET ALL DATA */
			var qty 	= $(this).find(".txt-qty").val();
			var rate 	= $(this).find(".txt-rate").val();
			var discount= $(this).find(".txt-discount").val();
			var amount 	= $(this).find(".txt-amount");
			var taxable = $(this).find(".taxable-check");
			var orig_qty= $(this).find(".txt-orig-qty").val();
			var orig_disc= $(this).find(".txt-orig-disc").val();
			var per_line = 0;
			
			/* CHECK IF QUANTITY IS EMPTY */
			if(qty == "" || qty == null)
			{
				qty = 1;
			}

			/* CHECK THE DISCOUNT */
			if($(this).find(".txt-discount").val())
			{
				if(discount.indexOf('%') >= 0)
				{
					$(this).find(".txt-discount").val(discount.substring(0, discount.indexOf("%") + 1));
					discount = (parseFloat(discount.substring(0, discount.indexOf('%'))) / 100) * action_return_to_number(rate) * action_return_to_number(qty);
				}
			}
			else if(discount == "" || discount == null)
			{
				discount = 0;
			}
			else
			{
				discount = parseFloat(discount);
			}
			if(orig_qty == "" || orig_qty == null)
			{
				orig_qty = 1;
			}

			if(orig_disc == "" || orig_disc == null)
			{
				orig_disc = 0;
			}
			else
			{
				orig_disc = parseFloat(orig_disc);
			}

			/* RETURN TO NUMBER IF THERE IS COMMA */
			qty = action_return_to_number(qty);
			rate = action_return_to_number(rate);
			discount = action_return_to_number(discount);
			orig_qty = action_return_to_number(orig_qty);
			orig_disc = action_return_to_number(orig_disc);

			if($(this).find(".if-fixed").val() == 'fixed')
			{
				var disc_per_item = ((orig_disc / orig_qty) * qty).toFixed(2);
				disc = parseFloat(disc_per_item);
				$(this).find(".disc").val(action_add_comma(disc));

				per_line = ((qty * rate) - disc).toFixed(2);
			}
			else
			{
				per_line = ((qty * rate) - discount).toFixed(2);
			}
			var total_per_tr = per_line

			/* action_compute SUB TOTAL PER LINE */
			subtotal += parseFloat(total_per_tr);

			/* AVOID ZEROES */
			if(total_per_tr <= 0)
			{
				total_per_tr = '';
			}

			/* CONVERT TO INTEGER */
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
			if(taxable.is(':checked'))
			{
				total_taxable += parseFloat(total_per_tr);
			}

			$(this).find(".txt-rate").val(action_add_comma(rate.toFixed(2)));
		});
		
		/* action_compute EWT */
		/*var ewt_value 			= $(".ewt-value").val();

		ewt_value = parseFloat(ewt_value) * subtotal;*/

		/* action_compute DISCOUNT */
		var discount_selection 	= $(".discount_selection").val();
		var discount_txt 		= $(".discount_txt").val();
		var tax_selection 		= $(".tax_selection").val();
		var taxable_discount 	= 0;
		var disc = 0;

		if(discount_txt == "" || discount_txt == null)
		{
			discount_txt = 0;
		}

		discount_total = discount_txt;

		if(discount_selection == 'percent')
		{
			disc = action_return_to_number(subtotal) * (discount_txt / 100);
			discount_total = action_return_to_number(disc);

			taxable_discount = total_taxable * (discount_txt / 100);
		}

		discount_total = parseFloat(action_return_to_number(discount_total));

		/* action_compute TOTAL */
		var total = 0;
		total     = subtotal - discount_total /*- ewt_value*/;

		/* action_compute TAX */
		var tax   = 0;
		if(tax_selection == 1){
			tax = total_taxable * (12 / 100);
		}
		total += tax;
		console.log(subtotal);
		$(".sub-total").html(action_add_comma((subtotal + acct_amount).toFixed(2)));
		$(".subtotal-amount-input").val(action_add_comma(subtotal.toFixed(2)));
		$(".discount-total").html(action_add_comma(discount_total.toFixed(2)));
		$(".tax-total").html(action_add_comma(tax.toFixed(2)));
		$(".total-amount").html(action_add_comma((total + acct_amount).toFixed(2)));
		$(".total-amount-input").val((total + acct_amount).toFixed(2));

	}

	function action_load_unit_measurement($this)
	{
		$parent = $this.closest(".tr-draggable");
		$item   = $this.closest(".tr-draggable").find(".select-item");

		$um_qty = parseFloat($this.find("option:selected").attr("qty") || 1);
		$sales  = parseFloat($item.find("option:selected").attr("cost"));
		$qty    = parseFloat($parent.find(".txt-qty").val());

		$parent.find(".txt-rate").val( $um_qty * $sales).change();

    	action_compute();
	}

	function action_return_to_number(number = '')//
	{

		number += '';
		number = number.replace(/,/g, "");
		if(number == "" || number == null || isNaN(number)){
			number = 0;
		}
		
		return parseFloat(number);
	}

	function action_add_comma(number)//
	{
		number += '';
		if(number == '0' || number == ''){
			return '';
		}

		else{
			return number.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		}
	}

	function event_compute_class_change()
	{
		$(document).on("change",".compute", function()
		{
			action_compute();
			if($(this).hasClass("txt-rate") || $(this).hasClass("acct-amount"))
			{
				$val = $(this).val();
				$(this).val($val);
			}
		});
	}

	function event_button_action_click()
	{
		$(document).on("click","button[type='submit']", function()
		{
			$(".button-action").val($(this).attr("data-action"));
		})
	}
	/* Make select input into a drop down list plugin */

	/* CHECK BOX FOR TAXABLE */
	function event_taxable_check_change()//
	{
		$(".taxable-check").unbind("change");
		$(".taxable-check").bind("change", function()
		{
			action_change_input_value($(this));
		});
	}

	function check_change_input_value()//
	{
		$(".taxable-check").each( function()
		{
			action_change_input_value($(this));
		})
	}
	
	function action_change_input_value($this)//
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
	function action_load_item_info($this)
	{
		$parent = $this.closest(".tr-draggable");
		$parent.find(".txt-desc").html($this.find("option:selected").attr("sales-info")).change();
		$parent.find(".txt-qty").val(1).change();
		$item_cost = $this.find("option:selected").attr("cost");
		if($(".item-new-cost").val() == "average_costing")
		{
			$item_cost = $this.find("option:selected").attr("warehouse_"+$(".current-warehouse").val());			
		}
		$parent.find(".txt-rate").val($item_cost).change();
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
			$parent.find(".select-um").html('<option class="hidden" value=""></option>').globalDropList("reload").globalDropList("clear");
		}
    	action_compute();
	}
	//Purpose: Add the specified number of dates to a given date.
	function AddDaysToDate(sDate, iAddDays, sSeperator)
	{
	    var date = new Date(sDate);
	    date.setDate(date.getDate() + parseInt(iAddDays));
	    var sEndDate = LPad(date.getMonth() + 1, 2) + sSeperator + LPad(date.getDate(), 2) + sSeperator + date.getFullYear();
	    return sEndDate;
	}

	function LPad(sValue, iPadBy) {
	    sValue = sValue.toString();
	    return sValue.length < iPadBy ? LPad("0" + sValue, iPadBy) : sValue;
	}

	function load_purchase_order_vendor(vendor_id)
	{
		$(".purchase-order-and-bill-container").load("/member/vendor/load_po_bill/"+vendor_id , function()
			{
				if($(".po-bill-count").length > 0 || $(".po-bill-count-bill").length > 0)
				{
					$(".purchase-order").removeClass("hidden");
					// $(".drawer").drawer({openClass: "drawer-open"});
					$(".drawer-toggle").trigger("click");					
				}
			});
	}

	function action_date_picker()
	{/*class name of tbody and text field for date*/
		$(".draggable .for-datepicker").datepicker({ dateFormat: 'mm-dd-yy', });
	}

	/*ITEM NUMBER*/
	function action_reassign_number()
	{
		var num = 1;
		$(".invoice-number-td").each(function(){
			$(this).html(num);
			num++;
		});
	}
	function action_reassign_number_acct()
	{
		var num = 1;
		$(".acct-number-td").each(function(){
			$(this).html(num);
			num++;
		});
	}

	function event_click_last_row()
	{
		$(document).on("click", "tbody.draggable.tbody-item tr:last td:not(.remove-tr)", function(){
			event_click_last_row_op();
		});
		$(document).on("click", "tbody.draggable.tbody-acct tr:last td:not(.acct-remove-tr)", function(){
			event_click_last_row_op_acct();
		});
	}

	/*INSERTING ANOTHER ROW WHEN CLICKING LAST ROW*/
	function event_click_last_row_op()
	{
		$("tbody.draggable.tbody-item").append(global_tr_html);
		event_taxable_check_change();
		action_reassign_number();
		action_load_initialize_select();
		action_date_picker();
	}
	function event_click_last_row_op_acct()
	{
		$("tbody.draggable.tbody-acct").append(global_tr_html_acct);
		action_reassign_number_acct();
		action_load_initialize_select();
		action_date_picker();
	}

	/*REMOVING ROW*/
	function event_remove_tr()
	{
		$(document).on("click", ".remove-tr", function(e){
			if($(".tbody-item .remove-tr").length > 1){

				$(this).parent().remove();
				action_reassign_number();
				action_compute();
			}			
		});
	}
	function event_remove_tr_acct()
	{
		$(document).on("click", ".acct-remove-tr", function(e){
			if($(".tbody-acct .acct-remove-tr").length > 1){

				$(this).parent().remove();
				action_reassign_number_acct();
				action_compute();
			}			
		});
	}

	function event_accept_number_only()//
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
			   	
			    if(ret == 0){
			    	ret = '';
			    }

				return ret;
			  });
		});
	}
	function load_applied_transaction()
	{
		$(".loading-tbody").removeClass("hidden");
		$('.applied-transaction-list').load('/member/transaction/write_check/load-applied-transaction', function()
		{
			console.log("success");
			action_reassign_number();
			action_load_initialize_select();
			action_date_picker();
	    	

			$('.remarks-wc').html($('.po-remarks').val());
			$('.tax-wc').val($('.po-tax').val());
			$('.disc-type-wc').val($('.po-disc-type').val());
			$('.disc-percentage-wc').val($('.po-disc-percentage').val());
			action_compute();
			$(".loading-tbody").addClass("hidden");
		});
	}
	this.load_applied_transaction = function()
	{
		load_applied_transaction();
	}
}

/*AFTER ADDING VENDOR*/
function success_vendor(data)
{
	$('.droplist-vendor').load("/member/vendor/load_vendor", function()
	{
		$('.droplist-vendor').globalDropList('reload');
		$('.droplist-vendor').val(data.vendor_id).change();

		data.element.modal("hide");
	});
}

/*AFTER ADDING ITEM*/
function success_item(data)
{
	$('.droplist-item').load("/member/item/load_item_category", function()
	{
		$(this).globalDropList("reload");
		$(this).val(data.item_id).change();

		data.element.modal("hide");
	});
}

function success_write_check(data)
{
	if(data.status == 'success')
	{
		toastr.success(data.status_message);
		location.href = data.status_redirect;
	}
}

function success_apply_transaction(data)
{
    if(data.status == "success")
    {
    	data.element.modal("toggle");
		write_check.load_applied_transaction();
    }
} 
//
function add_po_to_bill(po_id)
{
	$(".po-tbl").load('/member/vendor/load_added_item/'+po_id, function()
	{
		console.log("success");function add_po_to_bill(po_id)
{
	$(".po-tbl").load('/member/vendor/load_added_item/'+po_id, function()
	{
		console.log("success");
		write_check.action_compute();
		write_check.iniatilize_select();
		$(".tbody-item .select-um").globalDropList("enabled");
		$(".bill-data").addClass("hidden");
		$(".po-"+po_id).addClass("hidden");
	});
}
function iniatilize(id)
{
   $('.select-poline-item-'+id).globalDropList(
    {
        link : "/member/item/add",
        width : "100%"
    });
}
		write_check.action_compute();
		write_check.iniatilize_select();
		$(".tbody-item .select-um").globalDropList("enabled");
		$(".bill-data").addClass("hidden");
		$(".po-"+po_id).addClass("hidden");
	});
}
function iniatilize(id)
{
   $('.select-poline-item-'+id).globalDropList(
    {
        link : "/member/item/add",
        width : "100%"
    });
}

function success_update_price(data)
{
    if(data.status == "success")
    {
    	data.element.modal("toggle");
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