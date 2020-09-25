<form class="global-submit" action="/member/ecommerce/product/change-option" method="post">
	<input type="hidden" class="token" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="product_id" value="{{ $product_id }}">
    <input type="hidden" class="action_type" name="action_type" value="update">
    <input type="hidden" class="option_value_selected" name="option_value" value="">
	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal">×</button>
    	<h4 class="modal-title">Edit Options</h4>
    </div>
    <div class="modal-body clearfix">
        @foreach($_option as $key=>$option)
        	<div class="row clearfix option-container form-group" id="$key">
                <div class="col-md-5">
                    <input class="form-control option-name" type="text" name="option_name[]" value="{{$option->option_name}}" id="1" required>
                    <input class="hidden option-name-id" type="text" name="option_name_id[]" value="{{$option->option_name_id}}">
                </div>
                <div class="col-md-7 option-value-container">
                    @foreach($option->variant_values as $value)
                        <div type="text" class="btn btn-primary remove-option-value" >
        		            <text class="option-value" value="{{$value}}" option-name="{{$option->option_name}}">{{ $value }}</text>
        		            &times;
        		        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    <div class="modal-footer">
    	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
    	<button class="btn btn-primary btn-save" type="submit">Save</button>
        <button class="btn btn-primary btn-delete hidden" type="submit">Delete</button>
    </div>
</form>