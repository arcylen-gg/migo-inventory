
<form class="global-submit form-to-submit-add" action="/member/pis/agent/position/add_submit" method="post">
<style type="text/css">
    .chosen-container
    {
        width: 100% !important;
    }
</style>
<input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Add Position</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-12">            
                <label>Position Name *</label>
                <input type="text" class="form-control" name="position_name" id="warehouse_name">
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">          
                <label>Position Code *</label>
                <input type="text" class="form-control" name="position_code" id="warehouse_name">
            </div>
        </div> 
        @if(isset($commission))
            @if($commission == 1)
            <div class="form-group">
                <div class="col-md-12">          
                    <label>Commission Percent *</label>
                    <input type="text" class="form-control" name="commission_percent" id="warehouse_name">
                </div>
            </div>
            @endif 
        @endif
    </div>
</div>
<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Save Position</button>
</div>
</form>

<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<!-- <script type="text/javascript" src="/assets/member/js/position.js"></script> -->
<!-- <script type="text/javascript" src="/assets/member/js/warehouse.js"></script> -->