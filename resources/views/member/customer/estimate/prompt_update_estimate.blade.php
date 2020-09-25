<form class="global-submit form-horizontal" role="form" action="/member/customer/update_status_submit_continue" id="confirm_answer" method="post">
{!! csrf_field() !!}
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Update</h4>
</div>
<div class="modal-body add_new_package_modal_body clearfix">
    <div class="col-md-12">
        <h3>This estimate # {{sprintf("%'.05d\n", $estimate_id)}} has already linked to an invoice.</h3>
        <h3>Do you want to continue ?</h3>
    </div>
    <input type="hidden" name="estimate_id" value="{{$estimate_id}}">
    <input type="hidden" name="action_status" value="{{$action}}">
</div>
<div class="modal-footer">
    <div class="col-md-6 col-xs-6"><button type="submit" class="btn btn-custom-blue form-control">Yes</button></div>
    <div class="col-md-6 col-xs-6"><button data-dismiss="modal" class="btn btn-def-white btn-custom-white form-control">Cancel</button></div>
</div>	
</form>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>