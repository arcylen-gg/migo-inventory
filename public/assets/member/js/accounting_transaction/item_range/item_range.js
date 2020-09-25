var item_range = new item_range();
var global_tr_html = $(".div-script tbody").html();
var item_selected = ''; 
var bin_selected = ''; 
var load_table_data = {};

function item_range()
{
	init();

	function init()
	{
		action_load_item_range();
		initialize_search_item();
		
	}
	function action_filter_search(self)
	{
		var search = $(self).val();
		if(search)
		{
			load_table_data.search_item_id = search;
		    load_table_data.page = 1;
		    action_load_item_range();			
		}
	}
	function add_event_pagination()
	{
		$("body").on("click", ".pagination a", function(e)
		{
			$url = $(e.currentTarget).attr("href");
			var url = new URL($url);
			$page = url.searchParams.get("page");
			load_table_data.page = $page;
			action_load_item_range();
			return false;
		});
	}
	function action_load_item_range()
	{		
		if($(".load-item-table").text() == "")
		{
			$(".load-item-table").html(get_loader_html(100));
		}
		else
		{
			$(".load-item-table").css("opacity", 0.3);
		}
		$.ajax(
		{
			url:"/member/utilities/item_range_sales_discount/load-item-range",
			data: load_table_data,
			type: "get",
			success: function(data)
			{
				$(".load-item-table").html(data);
				$(".load-item-table").css("opacity", 1);
				add_event_pagination();

				action_load_initialize_select();
				action_date_picker();
				action_reassign_number();

				event_remove_tr();
				event_accept_number_only();
				event_click_last_row();
			}
		});
	}
	function initialize_search_item()
	{
		$('.search-select-item').globalDropList(
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
            	action_filter_search($(this));
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
        // .globalDropList('disabled')
	}
	function action_load_unit_measurement($this)
	{
		$parent = $this.closest(".tr-draggable");
		$item   = $this.closest(".tr-draggable").find(".select-item");

		$um_qty = parseFloat($this.find("option:selected").attr("qty") || 1);
		$sales  = parseFloat($item.find("option:selected").attr("price"));
		$qty    = parseFloat($parent.find(".txt-qty").val());

		$parent.find(".txt-rate").val( (parseFloat($um_qty * $sales)).toFixed(2)).change();

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

	function convert_to_dec($string)
	{
		$amount = action_return_to_number($string);
		return action_add_comma($amount.toFixed(2));
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
		$parentitem = $this.closest(".tr-draggable");
		$parentitem.find(".txt-desc").html($this.find("option:selected").attr("purchase-info")).change();
		$item_cost = parseFloat($this.find("option:selected").attr("price"));
		$parentitem.find(".txt-rate").val(action_add_comma($item_cost.toFixed(2))).change();
		console.log(action_add_comma($item_cost.toFixed(2)));
		$parentitem.find(".txt-qty").val(1).change();
		// if($this.find("option:selected").attr("has-um") && $this.find("option:selected").attr("has-um") != 0)
		// {
		// 	$parentitem.find(".txt-qty").attr("disabled",true);
		// 	$.ajax(
		// 	{
		// 		url: '/member/item/load_one_um/' +$this.find("option:selected").attr("has-um"),
		// 		method: 'get',
		// 		success: function(data)
		// 		{
		// 			$parentitem.find(".select-um").load('/member/item/load_one_um/' +$this.find("option:selected").attr("has-um"), function()
		// 			{
		// 				$parentitem.find(".txt-qty").removeAttr("disabled");
		// 				$(this).globalDropList("reload").globalDropList("enabled");
		// 				$parent.find('.txt-qty').focus();
		// 				$(this).val($(this).find("option:first").val()).change();
		// 				$ave_cost_per_warehouse = $this.find("option:selected").attr("warehouse_"+$(".current-warehouse").val());	
		// 				$parentitem.find(".txt-rate").val($ave_cost_per_warehouse).change();
		// 			})
		// 		},
		// 		error: function(e)
		// 		{
		// 			console.log(e.error());
		// 		}
		// 	})
		// }
		// else
		// {
			$parentitem.find(".select-um").html('<option class="hidden" qty="1" value=""></option>').globalDropList("reload").globalDropList("disabled");
		// }

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
		console.log(global_tr_html);
		$("tbody.draggable").append(global_tr_html);
		action_reassign_number();
		action_load_initialize_select();
		action_date_picker();
	}

	/*REMOVING ROW*/
	function event_remove_tr()
	{
		//cycy
		$(document).on("click", ".remove-tr", function(e){
			if($(".tbody-item .remove-tr").length > 1){

				$(this).parent().remove();
				action_reassign_number();
			}			
		});
	}

	function event_accept_number_only()//
	{
		$("body").on("change",".number-input", function()
		{
			$(this).val(function(index, value) 
			{		 
			    var ret = '';
			    value = action_return_to_number(value);
			    if(!$(this).hasClass("txt-qty"))
			    {
			    	value = parseFloat(value);
			    	value = value.toFixed(2);
			    	ret = action_add_comma(value);
			    }
			    else if(value != '' && !isNaN(value))
			    {
			    	value = parseFloat(value);
			    	ret = action_add_comma(value);
			    }
			    if(ret == 0)
			    {
			    	ret = '';
			    }
			    
				return ret;
			  });
		});
	}
	this.action_load_item_range = function()
	{
		action_load_item_range();
	}
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

function success_item_range(data)
{

	if(data.status == "success")
	{
        toastr.success("Success");
       	// location.href = location.reload;	
       	// location.reload();	
       	item_range.action_load_item_range();
	}
    else if(data.status == "error")
    {
        toastr.warning(data.status_message);
        $(data.target).html(data.view);
    }
}
