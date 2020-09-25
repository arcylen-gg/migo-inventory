
<form class="global-submit form-to-submit-add" action="/member/item/warehouse/edit_submit" method="post">
<style type="text/css">
    .chosen-container
    {
        width: 100% !important;
    }
</style>
<input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Warehouse Information</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container" >
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-6">            
                <h2>{{$warehouse->warehouse_name}}</h2>
            </div>
        </div>
        <div class="form-group">
       <!--  <div class="col-md-12"><h3>Select Item</h3></div>        
            <div class="col-md-3" >
                <select class="form-control">
                    <option>All Items</option>
                </select>
            </div>
            <div class="col-md-9" > 
                <div class="input-group">
                    <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                    <input type="search" name="" class="form-control" placeholder="Start typing item">
                </div>
            </div>
        </div> -->
        <div class="form-group">
            <div class="col-md-12 text-left">            
                <input type="checkbox" onclick="toggle('.show-container', this)" value="update_password" class="up-check" name="update_password"> <label class="label-type"> View Item Per Bundle/Group</label>
            </div>
        </div>
        <div class="row clearfix draggable-container show-container per-item">
            <div class="table-responsive">
                <div class="load-data" target="warehouse_data">
                    <div id="warehouse_data">                        
                       @include("member.warehouse.load_data_warehouse.load_view_warehouse")
                    </div>
                </div>
            </div>
        </div>  
        <div class="row clearfix draggable-container show-container per-bundle hidden">
            <div class="table-responsive">                   
                @include("member.warehouse.load_data_warehouse.bundle_load_view_warehouse")
            </div>
        </div>    
    </div>
</div>

<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
</div>
</form>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/transfer_warehouse.js"></script>
<script type="text/javascript" src="/assets/member/js/warehouse.js"></script>
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>

<script type="text/javascript">
    function toggle(className, obj) 
    {
        $(".modal-loader").removeClass("hidden");
        var $input = $(obj);
        if($input.prop('checked'))
        {
            $(".modal-loader").addClass("hidden");
            $(".show-container").toggleClass("hidden");   
        }
        else
        { 
            $(".modal-loader").addClass("hidden");
            $(".show-container").toggleClass("hidden");
        }
    }
</script>