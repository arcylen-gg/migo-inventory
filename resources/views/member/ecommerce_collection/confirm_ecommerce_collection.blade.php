<form class="global-submit form-horizontal" role="form" action="/member/ecommerce/product/collection/archived_submit" id="confirm_answer" method="post">
{!! csrf_field() !!}
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Confirm</h4>
</div>
<div class="modal-body add_new_package_modal_body clearfix">
    <div class="col-md-12">
        <h3>Are you sure you want to {{$action}} this Collection ?</h3>
    </div>
    <div class="col-md-12 text-center">
        <h4>{{$collection->collection_name}}</h4>
        <h4>{{$collection->collection_description}}</h4>
    </div>
    <input type="hidden" name="collection_id" value="{{$collection->collection_id}}">
    <input type="hidden" name="action" value="{{$action}}">
</div>
<div class="modal-footer">
    <div class="col-md-6 col-xs-12"><button type="submit" class="btn btn-custom-blue form-control">Yes</button></div>
    <div class="col-md-6 col-xs-12"><button data-dismiss="modal" class="btn btn-def-white btn-custom-white form-control">No</button></div>
</div>	
</form>