<form class="global-submit form-horizontal" role="form" action="/tablet/pis/sir/lof_action_submit" id="confirm_answer" method="post">
{!! csrf_field() !!}
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Confirm</h4>
</div>
<div class="modal-body add_new_package_modal_body clearfix">
    <div class="col-md-12">
        <h3>Are you sure you want to {{strtoupper($action)}} this Load Out Form No : {{sprintf("%'.05d\n", $sir_id)}} ?</h3>
    </div>
    @if($action == "reject")
    <div class="col-md-12 text-center">
        <textarea class="form-control textarea-expand" placeholder="State your reason here." required name="reason_txt"></textarea>
    </div>
    @endif
    <input type="hidden" name="sir_id" value="{{$sir_id}}">
    <input type="hidden" name="action" value="{{$action}}">
</div>
<div class="modal-footer">
    <div class="col-md-6 col-xs-6"><button type="submit" class="btn btn-custom-blue form-control">Yes</button></div>
    <div class="col-md-6 col-xs-6"><button data-dismiss="modal" class="btn btn-def-white btn-custom-white form-control">No</button></div>
</div>	
</form>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript">   
function submit_done(data)
{
    if(data.status == "success")
    {
        toastr.success("Success");
        location.href = "/tablet/dashboard";
    }
    else if(data.status == "success-close")
    {
        toastr.success("Success");
        location.href = "/tablet/dashboard";
    }
    else if(data.status == "error")
    {
        toastr.warning(data.status_message);
        $(data.target).html(data.view);
    }
}
</script>