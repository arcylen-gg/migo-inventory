<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">Choose Product</h4>
</div>
<form class="global-submit form-to-submit-add" action="{{$choose_item_submit or ''}}" method="post">
<input type="hidden" name="_token" value="{{csrf_token()}}">
	<div class="modal-body clearfix">
		<div class="row">
			<div class="col-md-8">
				<!-- <input type="text" placeholder="Enter Product Keyword" class="form-control" name=""> -->
				<select required class="form-control select-item" name="item_id">
					@include("member.load_ajax_data.load_item_category", ['add_search' => "", '_item' => $_item_to_bundle])
				</select>
			</div>
			<div class="col-md-4">
				<input type="text" placeholder="Enter Quantity" value="1" class="form-control" name="quantity">
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary add-product-btn" type="submit" disabled="disabled">Add Product</button>
	</div>
</form>
<script type="text/javascript" src="/assets/member/js/item/choose_item.js"></script>