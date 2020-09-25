var item_add = new item_add();
var code_generate_delay;
var original_item_code;
var original_item;


function item_add()
{
	init();

	function init()
	{
		add_event_auto_generate_code();
		add_event_select_item_type();
		add_event_change_type();
		add_action_initialize_select();
		add_event_click_save();
		add_event_change_price();
		add_action_load_type_id();
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
	function add_action_load_type_id()
	{
		if($(".type-id").val())
		{
			action_show_depending_on_type($(".type-id").val());
		}
	}
	function add_event_change_price()
	{
		$("body").on("change",".item-price-txt", function()
		{
			$val = action_add_comma(action_return_to_number($(this).val()).toFixed(2));
			console.log($val);
			$(this).val($val);
		});
		$("body").on("change",".item-cost-txt", function()
		{
			$valcost = action_add_comma(action_return_to_number($(this).val()).toFixed(2));
			console.log($valcost);
			$(this).val($valcost);
		});
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
	function add_event_click_save()
	{
		$('.add-submit-button').unbind("click");
		$('.add-submit-button').bind("click", function()
		{
			$('.remove-this-type').remove();
			$('#form_submit').submit();
		});
	}
	function add_event_auto_generate_code()
	{
		$(".item-description").focus(function(e)
		{
			original = $(e.currentTarget).val();
			original_item = original.toUpperCase();
			original_item_code = action_covert_to_code(original);
			
		});

		$(".item-description").keyup(function(e)
		{
			if($(".shop-id").val() != 81)
			{
				clearTimeout(code_generate_delay);

				$item_description = $(e.currentTarget).val();

				$code_generate = action_covert_to_code($item_description);

				$(".auto-generate-code").each(function(key)
				{
					// //comment 10/04/2018
					// if($(this).val() == original_item_code)
					// {
					// 	$(this).val($code_generate);
					// }
					$(this).val($code_generate);
				});
				$(".auto-generate").each(function(key)
				{
					$(this).val($item_description);
				});

				original_item = $item_description;
				original_item_code = $code_generate;				
			}
		});
	}
	function action_covert_to_code($string)
	{
		$string = $string.replace(/\s+/g, '-').toUpperCase();
		$string = $string.replace(/\W/g, '');
		return $string;
	}
	function add_action_initialize_select()
	{
		$('.select-category').globalDropList({
			width       : '100%',
            link        : '/member/item/category/modal_create_category?type=',
            link_size   : 'md',
            placeholder : 'Select Category'
		});
		$('.select-um').globalDropList({
			width       : '100%',
            hasPopup    : 'false',
            link_size   : 'md',
            placeholder : 'Select U/M'
		});
		$('.select-manufacturer').globalDropList({
			width       : '100%',
            link        : '/member/item/manufacturer/add',
            link_size   : 'md',
            placeholder : 'Select Manufacturer'
		});
		$('.select-vendor').globalDropList({
			width       : '100%',
            link        : '/member/vendor/add',
            placeholder : 'Select Vendor'
		});
		$('.select-income-account').globalDropList({
			width       : '100%',
            link        : '/member/accounting/chart_of_account/popup/add',
            link_size   : 'md',
            onCreateNew : function()
            {
                account_income = $(this);
            }
		});
		$('.select-expense-account').globalDropList({
			width       : '100%',
            link        : '/member/accounting/chart_of_account/popup/add',
            link_size   : 'md',
            onCreateNew : function()
            {
                account_expense = $(this);
            }
		});
		$('.select-asset-account').globalDropList({
			width       : '100%',
            link        : '/member/accounting/chart_of_account/popup/add',
            link_size   : 'md',
            onCreateNew : function()
            {
                account_asset = $(this);
            }
		});
		$('.select-has-serial').globalDropList({
			width       : '100%',
			hasPopup	: 'false'
		}).val(0).change();
	}
	function add_event_select_item_type()
	{
		$(".tp-picker").click(function(e)
		{
			$type_id = $(e.currentTarget).attr("type_id");
			$type_name = $(e.currentTarget).find(".tp-title").text();

			$(".change-type").find("input").val($type_name);
			$(".item-type .orig-item-type-id").val($type_id);
			$(".item-type-picker").hide();
			$(".modal-footer").find("button").removeAttr("disabled");
			action_show_depending_on_type($type_id);
		});
	}
	function action_show_depending_on_type($type_id)
	{
		$(".item-bundle").hide();
		$(".item-add-main").hide();
		$(".for-membership-kit").hide();
		$(".for-inventory").hide();
		$(".for-non-service").hide();
		$(".expense-account").removeClass("col-md-12");
		$(".expense-account").addClass("col-md-6");

		$(".item-add-main").addClass('remove-this-type');
		$(".item-bundle").addClass('remove-this-type');
		if($type_id == 1)
		{
			$(".item-add-main").fadeIn();
			$(".for-inventory").fadeIn();
			$(".for-non-service").fadeIn();
			$(".item-add-main").removeClass('remove-this-type');
		}
		else if($type_id == 2)
		{
			$(".item-add-main").fadeIn();
			$(".for-non-service").fadeIn();
			$(".item-add-main").removeClass('remove-this-type');
		}
		else if($type_id == 3)
		{
			$(".item-add-main").fadeIn();
			$(".expense-account").removeClass("col-md-6");
			$(".expense-account").addClass("col-md-12");
			$(".item-add-main").removeClass('remove-this-type');
		}
		else if($type_id == 4)
		{
			$(".item-bundle").fadeIn();
			$(".item-bundle").removeClass('remove-this-type');
		}
		else if($type_id == 5)
		{
			$(".item-bundle").fadeIn();	
			$(".for-membership-kit").fadeIn();
			$(".item-bundle").removeClass('remove-this-type');
		}	
		else if($type_id == 6)
		{
			$(".item-add-main").fadeIn();
			$(".expense-account").removeClass("col-md-6");
			$(".expense-account").addClass("col-md-12");
			$(".sales-module-account").addClass("hidden");
			$(".expense-module-account").removeClass("col-md-6");
			$(".expense-module-account").addClass("col-md-12");
			$(".item-i-n-s-b").addClass("hidden");
			$(".purchase-info").addClass("hidden");
			$(".item-add-main").removeClass('remove-this-type');
		}	
	}
	function add_event_change_type()
	{
		$(".change-type").click(function(e)
		{
			$(".item-type-picker").fadeIn();
			$(".item-add-main").hide();
			$(".item-bundle").hide();
			$(".modal-footer").find("button").attr("disabled", "disabled");
		});
	}
}
function remove_item(id)
{
	$('.choose-item-list').html('<tr><td class="text-center" colspan="5"><i class="fa fa-spinner fa-pulse fa-fw fa-3x"></i></td></tr>');
	$.ajax({
		url : '/member/item/choose/remove_item',
		data : {item_id : id},
		type : 'get',
		success : function()
		{
	        $('.choose-item-list').load('/member/item/choose/load_item', function()
	        {
	        	console.log('success');
	        });
		}
	});
}

function success_landing_cost(data)
{
	if(data.status == 'success')
	{
		$(".modal-loader").addClass("hidden");
        data.element.modal("hide");
		$(".item-cost").val(data.total_amount).change();
	}
}
function success_choose_item(data)
{
	if(data.status == 'success')
	{
        data.element.modal("hide");
        $('.choose-item-list').html('<tr><td class="text-center" colspan="5"><i class="fa fa-spinner fa-pulse fa-fw fa-3x"></i></td></tr>');
        $('.choose-item-list').load('/member/item/choose/load_item', function()
        {
        	console.log('success');
        });
	}
}
function submit_done(data)
{
	if(data.type == "category")
    {
        toastr.success("Success");
        $(".select-category.inventory").load("/member/item/load_category?type=", function()
        {                
             $(".select-category").globalDropList("reload");
             $(".select-category").val(data.id).change();              
        });
        data.element.modal("hide");
    }
    else if(data.type == "manufacturer")
    {
        toastr.success("Success");
        $(".select-manufacturer").load("/member/item/manufacturer/load_manufacturer", function()
        {                
             $(".select-manufacturer").globalDropList("reload"); 
             $(".select-manufacturer").val(data.id).change();              
        });
        data.element.modal("hide");
    }
    else if(data.type == "vendor")
    {
        toastr.success("Success");
        $(".select-vendor").load("/member/vendor/load_vendor", function()
        {                
             $(".select-vendor").globalDropList("reload"); 
             $(".select-vendor").val(data.id).change();              
        });
        data.element.modal("hide");
    }
}
function submit_selected_image_done(data) 
{ 
    var image_path = data.image_data[0].image_path;

    if (data.akey == 1) 
    {
        $('input[name="item_img"]').val(image_path);
        $('.image-put').attr("src", image_path);
    }
}
function success_update_bulk_item(data)
{
	if(data.status == 'success')
	{
		toastr.success(data.message);
		data.element.modal("toggle");
		location.reload();
	}
	else
	{
		toastr.error(data.message);
	}
}