<form class="global-submit form-horizontal" role="form" action="/member/mlm/code/block_submit" id="block_code" method="post">
{!! csrf_field() !!}
<input type="hidden" name="membership_code_id" value="{{$code->membership_code_id}}">
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Block Code</h4>
</div>
<div class="modal-body add_new_package_modal_body clearfix">
    <div class="col-md-12">
        <center><h3>Activation Code: {{$code->membership_activation_code}}</h3></center>
        </br>
        <center><h3>Owner: {{$code->first_name}} {{$code->middle_name}} {{$code->last_name}} </h3></center>
        <hr>

    </div>
    <div class="col-md-12">
        <div class="col-md-6"><button class="btn btn-def-white btn-custom-white col-md-12" data-dismiss="modal">Cancel</button></div>
        <div class="col-md-6"><button class="btn btn-custom-blue col-md-12 " onClick="confirm_block_code()">Confirm</button></div>
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
</div>	
</form>

<script type="text/javascript">
 function confirm_block_code() 
 {
     // body...
     $('#block_code').submit();
 }   
</script>