<form class="z-index-1000" id="modal_form_large" method="post" action="/member/item/warehouse/transfer_inventory_submit" >
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title">Transfer Inventory</h4>
  </div>
  <div class="modal-body modallarge-body-layout background-white">   
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
    <div class="form-horizontal">
	    <div class="form-group">
	    	<div class="col-md-12">
	    		<label>From: </label>
	    		<select required name="inventory_from" id="transfer_from" class="form-control">
	    			@if($warehouse)
	    				@foreach($warehouse as $ware)
			    			<option value="{{$ware->warehouse_id}}">{{$ware->warehouse_name}}</option>
		    			@endforeach
	    			@endif
	    		</select>
	    	</div>
	    </div>
	    <div class="form-group">
	    	<div class="col-md-12">
	    		<label>To: </label>
	    		<select required name="inventory_to" id="transfer_to" class="form-control transfer_to">
	    		</select>
	    	</div>
	    </div>
	</div>
 </div>
 <div class="modal-footer">
    <button type="button" class="btn btn-custom-white " data-dismiss="modal">Cancel</button>
    <!--<button type="button" class="btn btn-custom-red-white btn-del-modallarge" data-url="" data-value="">Delete</button>-->
    <button class="btn btn-custom-primary btn-save-modallarge pop" type="submit" data-url="">Start Transfer</button>
  </div>
</form>
<script type="text/javascript" src="/assets/member/js/transfer_warehouse.js"></script>
<script type="text/javascript" src="/assets/member/js/warehouse.js"></script>