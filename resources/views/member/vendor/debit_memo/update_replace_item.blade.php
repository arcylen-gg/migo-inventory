
<form class="global-submit form-to-submit-add" action="/member/vendor/debit_memo/replace_submit" method="post">
<style type="text/css">
    .chosen-container
    {
        width: 100% !important;
    }
</style>
<input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Replace Quantity</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-12">
                <h4>{{$db_item->item_name}}</h4>
                <input type="hidden" class="form-control" name="dbline_id" value="{{$db_item->dbline_id}}">
            </div>
        </div>
        @if($issued_um_name)         
        <div class="form-group">
            <div class="col-md-12"> 
                <label>{{$issued_um_name}} quantity *</label>
                <input type="text" class="form-control" name="issued_qty" value="{{$issued_um_qty}}">
                <input type="hidden" class="form-control" name="issued_um_id" value="{{$issued_um_id}}">
            </div>
        </div>
        @endif

        @if($base_name)         
        <div class="form-group">
            <div class="col-md-12"> 
                <label>{{$base_name}} quantity *</label>
                <input type="text" class="form-control" name="base_qty"  value="{{$base_qty}}">
                <input type="hidden" class="form-control" name="base_um_id" value="{{$base_um_id}}">
            </div>
        </div>
        @endif
    
    </div>
</div>
<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Update Count</button>
</div>
</form>

<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<!-- <script type="text/javascript" src="/assets/member/js/truck.js"></script>
<script type="text/javascript" src="/assets/member/js/warehouse.js"></script> -->
<script type="text/javascript">