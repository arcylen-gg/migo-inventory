<form class="global-submit form-horizontal" role="form" action="/member/pis/agent/position/archived_submit" id="confirm_answer" method="post">
{!! csrf_field() !!}
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Confirm</h4>
</div>
<div class="modal-body add_new_package_modal_body clearfix">
    <div class="col-md-12">
        <h3>Are you sure you want to {{$action}} this position ?</h3>
    </div>
    <div class="col-md-12 text-center">
        <h4>{{$position_info->position_name}}</h4>
    </div>
    <input type="hidden" name="position_id" value="{{$position_id}}">
    <input type="hidden" name="action" value="{{$action}}">
</div>
<div class="modal-footer">
    <div class="col-md-6 col-xs-6"><button type="submit" class="btn btn-custom-blue form-control">Yes</button></div>
    <div class="col-md-6 col-xs-6"><button data-dismiss="modal" class="btn btn-def-white btn-custom-white form-control">No</button></div>
</div>  	
</form>