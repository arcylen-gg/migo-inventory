var purchase_order = new purchase_order();
var global_tr_html = $(".div-script tbody").html();
var item_selected = ''; 
var bin_selected = '';
function purchase_order()
{
	init();

	function init()
	{
		action_load_initialize_select();
		action_date_picker();
		action_reassign_number();

		event_remove_tr();
		event_compute_class_change();
		event_taxable_check_change();
		event_accept_number_only();
		event_click_last_row();
		action_compute();
		event_keypress_enter();
		event_keypress_enter_OFF_barcode();
		action_click_close_item();
		//event_taxable_check_change1();
	}
	function action_click_close_item()
	{
		$('body').on('click', '.item-status-check', function()
        {	
	    	var parent = $(this).closest('.tr-draggable');
	    	var name = parent.find('.item-name').val();
	    	var status = parent.find('.item-status').val();
	    	var check_status = parent.find('.item-status-check').val();

	    	if( status == 0 )
	    	{
	    		alert('Do you want to close item '+ name +'?');
		        parent.find('.item-status').val(1).change();
	    	}
	    	else
	    	{
	    		parent.find('.item-status').val(0).change();
	    	}
        });
	}
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

	function action_load_initialize_select()
	{

		$('.droplist-vendor').globalDropList(
		{ 	
			width : "100%",
			link : "/member/vendor/add",
			placeholder : "Select Vendor...",
			onChangeValue: function()
			{
				$(".vendor-email").val($(this).find("option:selected").attr("email"));
				$('textarea[name="vendor_address"]').val($(this).find("option:selected").attr("billing-address"));

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
		$('.droplist-terms').globalDropList(
		{ 	
			width : "100%",
			link : "/member/maintenance/terms/terms",
			placeholder : "Select Term...",
			onChangeValue: function()
			{
				var start_date 		= $(".datepicker[name='transaction_date']").val();
            	var days 			= $(this).find("option:selected").attr("days");
            	var new_due_date 	= AddDaysToDate(start_date, days, "/");
            	$(".datepicker[name='transaction_duedate']").val(new_due_date);
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

        $('.droplist-um:not(.has-value)').globalDropList("disabled");

        $(".draggable .tr-draggable:last td select.select-um").globalDropList(
        {
        	hasPopup: "false",
    		width : "100%",
    		placeholder : "um..",
    		onChangeValue: function()
    		{  
    			action_load_unit_measurement($(this));
    		}

        }).globalDropList('disabled');
	}

	function action_load_pdf($po_id)
	{
		if($po_id)
		{
			$.ajax({
				url : '/member/transaction/purchase_order/print',
				type : 'get',
				data : {po_id : $po_id},
				success : function(data)
				{
					$(".popup-link-pdf").attr('link','/member/transaction/purchase_order/print?id='+$po_id);
				}
			});
		}
	}
	function action_compute()
	{
		var subtotal = 0;
		var total_taxable = 0;
		$(".tr-draggable").each(function()
		{
			/* GET ALL DATA */
			var qty 	= $(this).find(".txt-qty").val();
			var rate 	= $(this).find(".txt-rate").val();
			var discount= $(this).find(".txt-discount").val();
			var amount 	= $(this).find(".txt-amount");
			var taxable = $(this).find(".taxable-check");

			$(this).find(".txt-remaining").val(qty - $(this).find(".txt-received").val());
			/* CHECK IF QUANTITY IS EMPTY */
			if(qty == "" || qty == null)
			{
				qty = 1;
			}

			/* CHECK THE DISCOUNT */
			/*if(discount.indexOf('%') >= 0)
			{
				discount_amount = (parseFloat(discount.substring(0, discount.indexOf('%'))) / 100);
				discount_amount = (rate * qty) * discount_amount;
			}*/
			
			if(discount.indexOf('%') >= 0)
			{
				$(this).find(".txt-discount").val(discount.substring(0, discount.indexOf("%") + 1));
				discount = (parseFloat(discount.substring(0, discount.indexOf('%'))) / 100) * (action_return_to_number(rate) * action_return_to_number(qty));
			}

			else if(discount == "" || discount == null)
			{
				discount = 0;
			}
			else
			{
				discount = parseFloat(discount);
			}

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

			$(this).find(".txt-rate").val(action_add_comma(rate.toFixed(2)));
		});

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
		else
		{
			discount_total = parseFloat(action_return_to_number(discount_total));
		}
		//alert(discount_total);
		
		//console.log(discount_total);
		/* action_compute TOTAL */
		var total = 0;
		total     = subtotal - discount_total /*- ewt_value*/;

		/* action_compute TAX */
		var tax   = 0;
		if(tax_selection == 1){
			tax = total_taxable * (12 / 100);
		}
		total += tax;
		console.log(tax);
		$(".sub-total").html(action_add_comma(subtotal.toFixed(2)));
		$(".subtotal-amount-input").val(action_add_comma(subtotal.toFixed(2)));
		//$(".ewt-total").html(action_add_comma(ewt_value.toFixed(2)));
		$(".discount-total").html(action_add_comma(discount_total.toFixed(2)));
		$(".tax-total").html(action_add_comma(tax.toFixed(2)));
		$(".total-amount").html(action_add_comma(total.toFixed(2)));
		$(".total-amount-input").val(total.toFixed(2));
		//alert(total);
	}

	function action_load_unit_measurement($this)
	{
		$parent = $this.closest(".tr-draggable");
		$item   = $this.closest(".tr-draggable").find(".select-item");

		$um_qty = parseFloat($this.find("option:selected").attr("qty") || 1);
		$sales  = parseFloat($item.find("option:selected").attr("cost"));
		$qty    = parseFloat($parent.find(".txt-qty").val());

		$parent.find(".txt-rate").val( action_add_comma(($um_qty * $sales).toFixed(2))).change();

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
		});
	}

	function convert_to_dec($string)
	{
		$amount = action_return_to_number($string);
		return action_add_comma($amount.toFixed(2));
	}
	

	function action_load_item_info($this)
	{
		$parent = $this.closest(".tr-draggable");
		// $parent.find(".txt-desc").html($this.find("option:selected").attr("purchase-info")).change();
		$parent.find(".txt-rate").val($this.find("option:selected").attr("cost")).change();
		$parent.find(".txt-qty").val(1).change();
		console.log($this.find("option:selected").attr("item-type"));
		
		if($this.find("option:selected").attr("has-um") && $this.find("option:selected").attr("has-um") != 0)
		{
			$parent.find(".txt-qty").attr("disabled",true);
			$.ajax(
			{
				url: '/member/item/load_one_um/' +$this.find("option:selected").attr("has-um"),
				method: 'get',
				success: function(data)
				{
					$parent.find(".select-um").load('/member/item/load_one_um/' +$this.find("option:selected").attr("has-um"), function()
					{
						$parent.find(".txt-qty").removeAttr("disabled");
						$(this).globalDropList("reload").globalDropList("enabled");
						$parent.find(".txt-qty").focus();
						$(this).val($(this).find("option:first").val()).change();

						action_compute();
					})
				},
				error: function(e)
				{
					console.log(e.error());
				}
			})
			action_compute();
		}
		else
		{
			$parent.find(".select-um").html('<option class="hidden" qty="1" value=""></option>').globalDropList("reload").globalDropList("disabled");
			action_compute();
		}

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

	function action_date_picker()
	{/*class name of tbody and text field for date*/
		$(".draggable .for-datepicker").datepicker({ dateFormat: 'yy-mm-dd', });
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
	
	function event_click_last_row()
	{
		$(document).on("click", "tbody.draggable tr:last td:not(.remove-tr)", function(){
			event_click_last_row_op();
		});
	}

	/*INSERTING ANOTHER ROW WHEN CLICKING LAST ROW*/
	function event_click_last_row_op()
	{
		$("tbody.draggable").append(global_tr_html);
		event_taxable_check_change();
		action_reassign_number();
		action_load_initialize_select();
		action_date_picker();
	}

	/*REMOVING ROW*/
	function event_remove_tr()
	{
		// $(document).on("click", ".remove-tr", function(e){
		// 	var len = $(".tbody-item .remove-tr").length;

		// 	if($(".tbody-item .remove-tr").length > 1)
		// 	{
		// 		$(this).parent().remove();
		// 		action_reassign_number();
		// 	}
		// 	else
		// 	{
		// 		console.log("success");
		// 	}
		// });
		$(document).on("click", ".remove-tr", function(e){
			if($(".tbody-item .remove-tr").length > 1){

				$(this).parent().remove();
				action_reassign_number();
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
		$('.applied-transaction-list').load('/member/transaction/purchase_order/load-applied-transaction', function()
		{
			console.log("success");
			action_load_initialize_select();
			action_compute();
			action_reassign_number();

			$('.remarks-po').html($('.so-remarks').val());
			$('.tax-po').val($('.so-tax').val());
			
			action_compute();
			$(".loading-tbody").addClass("hidden");
		});
	}
	this.load_applied_transaction = function()
	{
		load_applied_transaction();
	}
}
/*AFTER ADDING barcode*/
function adding_barcode(data)
{
	if(data.status == 'success')
	{
		toastr.success(data.message);
	}
	else
	{
		toastr.error(data.message);
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
    item_selected.load("/member/item/load_item_category", function()
    {
        $(this).globalDropList("reload");
		$(this).val(data.item_id).change();
    });
    data.element.modal("hide");
}

function success_purchase_order(data)
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
		purchase_order.load_applied_transaction();
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