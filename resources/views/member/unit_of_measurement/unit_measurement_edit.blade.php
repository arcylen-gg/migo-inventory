
<form class="global-submit form-to-submit-add" action="/member/item/unit_of_measurement/edit_submit" method="post">
<style type="text/css">
    .chosen-container
    {
        width: 100% !important;
    }
    .radio-btn
    {
        display: inline-block;
        margin-left: 15px;
    }
    .input-radio
    {
        vertical-align: middle;   
        margin: 0 !important;        
    }
    .radio-btn span
    {
        vertical-align: middle;   
    }
</style>
<input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Edit U/M</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <div class="panel-body form-horizontal">

        <input type="hidden" name="um_id" value="{{$edit_um->um_id}}">
        <div class="form-group">
            <div class="col-md-6">
                <label>U/M Set Name *</label>
                <input type="text" required class="form-control" value="{{$edit_um->um_name}}" name="um_set_name" id="um_set_name">
            </div>
 
        <input type="hidden" class="other-choices" name="other" id="other">
        <div class="form-group">        
                <label>Base Unit </label>
            <div class="col-md-12">            
                <label>Name </label>
                <input type="text" required class="form-control" value="{{$edit_um->um_base_name}}" name="base_name" id="base_name">
            </div>
            <div class="col-md-12">            
                <label>Abbreviation </label>
                <input type="text" required class="form-control" value="{{$edit_um->um_base_abbrev}}" name="base_abbreviation" id="base_abbreviation">
            </div>
        </div>
        <div class="form-group">
        <div class="col-md-12"><h3>Related Units</h3></div>
        </div>
        <div class="row clearfix draggable-container">
            <div class="table-responsive">
                <div class="col-sm-12">
                    <table class="digima-table">
                        <thead >
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th style="width: 200px;">Abbreviation</th>
                                <!-- <th class="text-center" style="width: 100px;">Main Warehouse QTY</th> -->
                                <th class="text-center" style="width: 200px;">Qty per <span class="abb">{{$edit_um->um_base_abbrev}}</span></th>

                                <th style="width: 20px"></th>
                            </tr>
                        </thead>
                        <tbody class="draggable">
                        @if($_multi)
                            @foreach($_multi as $key => $multi)
                            <input type="hidden" value="{{$multi->multi_id}}" name="multi_id[]">
                            <tr class="tr-draggable tr-draggable-html">
                                <td class="invoice-number-td text-right">{{$key+1}}</td>
                                <td class="">
                                   <input type="text" name="related_name[]" value="{{$multi->multi_name}}" class="form-control input-sm">
                                </td>
                                <td>
                                   <input type="text" class="form-control input-sm" value="{{$multi->multi_abbrev}}" name="related_abb[]">
                                </td>
                                <td>                                    
                                   <input type="text" class="form-control number-input input-sm" value="{{$multi->unit_qty}}" name="related_qty[]">
                                </td>
                                <td class="text-center cursor-pointer"></td>
                            </tr>
                            @endforeach
                        @endif
                            <tr class="tr-draggable tr-draggable-html">
                                <td class="invoice-number-td text-right">2</td>
                                <td class="">
                                   <input type="text" name="related_name[]" class="form-control input-sm">
                                </td>
                                <td>
                                   <input type="text" class="form-control input-sm" name="related_abb[]">
                                </td>
                                <td>                                    
                                   <input type="text" class="form-control number-input input-sm" name="related_qty[]">
                                </td>
                                <td class="text-center cursor-pointer"><i class="fa fa-trash-o remove-tr" aria-hidden="true"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>    
    </div>
</div>
<div class="div-script">
    <table class="div-item-row-script hide">
         <tr class="tr-draggable tr-draggable-html">
            <td class="invoice-number-td text-right">3</td>
            <td class="">
               <input type="text" name="related_name[]" class="form-control input-sm">
            </td>
            <td>
               <input type="text" class="form-control input-sm" name="related_abb[]">
            </td>
            <td>
               <input type="text" class="form-control number-input input-sm" name="related_qty[]">
            </td>
            <td class="text-center cursor-pointer"><i class="fa fa-trash-o remove-tr" aria-hidden="true"></i></td>
        </tr>
    </div>
</div>
<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Save U/M</button>
</div>
</form>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<!-- <script type="text/javascript" src="/assets/member/js/draggable_row.js"></script> -->
<script type="text/javascript" src="/assets/member/js/transfer_warehouse.js"></script>
<script type="text/javascript" src="/assets/member/js/warehouse.js"></script>
<script type="text/javascript" src="/assets/member/js/unit_measurement.js"></script>