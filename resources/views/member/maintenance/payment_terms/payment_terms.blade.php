
<form class="global-submit form-to-submit-add" action="{{$action}}" method="post">
<style type="text/css">
    .chosen-container
    {
        width: 100% !important;
    }
</style>
<input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Payment Method</h4>
    <input type="hidden" value="{{$payment_method->payment_method_id or '' }}" name="payment_method_id">
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-12">            
                <label>Payment Method Name *</label>
                <input type="text" class="form-control" value="{{$payment_method->payment_name or ''}}" name="payment_method_name" >
            </div>
        </div>
    </div>
</div>
<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Save Payment Method</button>
</div>
</form>


<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<!-- <script type="text/javascript" src="/assets/member/js/truck.js"></script> -->
<!-- <script type="text/javascript" src="/assets/member/js/warehouse.js"></script> -->