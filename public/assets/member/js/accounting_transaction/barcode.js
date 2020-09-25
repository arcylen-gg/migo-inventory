function event_search($this, event)
{
/*	console.log(event.keyCode);*/
	$parent = $this.closest(".tr-draggable");
    if(event.keyCode == 13) 
    {
    	$parent.find('.item-textbox').addClass("hidden");
        action_search_item($this.val(), $parent, function(res)
    	{
    		if(res == 0)
    		{
    			$parent.find('.item-textbox').removeClass("hidden");
				$parent.find(".item-textbox").focus();
				toastr.warning("No Item Found");
				$parent.find(".item-textbox").select();
    		}
    	});
    }
}

function action_search_item($item_id = 0, $parent = '', callback)
{
	if($item_id && $parent)
	{	
		// $.ajax({
		// 	url : '/member/transaction/purchase_order/search-item',
		// 	type : 'get',
		// 	dataType : 'json',
		// 	data : {item_id : $item_id},
		// 	success : function(data)
		// 	{
		// 		if(data.item_id)
		// 		{
		// 			$parent.find(".select-item").val(data.item_id).change();
		// 			$parent.find('td.item-select-td .input-group').removeClass("hidden");
		// 			$parent.find(".txt-qty").focus();
		// 			toastr.success(data.message);
		// 		}
		// 		else
		// 		{
		// 			$parent.find('.item-textbox').removeClass("hidden");
		// 			$parent.find(".item-textbox").focus();
		// 			toastr.warning(data.message);
		// 		}
		// 	}
		// });

		/* PURE JS */
		$thisSelect = $parent.find(".select-item > option");
		$has = 0;
		$thisSelect.each(function()
		{
			if($(this).attr("item-barcode") == $item_id)
			{
				$parent.find(".select-item").val(this.value).change();
				$parent.find('td.item-select-td .input-group').removeClass("hidden");
				$parent.find(".txt-qty").focus();
				toastr.success("Item Added");
				$has ++;				
			}
		});
		callback($has);
	}
}
