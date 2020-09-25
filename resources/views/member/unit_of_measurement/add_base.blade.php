
<form class="global-submit form-to-submit-add" action="/member/item/um/add_base_submit" method="post">

<input type="hidden" name="_token" value="{{csrf_token()}}">
<input type="hidden" name="um_id" value="{{$base->multi_um_id}}">
<input type="hidden" name="um_id_2" value="{{$um_id}}">
<input type="hidden" name="sub_multi_id" value="{{$sub->multi_id}}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Add Quantity</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <div class="panel-body form-horizontal">
    	<div class="form-group">
    		<div class="col-md-6">
                <label>{{$sub->multi_name." (".$sub->multi_abbrev.")"}}</label>
    			<input type="text" class="form-control input-sm" name="base_qty" disabled value="{{$base->unit_qty}}">
    		</div>
    		<div class="col-md-6">
                <label>{{$base->multi_name." (".$base->multi_abbrev.")"}}</label>
    			<input type="text" class="form-control input-sm" name="sub_qty" value="{{$sub->unit_qty}}">
    		</div>    		
    	</div>
    </div>
</div>
<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Save Quantity</button>
</div>
</form>