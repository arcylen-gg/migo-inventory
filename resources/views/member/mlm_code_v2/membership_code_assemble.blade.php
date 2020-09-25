<form action="/member/mlm/code2/assemble" method="post" class="global-submit">
    {{ csrf_field() }}
    <div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal">×</button>
    	<h4 class="modal-title"><i class="fa fa-qrcode"></i> ASSEMBLE MEMBERSHIP CODE</h4>
    </div>
    <div class="modal-body clearfix">
    	<div class="row">
            <div class="clearfix modal-body"> 
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-8">
                            <label for="basic-input">Item Kit</label>
    			            <select name="item_id" class="form-control membership-item-kit-select">
    			                @foreach($_item_kit as $kit)
    			                    <option value="{{ $kit->item_id }}">{{ $kit->item_name }}</option>
    			                @endforeach
    			            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="basic-input">Kit Quantity</label>
                            <input name="quantity" id="basic-input" value="1" class="form-control text-right membership-item-kit-quantity" name="item_sku" placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                    	<div class="col-md-12 assembly-table-container-projection">
                        </div>
                    </div>
                </div>
            </div>
    	</div>
    </div>
    <div class="modal-footer">
    	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
    	<button class="btn btn-primary btn-custom-primary assemble-code-submit" type="submit"><i class="fa fa-qrcode"></i> Assemble Code</button>
    </div>
    <script type="text/javascript" src="/assets/member/js/membership_code/membership_code_assemble.js"></script>
</form>