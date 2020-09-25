var pos = new pos()
var load_item = null;
var load_customer = null;
var item_search_delay_timer;
var settings_delay_timer;
var keysearch = {};
var keysearch_customer = {};

var success_audio = new Audio('/assets/sounds/success.mp3');
var error_audio = new Audio('/assets/sounds/error.mp3');

function pos()
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
		action_load_item_table();
		event_search_item();
		event_search_customer();
		event_click_search_result();
		event_remote_item_from_cart();
		action_convert_price_level_to_global_drop_list();
		event_change_global_discount();
		action_load_detach_customer();
		event_click_process_sale();
		action_hide_popover();
		event_change_quantity();
		event_click_add_payment();
		event_click_remove_payment();
		event_change_slot_id();

        event_load_popover();
        action_click_change_qty();
	}
	function event_change_slot_id()
	{
		$('body').on('change','.change-slot-id', function(e)
		{
			$('.input-slot-id').val($(e.currentTarget).val());
		});
	}
	function event_click_add_payment()
	{
		$('body').on('click','.btn-add-payment', function(e)
		{
			var payment_method = $('.input-payment-method').val();
			var payment_amount = $('.input-payment-amount').val();
			var _token = $('#_token').val();
			payment_loading();
			$.ajax(
			{
				url:"/member/cashier/pos/add_payment",
				dataType:"json",
				data: {payment_method:payment_method,payment_amount:payment_amount,_token:_token},
				type:"post",
				success: function(data)
				{
					if(data.status == 'success')
					{
						action_load_payment_table();
					}
					else
					{
						toastr.warning(data.status_message);
						action_load_payment_table();
					}
				}
			});
		});
	}
	function event_click_remove_payment()
	{
		$('body').on('click','.remove-payment', function(e)
		{
			payment_loading();
			$.ajax(
			{
				url:"/member/cashier/pos/remove_payment",
				dataType:"json",
				data: {cart_payment_id : $(e.currentTarget).attr('payment-id')},
				type:"get",
				success: function(data)
				{
						action_load_payment_table();
				}
			});
		});
	}
	function action_click_change_qty()
    {
        $('body').on('click','.td-change-qty', function(e)
        {
            var item_id = $(e.currentTarget).attr('item-id');
            $('.input-qty-'+item_id).removeClass('hidden');
            $('.change-quantity.'+item_id).addClass('hidden');
        });
    }
    function event_load_popover()
    {
        $('[data-toggle="popover"]').popover(
        {
            placement: 'top',
            title: 'Change Quantity',
            html: true,
            content:  $('#changeQuantity').html()
        }).on('click', getDetails());
    }
    function getDetails()
    {

    }
	function action_hide_popover()
	{
	    $('body').on('click', function (e) 
	    {
	        //did not click a popover toggle or popover
	        if ($(e.target).data('toggle') !== 'popover' && $(e.target).parents('.popover.in').length === 0) 
	        { 
	            $('[data-toggle="popover"]').popover('hide');
	        }
	    });
    }
	function event_click_process_sale()
	{
		$('body').on('click','.btn-process-sale', function()
		{
			$('.form-process-sale').submit();
		});
	}
	function action_load_detach_customer()
	{
		$("body").on('click', '.detach-customer', function()
		{
			customer_loading();
			$.ajax({
				url : '/member/cashier/pos/remove_customer',
				data : {},
				type : 'get',
				success : function (data)
				{
					action_load_customer_info();
					action_load_item_table();
				}
			});
		});
	}
	function payment_loading()
	{
		$(".pos-payment").css("opacity", 0.3);
	}
	function table_loading()
	{
		$(".load-item-table-pos").css("opacity", 0.3);
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
	function action_convert_price_level_to_global_drop_list()
	{
		$(".price-level-select").globalDropList(
		{  
			hasPopup                : "true",
			link                    : "/member/item/price_level/add",
			link_size               : "lg",
			width                   : "100%",
			maxHeight				: "129px",
			placeholder             : "Select a Price Level",
			no_result_message       : "No result found!",
			onChangeValue			: function()
			{
				action_set_cart_info("price_level_id", $(".price-level-select").val());
			}
		});
		$('.payment-method-select').globalDropList({
			hasPopup : "false",
			width    : '100%'		
		});

		$('.select-warehouse').globalDropList({
	        hasPopup : 'false',
	        width    : '100%'
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
				url:"/member/cashier/pos/remove_item",
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
	function event_change_quantity()
	{
		$("body").on("keyup", ".input-change-qty", function(e)
		{
			var qty_item_id = $(e.currentTarget).attr('item-id');
			var qty = $(e.currentTarget).val();
			if(e.which == 13) //ENTER KEY
			{
				$.ajax({
					url : '/member/cashier/pos/change_qty',
					type : 'post',
					data : {item_id : qty_item_id, qty : qty, _token : $('#_token').val()},
					success : function(data)
					{
						action_load_item_table();
						if(data.status == 'error')
						{
							toastr.warning(data.status_message);
						}
					}
				})
			}
		});
	}
	function event_search_customer()
	{
		$("body").on("keyup", ".event_search_customer", function(e)
		{
			if(e.which == 13) //ENTER KEY
			{
				action_scan_customer($(".event_search_customer").val());
				action_hide_search();
			}
			else if(e.which == 38) //UP KEY
			{
				event_search_customer_cursor_next(true);
			}
			else if(e.which == 40) //DOWN KEY
			{
				event_search_customer_cursor_next();
			}
			else /* SEARCH MODE */
			{
				if($(".event_search_customer").val() == "")
				{
					action_hide_search();
				}
				else
				{
					keysearch_customer.customer_keyword = $(".event_search_customer").val();
					keysearch_customer._token = $(".token").val();
					if(load_customer)
					{
						load_customer.abort();
					}

					clearTimeout(item_search_delay_timer);

				    item_search_delay_timer = setTimeout(function()
				    {
				       $(".pos-search-container-customer").html(get_loader_html(10)).show();
				       action_ajax_search_customer();
				    }, 500);
				}
			}
		});
	}
	function action_scan_customer($customer_id)
	{
		$(".event_search_customer").val("");
		$(".event_search_customer").attr("disabled", "disabled");
		$(".customer-button-scan").find(".customer-scan-load").show();
		$(".customer-button-scan").find(".customer-scan-icon").hide();

		scandata_customer = {};
		scandata_customer.customer_id = $customer_id;
		scandata_customer._token = $(".token").val();

 		$.ajax(
		{
			url			: "/member/cashier/pos/scan_customer",
			dataType	: "json",
			type 		: "post",
			data 		: scandata_customer,
			success 	: function(data)
			{
				$(".event_search_customer").removeAttr("disabled");
				$(".customer-button-scan").find(".customer-scan-load").hide();
				$(".customer-button-scan").find(".customer-scan-icon").show();

				if(data.status == "success")
				{
					success_audio.play();
					action_load_customer_info(data.price_level_id, data.stockist_warehouse_id, data.reserved_item);
				}
				else if(data.status == "error")
				{
					toastr.error("<b>ERROR!</b><br>" + data.message);
					error_audio.play();
				}
			},
			error : function(data)
			{
				$(".event_search_customer").removeAttr("disabled");
				$(".customer-button-scan").find(".customer-scan-load").hide();
				$(".customer-button-scan").find(".customer-scan-icon").show();
				toastr.error("An error occured during scan - please contact system administrator");
				$(".event_search_customer").focus();
			}
		});
	}
	function event_search_item()
	{
		$(".event_search_item").keyup(function(e)
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

	function event_search_customer_cursor_next(reverse = false)
	{
		var current_cursor = $(".pos-customer-search-result.cursor");

		if(current_cursor.length < 1)
		{
			$(".pos-customer-search-result:first").addClass("cursor");
		}
		else
		{
			if(reverse == true)
			{
				$(".pos-customer-search-result.cursor").prev(".pos-customer-search-result").addClass("cursor");
			}
			else
			{
				$(".pos-customer-search-result.cursor").next(".pos-customer-search-result").addClass("cursor");
			}
			
			current_cursor.removeClass("cursor");
		}

		$active_item_id = $(".pos-customer-search-result.cursor").attr("customer_id");
		$(".event_search_customer").val($active_item_id);
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
		$("body").on("click", ".pos-customer-search-result", function(e)
		{
			$customer_id = $(e.currentTarget).attr("customer_id");
			action_scan_customer($customer_id);
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
			url			: "/member/cashier/pos/scan_item",
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
	function action_ajax_search_customer()
	{
		load_customer = $.ajax(
		{
			url:"/member/cashier/pos/search_customer",
			type:"post",
			data: keysearch_customer,
			success: function(data)
			{
				$(".pos-search-container-customer").html(data);
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
		$(".pos-search-container-customer").hide();
		clearTimeout(item_search_delay_timer);
	}
	function action_load_customer_info(price_level_id = '', stockist_warehouse_id = '', reserve_item = 0)
	{
		if($(".customer-container").text() != "")
		{
			customer_loading();
		}
		else
		{
			$(".customer-container").html(get_loader_html());
		}
		
		$(".customer-container").load("/member/cashier/pos/customer", function()
		{
			$(".customer-container").css("opacity", 1);

			if(price_level_id)
			{
				$('.price-level-select').val(price_level_id).change();
			}
			if(reserve_item)
			{
				action_load_item_table();
			}
			if(stockist_warehouse_id)
			{
				// $('.select-warehouse').val(stockist_warehouse_id).change();
				// $('.select-warehouse').attr('readonly',true);
				load_warehouse_destination(stockist_warehouse_id);
			}
			$('.input-slot-id').val($('.change-slot-id').val());
		});
	}
	function load_warehouse_destination(stockist_warehouse_id = null)
	{
		if(stockist_warehouse_id)
		{
			$(".select-warehouse").load("/member/cashier/pos/load_warehouse?w_id="+stockist_warehouse_id, function()
	        {                
	             $(".select-warehouse").globalDropList("reload"); 
	             $(".select-warehouse").val(stockist_warehouse_id).change();              
	        });
		}
	}
	function customer_loading()
	{
		$(".customer-container").css("opacity", 0.3);
	}
	function action_load_payment_table()
	{
		if($(".pos-payment").text() != "")
		{
			payment_loading();
		}
		
		$(".pos-payment").load("/member/cashier/pos/load_payment", function()
		{
			action_update_big_totals();
			$(".pos-payment").css("opacity", 1);
		});
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
		
		$(".load-item-table-pos").load("/member/cashier/pos/table_item", function()
		{
			action_update_big_totals();
			$(".load-item-table-pos").css("opacity", 1);
		});
	}
	function action_update_big_totals()
	{
		$(".big-total").find(".grand-total").text($(".table-grand-total").val());
		var payment_amount = 0;
		var amount_due_php = $(".table-amount-due").val();
		var amount_due = parseFloat($(".table-amount-due").val().replace('PHP','').replace(',',''));
		$('.payment-li').each(function()
		{
			payment_amount += parseFloat($(this).find(".compute-payment-amount").val());
		});
		$(".big-total").find(".amount-due").text('PHP ' +(amount_due - payment_amount).toFixed(2));
		$(".input-payment-amount").val(amount_due - payment_amount);
	}
	function get_loader_html($padding = 50)
	{
		return '<div style="padding: ' + $padding + 'px; font-size: 20px;" class="text-center"><i class="fa fa-spinner fa-pulse fa-fw"></i></div>';
	}
}

function toggle_destination(className) 
{
    if($('.wis-click').prop('checked'))
    {
    	$(className).slideDown();
    }
    else 
    {
    	$(className).slideUp();
    }
}
function select_payment(type = '')
{
	$('.btn-payment').addClass('btn-custom-white');
	$('.input-payment-method').val(type);
	$('.'+type).removeClass('btn-custom-white');
	$('.'+type).addClass('btn-primary');
}

function new_price_level_save_done(data)
{
	$("#global_modal").modal("hide");
	$(".price-level-select").append('<option value="' + data.price_level_id + '">' + data.price_level_name + '</option>');
	$(".price-level-select").globalDropList("reload");
	$(".price-level-select").val(data.price_level_id).change();
}
function success_process_sale(data)
{
	if(data.status == 'success')
	{
		toastr.success('Success Process Sales');
		// setInterval(function()
		// {
		location.href = '/member/cashier/transactions_list?receipt_id='+data.receipt_id;
		// },2000);
	}	
}