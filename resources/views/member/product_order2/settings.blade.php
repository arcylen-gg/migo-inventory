<form method="post" action="/member/ecommerce/product_order2/settings">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal">×</button>
    	<h4 class="modal-title">ORDER SETTINGS</h4>
    </div>
    <div class="modal-body clearfix">
    	<div class="row">
            <div class="clearfix modal-body"> 
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-6">
                            <label for="basic-input">SHIPPING FEE</label>
                            <input value="{{ $shipping_fee }}" type="number" name="shipping_fee" class="form-control" min="0" step="any">
                        </div>
                    </div>
                </div>
            </div>
    	</div>
    </div>
    <div class="modal-footer">
    	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
    	<button class="btn btn-primary btn-custom-primary" type="submit">Submit</button>
    </div>
</form>