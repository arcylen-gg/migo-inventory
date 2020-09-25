
<form class="global-submit form-to-submit-add" action="/member/pis/truck_list/edit_submit" method="post">
<style type="text/css">
    .chosen-container
    {
        width: 100% !important;
    }
</style>
<input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Edit Truck</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <div class="panel-body form-horizontal">
    <input type="hidden" name="truck_id" value="{{$edit_truck->truck_id}}">
        <div class="form-group">
            <div class="col-md-12">            
                <label>Truck Plate Number *</label>
                <input type="text" class="form-control" value="{{$edit_truck->plate_number}}" name="truck_plate_number">
            </div>
        </div>         
        <div class="form-group">
            <div class="col-md-12">            
                <label>Warehouse</label>
                <select class="form-control" name="truck_warehouse">
                    <option value="0">No Warehouse</option>
                    @if($_warehouse)
                        @foreach($_warehouse as $warehouse)
                            <option value="{{$warehouse->warehouse_id}}" {{$edit_truck->warehouse_id == $warehouse->warehouse_id ? 'selected' : ''}}>{{$warehouse->warehouse_name}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">            
                <label>Truck Model</label>
                <input type="text" class="form-control" value="{{$edit_truck->truck_model}}" name="truck_model" id="warehouse_name">
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">            
                <label>Kilogram </label>
                <div class="input-group">
                <input type="text" class="form-control number-input" value="{{$edit_truck->truck_kilogram}}" name="truck_kilogram">
                  <span class="input-group-addon" style="background-color: #e6e6e6" id="basic-addon2">kg</span>
                </div>
            </div>
        </div> 
    </div>
</div>
<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Save Truck</button>
</div>
</form>

<script type="text/javascript">
    $("select").globalDropList(
    { 
      hasPopup                : "false",
      width                   : "100%",
      placeholder             : "Search....",
      no_result_message       : "No result found!"
    })
</script>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/truck.js"></script>
<!-- <script type="text/javascript" src="/assets/member/js/warehouse.js"></script> -->