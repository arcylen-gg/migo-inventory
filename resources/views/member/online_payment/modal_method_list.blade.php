<form class="global-submit" role="form" action="/member/maintenance/online_payment/modal-method-list" method="POST" >
    <input type="hidden" name="_token" value="{{csrf_token()}}" >
    <input type="hidden" name="method_id" value="{{$method->method_id or ''}}" >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title layout-modallarge-title item_title">Payment Method List</h4>
    </div>

    <div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
        <div class="panel-body form-horizontal">
            <div class="row clearfix">
                <div class="col-md-12">
                    <!-- START CONTENT -->
                    <div class="form-group">
                        <div class="col-md-12">
                           <label>Method Name</label>
                           <input class="form-control" type="text" name="method_name" value="{{$method->method_name or ''}}" required>          
                        </div>
                    </div>
                    <!-- END CONTENT -->
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer" >
        <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
        <div class="col-md-4 pull-right">
            <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Save</button>
        </div>
        <!-- <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Generate</button> -->
    </div>
</form>