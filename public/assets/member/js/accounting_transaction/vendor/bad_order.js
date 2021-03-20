var bad_order = new bad_order();
var global_tr_html = $(".div-script tbody").html();
var item_selected = ''; 

function bad_order()
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
			}
		});
/*
		$('.droplist-terms').globalDropList(
		{ 	
			width : "100%",
			link : "/member/maintenance/terms/terms",
			placeholder : "Select Term...",
			onChangeValue: function()
			{
				var start_date 		= $(".datepicker[name='po_date']").val();
            	var days 			= $(this).find("option:selected").attr("days");
            	var new_due_date 	= AddDaysToDate(start_date, days, "/");
            	$(".datepicker[name='po_due_date']").val(new_due_date);
			}
		});*/

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

	function action_compute()
	{
		var subtotal = 0;
		var total_taxable = 0;

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
			discount = 0;

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

		discount_total = discount_txt;

		if(discount_selection == 'percent')
		{
			discount_total = subtotal * (discount_txt / 100);
			taxable_discount = total_taxable * (discount_txt / 100);
		}

		discount_total = parseFloat(discount_total);

		/* action_compute TOTAL */
		var total = 0;
		total     = subtotal - discount_total - ewt_value;

		/* action_compute TAX */
		var tax   = 0;
		if(tax_selection == 1){
			tax = total_taxable * (12 / 100);
		}
		total += tax;

		/* action payment applied */
		var payment_applied   	= parseFloat($(".payment-applied").html());
		// console.log(total + "|" + payment_applied);
		var balance_due 		= total - payment_applied;

		$(".sub-total").html(action_add_comma(subtotal.toFixed(2)));
		$(".subtotal-amount-input").val(action_add_comma(subtotal.toFixed(2)));

		$(".ewt-total").html(action_add_comma(ewt_value.toFixed(2)));
		$(".discount-total").html(action_add_comma(discount_total.toFixed(2)));
		$(".tax-total").html(action_add_comma(tax.toFixed(2)));

		$(".total-amount").html(action_add_comma(subtotal.toFixed(2)));
		$(".total-amount-input").val(subtotal.toFixed(2));

		$(".balance-due").html(balance_due.toFixed(2));

	}

	function action_load_unit_measurement($this)
	{
		$parent = $this.closest(".tr-draggable");
		$item   = $this.closest(".tr-draggable").find(".select-item");

		$um_qty = parseFloat($this.find("option:selected").attr("qty") || 1);
		$sales  = parseFloat($item.find("option:selected").attr("price"));
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

	function action_load_item_info($this)
	{
		$parent = $this.closest(".tr-draggable");
		// $parent.find(".txt-desc").html($this.find("option:selected").attr("purchase-info")).change();
		$parent.find(".txt-rate").val($this.find("option:selected").attr("cost")).change();
		$parent.find(".txt-qty").val(1).change();
		console.log($this.find("option:selected").attr("item-type"));
		

		if($this.find("option:selected").attr("has-um"))
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
			$parent.find(".select-um").html('<option class="hidden" qty="1" value=""></option>').globalDropList("reload").globalDropList("disabled");
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
		action_reassign_number();
		action_load_initialize_select();
		action_date_picker();
	}

	/*REMOVING ROW*/
	function event_remove_tr()
	{
		$(document).on("click", ".remove-tr", function(e){
			var len = $(".tbody-item .remove-tr").length;
			if($(".tbody-item .remove-tr").length > 1)
			{
				$(this).parent().remove();
				action_reassign_number();
			}
			else
			{
				console.log("success");
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

