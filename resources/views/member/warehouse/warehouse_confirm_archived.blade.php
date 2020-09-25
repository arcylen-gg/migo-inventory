<form class="global-submit form-horizontal" role="form" action="/member/item/warehouse/archive_submit" id="archive_warehouse" method="post">
{!! csrf_field() !!}
<input type="hidden" name="warehouse_id" value="{{$warehouse->warehouse_id}}">
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Archive Warehouse</h4>
</div>
<div class="modal-body add_new_package_modal_body clearfix">
    <div class="col-md-6">
        <h3>Warehouse Name:</h3>
        <h3>Total Item On Hand:</h3>
        <h3>Total Selling Price:</h3>
        <h3>Total Cost Price:</h3>
    </div>
    <div class="col-md-6">
        <center><h3>{{$warehouse->warehouse_name}}</h3></center>
        <center><h3>{{number_format($warehouse->total_qty)}} item(s)</h3></center>
        <center><h3>{{currency("PHP",$warehouse->total_selling_price)}}</h3></center>
        <center><h3>{{currency("PHP",$warehouse->total_cost_price)}}</h3></center>
    </div>
</div>
<div class="modal-footer">
    <div class="col-md-6 col-xs-6"><button type="submit" class="btn btn-custom-blue form-control">Yes</button></div>
    <div class="col-md-6 col-xs-6"><button data-dismiss="modal" class="btn btn-def-white btn-custom-white form-control">Cancel</button></div>
</div>  
</form>

<script type="text/javascript" src="/assets/member/js/transfer_warehouse.js"></script>
<script type="text/javascript" src="/assets/member/js/warehouse.js"></script>
<script type="text/javascript">
 function confirm_archive_warehouse() 
 {
     // body...
     $('#archive_warehouse').submit();
 }   
</script>