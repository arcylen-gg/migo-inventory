<form class="global-submit form-horizontal" role="form" action="{{$action}}" id="archive_warehouse" method="post">
{!! csrf_field() !!}
<input type="hidden" name="warehouse_id" value="{{$id}}">
<input type="hidden" name="val" value="{{$val}}">
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">{{ucfirst($type)}} Warehouse</h4>
</div>
<div class="modal-body add_new_package_modal_body clearfix">
    <div class="form-group">
        <div class="col-md-12">
            @if($remaining_qty > 0)
                <h3>There's still <strong>{{number_format($remaining_qty)}}</strong> quantity remaining in this warehouse. Kindly empty before archiving.</h3>
            @else
                <h3>Are you sure you wan't to {{ucfirst($type)}} this warehouse ?</h3><br>
            @endif
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="col-md-12">
        @if(!$remaining_qty)
        <button type="submit" class="btn btn-custom-blue">{{ucfirst($type)}}</button>
        @endif
        <button data-dismiss="modal" class="btn btn-def-white btn-custom-white">Cancel</button>
    </div>
</div>  
</form>