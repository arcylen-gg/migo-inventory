

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title layout-modallarge-title">Merchant Product Points Settings</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal clearfix">  
	<form class="global-submit" method="post" action="/member/item/approve_request_post/approve">
	{!! csrf_field() !!} 
		<table class="table">
			<tr><td>Item Name: {{$item->item_name}}</td></tr>
			<tr><td>Item Price: {{$item->item_price}}</td></tr>
			<!-- <tr><td><div class="col-md-4">Mark Up(Percentage): </div><div class="col-md-4"><input type="number" class="form-control mark_up" value="{{$request->default_margin_per_product}}"></div></td></tr>
			<tr><td>Mark Up Price: <span class="mark_up_price">{{ ($request->default_margin_per_product/100) * $item->item_price}}</span></td></tr> -->
			<tr>
				<td>Mark up: <a target="_blank" href="/member/merchant/markup?user_id={{$request->user_id}}">Click here to set mark up percentage</a></td>
			</tr>
		
			<tr><td>Merchant: {{$request->user_first_name}} {{$request->user_last_name}}</td></tr>
			<tr><td>Warehouse: {{$request->warehouse_name}}</td></tr>
		</table>
	<button class="btn btn-primary pull-right">Approve</button>
	<input type="hidden" name="item_merchant_request_id" value={{$request->item_merchant_request_id}}>
	<input type="hidden" name="item_id" value="{{$item->item_id}}">
	</form>	
	<div class="col-md-12"></div>
	<div class="col-md-12"><hr></div>
	<div class="append_points_edit">
		<center>Getting Points...</center>
	</div>
</div>
<div class="modal-footer">  
	
</div>


<script>
var price = {{$item->item_price}};
var mark_up = {{$request->default_margin_per_product}};
var mark_up_price = {{ ($request->default_margin_per_product/100) * $item->item_price}};

function fix_mark_up()
{
	mark_up_price = (mark_up/100) * price;
	$('.mark_up_price').html(mark_up_price);
}
$('.mark_up').on('keyup', function(){
  	mark_up = $(this).val();	
  	fix_mark_up();
});
$('.append_points_edit').load('/member/mlm/product?merchant_item={{$item->item_id}} #productpoints-paginate');
function save_product_points(item_id)
{
    $('#formvariant'+ item_id).submit();   
}
function submit_done(data)
{
	console.log(data);
	if(data.response_status == "success_edit_points")
	{
	    toastr.success('Success! Points Editted');
	}
	else if(data.response_status == "success_edit_all_points")
	{
	    toastr.success('All Product Points Updated');
	}
	else if (data.response_status == 'success_approve')
	{
		toastr.success('Item Approved');
		$('.close').click();
		location.reload();
	}

}
</script>