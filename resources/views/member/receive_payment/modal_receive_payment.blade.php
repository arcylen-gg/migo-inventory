<form class="global-submit form-horizontal" role="form" action="{{$action or ''}}" id="confirm_answer" method="post">
	{!! csrf_field() !!}
	<div class="modal-header">

       <input type="hidden" class="form-control input-sm" name="cm_id" value="{{$cm_data->cm_id or ''}}" />
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Receive Payment</h4>
	</div>
	<div class="modal-body add_new_package_modal_body clearfix">		
        @include("member.receive_payment.load_content_receive_payment");
	</div>
	<div class="modal-footer text-right">
	    <div class="col-md-12">
	    	<button type="submit" class="btn btn-custom-blue">Save</button>
	    	<button data-dismiss="modal" class="btn btn-def-white btn-custom-white">Cancel</button>
	    </div>
	</div>  
</form>
<script type="text/javascript" src="/assets/member/js/receive_payment.js"></script>