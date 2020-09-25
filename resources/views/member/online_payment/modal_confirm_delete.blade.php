<form class="global-submit form-horizontal" role="form" action="/member/maintenance/online_payment/delete-method-list?method_id={{$method->method_id}}" id="archive_item" method="post">
    {!! csrf_field() !!}
    <!-- <input type="hidden" name="action" value=""> -->
    <div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal">&times;</button>
    	<h4 class="modal-title">Do you really want to delete this payment method?</h4>
    </div>
    <div class="modal-body add_new_package_modal_body clearfix">
        <div class="col-md-12">
            <center><h3>Method Name: {{$method->method_name}}</h3></center>
        </div>
        <!--<div class="col-md-12">
            <div class="col-md-6"><button class="btn btn-def-white btn-custom-white col-md-12" data-dismiss="modal">Cancel</button></div>
            <div class="col-md-6"><button class="btn btn-custom-blue col-md-12 ">Confirm</button></div>
        </div> -->
    </div>
    <div class="modal-footer">
            <div class="col-md-6"><button class="btn btn-def-white btn-custom-white col-md-12" data-dismiss="modal">Cancel</button></div>
            <div class="col-md-6"><button class="btn btn-custom-blue col-md-12 ">Confirm</button></div>
    </div>	
</form>