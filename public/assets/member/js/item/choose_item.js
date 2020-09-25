var choose_item = new choose_item();
function choose_item()
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
		action_initialize_select();
		action_load_add_click();
	}
	function action_load_add_click()
	{
		$('.add-product-btn').unbind('click');
		$('.add-product-btn').bind('click', function()
		{
			$(this).html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
		});
	}
	function action_initialize_select()
	{
		$(".select-item").globalDropList(
		{
			width       : '100%',
			hasPopup 	: 'false',
            placeholder : 'Enter Product Keyword',
            onChangeValue : function()
            {            	
            	$('.add-product-btn').attr('disabled');
            	if($(this).val())
            	{
            		$('.add-product-btn').removeAttr('disabled');
            	}
            }
		});
	}
}