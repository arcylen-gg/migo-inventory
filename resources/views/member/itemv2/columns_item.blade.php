<form class="global-submit form-horizontal" role="form" action="/member/item/v2/columns" method="post">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Item Columns</h4>
	</div>
	<div class="modal-body clearfix">
		<input type="hidden" class="count-column" name="" value="{{$count_column}}">
		<label><input type="checkbox" class="check-all-checkbox" name=""> Select All</label>
		@foreach($_column as $key => $column)
		<div class="checkbox" style="text-indent: 10px">
			<input type="hidden" name="column[{{ $key }}][value]" value="{{ $column["value"] }}">
			<input type="hidden" name="column[{{ $key }}][array]" value="{{ $column["array"] }}">
			<input type="hidden" name="column[{{ $key }}][checked]" value="no">
		  	<label><input name="column[{{ $key }}][checked]" type="checkbox" {{ $column["checked"] ? "checked" : "" }} value="yes" class="td-checkbox">{{ $column["value"] }}</label>
		</div>
		@endforeach
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="submit">Submit</button>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function()
{
	/*if($('.td-checkbox').prop("checked") == true)
	{
		alert(123);
		$(".check-all-checkbox").prop('checked', true);
	}*/
	//console.log($('.count-column').val()+ $('.td-checkbox').length);
	$(".check-all-checkbox").change(function()
	{  //"select all" change 
    	$(".td-checkbox").prop('checked', $(this).prop("checked")); //change all ".td-checkbox" checked status
	});
	//".td-checkbox" change 
	$('.td-checkbox').change(function(){ 
	    //uncheck "select all", if one of the listed checkbox item is unchecked
	    if(false == $(this).prop("checked"))
	    { //if this item is unchecked
	        $(".check-all-checkbox").prop('checked', false); //change "select all" checked status to false
	    }
	    //check "select all" if all checkbox items are checked
	    if ($('.td-checkbox:checked').length == $('.td-checkbox').length )
	    {
	        $(".check-all-checkbox").prop('checked', true);
	    }
	    
	});
	if ($('.td-checkbox:checked').length == $('.td-checkbox').length )
    {
        $(".check-all-checkbox").prop('checked', true);
    }
}); 

function columns_submit_done(data)
{
	if (data.response_status == "success") 
	{
		toastr.success(data.message, 'Success');
	}
	else
	{
		toastr.warning(data.message);
	}

	data.element.modal("hide");
	item_list.action_load_table();
}
</script>