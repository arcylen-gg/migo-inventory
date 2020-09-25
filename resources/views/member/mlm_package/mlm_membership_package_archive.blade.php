<form class="global-submit form-horizontal" role="form" action="/member/mlm/membership/edit/package/save/submit" id="remove_packge" method="post">
{!! csrf_field() !!}
<input type="hidden" name="membership_package_id" value="{{$membership_packages->membership_package_id}}">
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Remove Package</h4>
</div>
<div class="modal-body add_new_package_modal_body clearfix">
    <div class="col-md-12">
        <center><h3>{{$membership_packages->membership_package_name}}</h3></center>
        <hr>
    </div>
    <div class="col-md-12">
        <div class="col-md-6"><button class="btn btn-def-white btn-custom-white col-md-12" data-dismiss="modal">Cancel</button></div>
        <div class="col-md-6"><button class="btn btn-custom-blue col-md-12 " onClick="confirm_remove_package()">Confirm</button></div>
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
</div>	
</form>

<script type="text/javascript">
 function confirm_remove_package() 
 {
     // body...
     $('#remove_packge').submit();
 }   
</script>