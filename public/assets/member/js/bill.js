var bill = new bill();
var global_tr_html = $(".div-script tbody").html();
var global_acct_tr_html = $(".acct-div-script tbody").html();
var po_id_list = $(".div-script-po").html();
var item_selected = ''; 

function bill()
{
	init();

	function init()
	{
		iniatilize_select();
		draggable_row.dragtable();

		event_remove_tr();
		event_accept_number_only();
		event_compute_class_change();
		event_taxable_check_change();
		
		action_lastclick_row();
		action_compute();
		action_date_picker();
		action_reassign_number();
		action_acct_reassign_number();
		event_button_action_click();
	}

	function event_remove_tr()
	{
		//cycy
		$(document).on("click", ".remove-tr", function(e){
			if($(".tbody-item .remove-tr").length > 1)
			{
				if($(this).attr("tr_id") != 0 && $(this).attr("tr_id") != null)
				{					
					var id = $(this).attr("tr_id");
					console.log($(this).attr("linked_in"));
					if($(this).attr("linked_in") != 'no')
					{						
						$(".tr-id-"+id).remove();
					}
					else
					{
						$(".po-tbl").load("/member/vendor/po_remove/"+id, function()
						{
							// console.log("success-removing");
							iniatilize_select();
							$(".tbody-item .select-um").globalDropList("enabled");
							$(".po-"+id).removeClass("hidden");
							$(".drawer-toggle").trigger("click");
							action_reassign_number();
							action_compute();
						});
					}
				}
				else
				{
					$(this).parent().remove();
				}

				action_reassign_number();
				action_compute();
			}			
		});

		//cycy
		$(document).on("click", ".acct-remove-tr", function(e){
			if($(".tbody-acct .acct-remove-tr").length > 1)
			{
				$(this).parent().remove();

				action_acct_reassign_number();
				action_compute();
			}			
		});
	}

	this.action_lastclick_row = function()
	{
		action_lastclick_row();
	}

	function action_lastclick_row()
	{
		$(document).on("click", "tbody.draggable.tbody-item tr:last td:not(.remove-tr)", function(){
			action_lastclick_row_op();
		});
		$(document).on("click", "tbody.draggable.tbody-acct tr:last td:not(.acct-remove-tr)", function(){
			action_lastclick_acct_row_op();
		});
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

	function action_lastclick_row_op()
	{
		$("tbody.draggable.tbody-item").append(global_tr_html);
		action_reassign_number();
		action_trigger_select_plugin();
		action_date_picker();
	}
	function action_lastclick_acct_row_op()
	{
		$("tbody.draggable.tbody-acct").append(global_acct_tr_html);
		action_acct_reassign_number();
		action_trigger_select_plugin();
	}

	function action_date_picker()
	{
		$(".draggable .for-datepicker").datepicker();
	}

	this.action_reassign_number = function()
	{
		action_reassign_number();
	}

	function action_reassign_number()
	{
		var num = 1;
		$(".invoice-number-td").each(function(){
			$(this).html(num);
			num++;
		});
	}
	function action_acct_reassign_number()
	{
		var num = 1;
		$(".acct-number-td").each(function(){
			$(this).html(num);
			num++;
		});		
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
			   	
			    if(ret == 0){
			    	ret = '';
			    }

				return ret;
			  });
		});
	}

	function event_compute_class_change()
	{
		$(document).on("change",".compute", function()
		{
			action_compute();
		});
	}

	function action_compute()
	{
		var subtotal = 0;
		var total_taxable = 0;
		var acct_amount = 0;
		$(".acct-amount").each(function()
		{
			acct_amount += parseFloat(action_return_to_number($(this).val()));
		});
		$(".tr-draggable").each(function()
		{
			/* GET ALL DATA */
			var qty 	= $(this).find(".txt-qty").val();
			var rate 	= $(this).find(".txt-rate").val();
			var discount= 0;
			var amount 	= $(this).find(".txt-amount");
			var taxable = $(this).find(".taxable-check");
			
			/* CHECK IF QUANTITY IS EMPTY */
			if(qty == "" || qty == null)
			{
				qty = 1;
			}

			/* CHECK THE DISCOUNT */
			// if(discount.indexOf('%') >= 0)
			// {
			// 	$(this).find(".txt-discount").val(discount.substring(0, discount.indexOf("%") + 1));
			// 	discount = (parseFloat(discount.substring(0, discount.indexOf('%'))) / 100) * action_return_to_number(rate);
			// }
			// else if(discount == "" || discount == null)
			// {
			// 	discount = 0;
			// }
			// else
			// {
			// 	discount = parseFloat(discount);
			// }

			/* RETURN TO NUMBER IF THERE IS COMMA */
			qty = action_return_to_number(qty);
			rate = action_return_to_number(rate);
			discount = action_return_to_number(discount);

			var total_per_tr = ((qty * rate) - discount).toFixed(2);

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

		});
		
		/* action_compute EWT */
		var ewt_value 			= $(".ewt-value").val();

		ewt_value = parseFloat(ewt_value) * subtotal;

		/* action_compute DISCOUNT */
		var discount_selection 	= $(".discount_selection").val();
		var discount_txt 		= $(".discount_txt").val();
		var tax_selection 		= $(".tax_selection").val();
		var taxable_discount 	= 0;

		if(discount_txt == "" || discount_txt == null)
		{
			discount_txt = 0;
		}

		discount_total = 0;

		if(discount_selection == 'percent')
		{
			discount_total = subtotal * (discount_txt / 100);
			taxable_discount = total_taxable * (discount_txt / 100);
		}

		discount_total = 0;

		/* action_compute TOTAL */
		var total = 0;
		total     = subtotal - discount_total - ewt_value;

		/* action_compute TAX */
		var tax   = 0;
		if(tax_selection == 1){
			tax = total_taxable * (12 / 100);
		}
		total += tax;


		$(".sub-total").html(action_add_comma(subtotal.toFixed(2)));
		$(".subtotal-amount-input").val(action_add_comma(subtotal.toFixed(2)));
		$(".ewt-total").html(action_add_comma(ewt_value.toFixed(2)));
		$(".discount-total").html(action_add_comma(discount_total.toFixed(2)));
		$(".tax-total").html(action_add_comma(tax.toFixed(2)));
		$(".total-amount").html(action_add_comma((subtotal + acct_amount).toFixed(2)));
		$(".total-amount-input").val((subtotal + acct_amount).toFixed(2));

	}

	function action_add_comma(number)
	{
		number += '';
		if(number == '0' || number == ''){
			return '';
		}

		else{
			return number.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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


	function action_trigger_select_plugin_not_last()
	{
		$(".draggable .tr-draggable:first td select.select-item").globalDropList(
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
        $(".draggable .tr-draggable:first td select.select-um").globalDropList(
        {
        	hasPopup: "false",
    		width : "100%",
    		placeholder : "um.."
        });
	}	
	function action_trigger_select_plugin()
	{
		$(".draggable .tr-draggable:last td select.select-item").globalDropList(
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
        $(".draggable .tr-draggable:last td select.select-um").globalDropList(
        {
        	hasPopup: "false",
    		width : "100%",
    		placeholder : "um.."
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

	
	function iniatilize_select()
	{
		$('.droplist-vendor').globalDropList(
		{ 
			width : "100%",
			link : "/member/vendor/add",
			onChangeValue: function()
			{
				$(".customer-email").val($(this).find("option:selected").attr("email"));
				$('textarea[name="bill_mailing_address"]').val($(this).find("option:selected").attr("billing-address"));
				load_purchase_order_vendor($(this).find("option:selected").attr("value"));

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
		$('.droplist-terms').globalDropList(
        {
            link : "/member/maintenance/terms/terms",
            link_size : "sm",
            width : "100%",
            onChangeValue: function()
            {
            	var start_date 		= $(".datepicker[name='bill_date']").val();
            	var days 			= $(this).find("option:selected").attr("days");
            	var new_due_date 	= AddDaysToDate(start_date, days, "/");
            	$(".datepicker[name='bill_due_date']").val(new_due_date);
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
	}

	function AddDaysToDate(sDate, iAddDays, sSeperator) {
    //Purpose: Add the specified number of dates to a given date.
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
		$(".purchase-order-container").load("/member/vendor/load_purchase_order/"+vendor_id , function()
		{
			if($(".po-counter").length > 0)
			{
				$(".purchase-order").removeClass("hidden");
				$(".drawer-toggle").trigger("click");
			}
		});
	}

	function action_load_coa_info($this)
	{
		$parent = $this.closest(".tr-draggable");
		$parent.find(".acct-desc").html($this.find("option:selected").attr("acct-desc")).change();		
	}
	function action_load_item_info($this)
	{
		$parent = $this.closest(".tr-draggable");
		// $parent.find(".txt-desc").html($this.find("option:selected").attr("sales-info")).change();
		$parent.find(".txt-rate").val($this.find("option:selected").attr("cost")).change();
		$parent.find(".txt-qty").val(1).change();

		$parent.find(".txt-rate").attr("readonly",false);
		$parent.find(".txt-discount").attr("disabled",false);
		if($this.find("option:selected").attr("item-type") == 4)
		{
			$parent.find(".txt-rate").attr("readonly",true);
			$parent.find(".txt-discount").attr("disabled","disabled");
		}
		if($this.find("option:selected").attr("has-um") != '')
		{
			$.ajax(
			{
				url: '/member/item/load_one_um/' +$this.find("option:selected").attr("has-um"),
				method: 'get',
				success: function(data)
				{						
					$parent.find(".select-um").load('/member/item/load_one_um/' +$this.find("option:selected").attr("has-um"), function()
					{
						$(this).globalDropList("reload").globalDropList("enabled");
						console.log($(this).find("option:first").val());
						$(this).val($(this).find("option:first").val()).change();
					})
				},
				error: function(e)
				{
					console.log(e.error());
				}
			})
		}
		else
		{
			$parent.find(".select-um").html('<option class="hidden" value=""></option>').globalDropList("reload").globalDropList("clear");
		}
	}

	function action_load_unit_measurement($this)
	{
		$parent = $this.closest(".tr-draggable");
		$item   = $this.closest(".tr-draggable").find(".select-item");

		$um_qty = parseFloat($this.find("option:selected").attr("qty") || 1);
		$sales  = parseFloat($item.find("option:selected").attr("cost"));
		$qty    = parseFloat($parent.find(".txt-qty").val());
		console.log($um_qty +"|" + $sales +"|" +$qty);
		$parent.find(".txt-rate").val( $um_qty * $sales * $qty ).change();
	}

	this.action_reload_customer = function($id)
	{
		action_reload_customer($id);
	}

	function action_reload_customer($id)
	{
		$.ajax(
		{
			url: '/member/customer/load_customer',
			method: 'GET',
			success: function(data)
			{
				$element = $(".droplist-customer");

				$element.html(data);
				$element.globalDropList("reload");
				$element.val($id).change();
				
			},
			error: function()
			{
				console.log("error");
			}

		})
	}

	this.action_reload_item = function($id)
	{
		action_reload_item($id);
	}

	function action_reload_item($id)
	{
		$.ajax(
		{
			url: '/member/item/load_item_category',
			method: 'GET',
			success: function(data)
			{
				$element = $(".tbody-item .select-item");

				$element.html(data);
				
				$element.globalDropList("reload");

				// Filter selected combobox only
				item_selected.val($id).change();
			},
			error: function()
			{
				console.log("error");
			}

		})
	}
	this.action_compute = function()
	{
		action_compute();
	}
	this.iniatilize_select = function()
	{
		iniatilize_select();
	}
	this.action_trigger_select_plugin = function()
	{
		action_trigger_select_plugin();
	}
	this.action_trigger_select_plugin_not_last = function()
	{
		action_trigger_select_plugin_not_last();
	}

}	


/* AFTER DRAGGING A TABLE ROW */
function dragging_done()
{
	bill.action_reassign_number();
    bill.action_compute();
}

/* AFTER ADDING A CUSTOMER */
function submit_done_customer(result)
{
	toastr.success("Success");
    $(".droplist-customer").load("/member/customer/load_customer", function()
    {                
         $(".droplist-customer").globalDropList("reload");
         $(".droplist-customer").val(result.id).change();          
    });
}

/* AFTER ADDING AN  ITEM */
function submit_done_item(data)
{
    item_selected.load("/member/item/load_item_category", function()
    {                
         $(this).globalDropList("reload"); 
         $(this).val(data.item_id).change();  
         toastr.success("Success");
    });
    data.element.modal("hide");
}
function add_po_to_bill(po_id)
{
	// $(".modal-loader").removeClass("hidden");
	// $.ajax({
	// 	url : "/member/vendor/load_po_item",
	// 	data : {po_id: po_id},
	// 	dataType : "json",
	// 	type : "get",
	// 	success : function(data)
	// 	{
 //             $(data).each(function (a, b)
 //             {		
	//              var $container = "";
	//              var con = $("tbody.draggable").prepend(global_tr_html);
	//              bill.action_trigger_select_plugin_not_last();
	//              $container = $("tbody.draggable .tr-draggable:first");
	//              // $this.closest(".tr-draggable");

	//             $container.addClass("tr-"+b.poline_po_id);
	//             $container.find(".select-item").val(b.poline_item_id).change();
	//             $container.find(".txt-desc").val(b.poline_description);
	//             $container.find(".select-um").load('/member/item/load_one_um/'+b.multi_um_id, function()
 //             	{
 //             		$container.find(".select-um").globalDropList("reload");
 //             		$container.find(".select-um").val(b.poline_um).change();
 //             	});
	// 			$container.find(".poline_id").val(b.poline_id);
	// 			$container.find(".itemline_po_id").val(po_id);
	//             $container.find(".txt-qty").val(b.poline_qty);
	//             $container.find(".txt-rate").val(b.poline_rate);
	//             $container.find(".txt-amount").val(b.poline_amount);
	//             $container.find(".remove-tr").addClass("remove-tr"+b.poline_po_id);
	//             $container.find(".remove-tr").attr("tr_id", b.poline_po_id);
 //             });

	//          $(".po-listing").prepend(po_id_list);
	//          var $po_id = $(".po-listing .po_id:first");
	//          $(".po-listing .po_id:first").addClass("div_po_id"+po_id);
	//          $po_id.find(".po-id-input").val(po_id).change();

	//         $(".po-"+po_id).addClass("hidden");
	// 		// $(".modal-loader").addClass("hidden");

 //             bill.action_compute();
 //             bill.action_reassign_number();
	// 	},
	// 	error : function()
	// 	{
	// 		alert("Something wen't wrong.");
	// 	}
	// });


	$(".po-tbl").load('/member/vendor/load_added_item/'+po_id, function()
	{
		console.log("success");
		bill.action_compute();
		bill.iniatilize_select();
		$(".tbody-item .select-um").globalDropList("enabled");

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
function submit_done(data)
{
	if(data.type == 'vendor')
	{		
       toastr.success("Success");
	    $(".droplist-vendor").load("/member/vendor/load_vendor", function()
	    {                
	         $(".droplist-vendor").globalDropList("reload");
	         $(".droplist-vendor").val(data.vendor_id).change();          
	    });
    	data.element.modal("hide");
	}
	else if(data.status == 'success-bill')
	{		
        toastr.success("Success");
       	location.href = data.redirect;
	}
	else if(data.status == 'success-receive-inventory')
	{
        toastr.success("Success");
       	location.href = data.redirect;		
	}
    else if(data.status == "error")
    {
        toastr.warning(data.status_message);
        $(data.target).html(data.view);
    }
}