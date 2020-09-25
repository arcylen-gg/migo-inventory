// $.ajax({

// 	url : '/member/pis_counter',
// 	type : 'get',
// 	dataType : 'json',
// 	success : function(data)
// 	{
// 		if(data.lof_ctr != 0)
// 		{
// 			$(".lof-count").html(data.lof_ctr);
// 			$(".lof-count").removeClass("hidden");			
// 		}

// 		if(data.sir_ctr != 0)
// 		{
// 			$(".sir-count").html(data.sir_ctr);
// 			$(".sir-count").removeClass("hidden");	
// 		}

// 		if(data.ilr_ctr != 0)
// 		{
// 			$(".ilr-count").html(data.ilr_ctr);
// 			$(".ilr-count").removeClass("hidden");	
// 		}

// 		if(data.col_ctr != 0)
// 		{
// 			$(".col-count").html(data.col_ctr);
// 			$(".col-count").removeClass("hidden");	
// 		}

// 		if(data.inv_ctr != 0)
// 		{
// 			$(".inv-count").html(data.inv_ctr);
// 			$(".inv-count").removeClass("hidden");	
// 		}

// 		if(data.po_ctr != 0)
// 		{
// 			$(".po-count").html(data.po_ctr);
// 			$(".po-count").removeClass("hidden");	
// 		}

// 		if(data.bill_ctr != 0)
// 		{
// 			$(".bill-count").html(data.bill_ctr);
// 			$(".bill-count").removeClass("hidden");	
// 		}
// 	}
// });
$.ajax({
	url : '/member/transaction/notification/check-notification-message',
	type : 'get',
	dataType : 'json',
	success : function(data)
	{
		if((data.print).length > 0)
		{
			for (var i = 0; i <= (data.print).length; i++)
			{
				if((data.print)[i] != null)
				{
					window.open('/member/transaction/'+(data.print)[i], '_blank');
				}
			}			
		}
	}
});
$show_reorder = $(".show-reorder-class").val();
if($show_reorder)
{
	if($show_reorder == 'true')
	{
		action_load_link_to_modal("/member/transaction/notification/reorder-notification","md"); 
	}
}

