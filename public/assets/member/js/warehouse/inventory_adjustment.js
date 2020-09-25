var inventory_adjustment = new inventory_adjustment();
var global_tr_html = $(".div-script tbody").html();
var item_selected = ''; 
var bin_selected = '';
var item_inventory = null;
var warehouse_selected = null;
function inventory_adjustment()
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
		event_keypress_enter();
		event_keypress_enter_OFF_barcode();
	} 
	function action_load_item_with_inventory(warehouse_id, item_id, $parent)
	{
		$parent.find(".txt-qty").attr("disabled","disabled");
		if(warehouse_id)
		{
			$(".item-inventory-loading").removeClass("hidden");
			$.ajax(
			{
				url: '/member/item/warehouse/inventory_adjustment/item-inventory?w='+warehouse_id+'&item_id='+item_id,
				method: 'get',
				success: function(data)
				{
					$parent.find(".txt-qty").removeAttr("disabled");
					// item_inventory = JSON.parse(data);
					$(".item-inventory-loading").addClass("hidden");
					$parent.find(".txt-actual-qty").val(data).change();
				}
			})
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
		    	console.log(123);
		    	$txtparent = $(this).parent().parent();
		    	$next_parent = $txtparent.next('.tr-draggable');
		    	$next_parent.find('.item-textbox').removeClass("hidden");
		    	$next_parent.find('td.item-select-td .input-group').addClass("hidden");
		    	$next_parent.find('.item-textbox').focus();
		    	event_click_last_row_op();
		    }
		});		
	}
	function action_load_initialize_select()
	{

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
        $('.droplist-warehouse').globalDropList(
        {
            width : "100%",
    		hasPopup: "false",
    		onChangeValue : function()
    		{
    			action_load_item_inventory_warehouse($(this));
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


	function action_load_item_info($this)
	{
		if($(".droplist-warehouse").val())
		{
			$parent = $this.closest(".tr-draggable");
			$parent.find(".txt-desc").html($this.find("option:selected").attr("purchase-info")).change();
			$parent.find(".txt-rate").val($this.find("option:selected").attr("cost")).change();
			$qty = $this.find("option:selected").attr("warehouse_"+$(".droplist-warehouse").val());
			action_load_item_with_inventory($(".droplist-warehouse").val(), $this.val(), $parent);

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
							$parent.find('.txt-qty').focus();
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
		else
		{
			$this.globalDropList("reload");
			alert("Please specify warehouse to adjust inventory");
		}
	}

	function action_load_item_inventory_warehouse($this)
	{
		if(!$this.val())
		{
			alert("Please specify warehouse to adjust inventory");
		}
		else
		{
			// $(".tr-draggable").each(function()
			// {
			// 	if($(this).find(".select-item").val())
			// 	{
			// 		action_load_item_info($(this).find(".select-item"));
			// 	}
			// });
			warehouse_selected = $this.val();
		}
	}
	function action_compute()
	{
		var subtotal = 0;
		var total_taxable = 0;

		$(".tr-draggable").each(function()
		{
			/* GET ALL DATA */
			var qty 			= $(this).find(".txt-actual-qty").val();
			var rate 			= $(this).find(".txt-rate").val();
			var amount 			= $(this).find(".txt-amount");
			var new_quantity	= $(this).find(".txt-qty").val();
			var difference 		= $(this).find(".txt-difference");
			var total_amount 	= $(this).find(".txt-total-amount");
			
			/* CHECK IF QUANTITY IS EMPTY */

			/* RETURN TO NUMBER IF THERE IS COMMA */
			rate = action_return_to_number(rate);
			new_quantity = action_return_to_number(new_quantity);

			/*ENTERED NEW QUANTITY*/
			// if (new_quantity == "" || new_quantity == null)
			// {
			// 	var total_per_tr = ((qty * rate)).toFixed(2);
			// }
			// else
			// {
				var total_per_tr = ((new_quantity * rate)).toFixed(2);

				//var diff = qty - new_quantity;
				var diff = new_quantity - qty;
				difference.val(action_add_comma(diff));
			// }
			

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

		});
		

		$(".total-amount").html(action_add_comma(subtotal.toFixed(2)));
		$(".total-amount-input").val(action_add_comma(subtotal.toFixed(2)));
	}

	function action_load_unit_measurement($this)
	{
		$parent = $this.closest(".tr-draggable");
		$item   = $this.closest(".tr-draggable").find(".select-item");

		$um_qty = parseFloat($this.find("option:selected").attr("qty") || 1);
		$sales  = parseFloat($item.find("option:selected").attr("price"));
		$qty    = parseFloat($parent.find(".txt-actual-qty").val());
		$parent.find(".txt-rate").val( $um_qty * $sales * $qty ).change();

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
			return 0;
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
	// function action_load_item_info($this)
	// {
	// 	$parent = $this.closest(".tr-draggable");
	// 	$parent.find(".txt-desc").html($this.find("option:selected").attr("purchase-info")).change();
	// 	$parent.find(".txt-rate").val($this.find("option:selected").attr("cost")).change();
	// 	$qty = $this.find("option:selected").attr("warehouse_"+$(".droplist-warehouse").val());

	// 	$parent.find(".txt-actual-qty").val($qty).change();
		
	// 	if($this.find("option:selected").attr("has-um") != 0)
	// 	{
	// 		$.ajax(
	// 		{
	// 			url: '/member/item/load_one_um/' +$this.find("option:selected").attr("has-um"),
	// 			method: 'get',
	// 			success: function(data)
	// 			{
	// 				$parent.find(".select-um").load('/member/item/load_one_um/' +$this.find("option:selected").attr("has-um"), function()
	// 				{
	// 					$(this).globalDropList("reload").globalDropList("enabled");
	// 					$(this).val($(this).find("option:first").val()).change();
	// 				})
	// 			},
	// 			error: function(e)
	// 			{
	// 				console.log(e.error());
	// 			}
	// 		})
	// 	}
	// 	else
	// 	{
	// 		$parent.find(".select-um").html('<option class="hidden" qty="1" value=""></option>').globalDropList("reload").globalDropList("disabled");
	// 	}

	// 	action_compute();
	// }
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
		$(".draggable .for-datepicker").datepicker({ dateFormat: 'mm/dd/yy', });
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
			    if(!$(this).hasClass("txt-actual-qty")){
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

function success_adjust_inventory(data)
{
	if(data.status == 'success')
	{
		toastr.success(data.status_message);
		location.href = data.status_redirect;
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