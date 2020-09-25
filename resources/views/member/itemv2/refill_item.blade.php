<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">Refill Item</h4>
</div>
<form class="global-submit form-to-submit-add" action="{{$refill_submit or ''}}" method="post">
<input type="hidden" name="_token" value="{{csrf_token()}}">
<input type="hidden" name="item_id" value="{{$item->item_id or ''}}">
	<div class="modal-body clearfix">
		<div class="form-group">
			<div class="row">
				<div class="col-md-12">
				<h3>{{$item->item_sku}}</h3>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-md-12">
					<input type="text" placeholder="Enter Quantity" class="form-control" name="quantity">				
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-md-12">
					<textarea class="form-control" name="remarks" placeholder="Remarks"></textarea>				
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary add-product-btn" type="submit" >Refill</button>
	</div>
</form>