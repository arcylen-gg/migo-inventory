
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Item Price History</h4>
</div>
<div class="modal-body add_new_package_modal_body">
    <div class="row clearfix">
        <div class="form-group">
            <div class="col-md-12">
               {!! $text !!}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer clearfix">
    <div class="col-md-6 col-xs-6 pull-right"><button data-dismiss="modal" class="btn btn-def-white btn-custom-white form-control">Close</button></div>
</div>  

<script type="text/javascript">
    $(".click_delete").unbind("click");
    $(".click_delete").bind("click",function()
    {
    	var history_id= $(this).attr('history-id');      
      	
    	$.ajax({

    		url:"/member/item/delete_item_history",
	        data: { history_id : history_id},
	        type:"get",
	        success: function(data)
	        {
		        $(".codes_container").load("/member/item .codes_container"); 
		        $('#global_modal').modal('toggle');
		        $('.multiple_global_modal').modal('hide');
        	}
    	});
    });
</script>

