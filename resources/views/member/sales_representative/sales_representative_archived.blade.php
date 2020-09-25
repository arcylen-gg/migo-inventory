<form class="global-submit" method="post" action="/member/utilities/sales-rep-archive-submit">
    <input type="hidden" value="{{ csrf_token() }}" name="_token">
    <input type="hidden" value="{{ $sales_rep_id or '' }}" name="sales_rep_id">
    <input type="hidden" value="{{ $action or '' }}" name="action">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title layout-modallarge-title item_title">{{ucwords($action)}} Sales Representative</h4>
    </div>
    <div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
        <div class="panel-body form-horizontal">
        	<h3> Are you sure you want to {{ucwords($action)}} this sales representative ?</h3>
        </div>
    </div>
    <div class="modal-footer" >
        <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary" >Yes</button>
    </div>
</form>