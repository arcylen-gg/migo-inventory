
<form class="global-submit form-to-submit-add" action="/member/item/unit_of_measurement/add_submit" method="post">
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
    <h4 class="modal-title layout-modallarge-title item_title">Add U/M</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <div class="panel-body form-horizontal">
    <div class="form-group">
        <div class="col-md-12 text-center">            
            <strong>Select a Unit of Measure Type</strong>
        </div>
        <div class="col-md-12 text-center">
            @if($_um_type)
                @foreach($_um_type as $type)
                    <label class="radio-inline type{{$type->um_type_id}}" data-content="{{$type->um_type_name}}" onclick="chooseType({{$type->um_type_id}})"><input type="radio" value="selected" name="um_type_type">{{$type->um_type_name}}</label>
                @endforeach
                    <label class="radio-inline" onclick="chooseOther()"><input type="radio" value="selected_other" name="um_type_type"> Other</label>
            @endif
        </div>
    </div>
    <div class="form-group child-div">
        <div class="col-md-12 text-center loading-sub hidden">
            <div style="color: green"><i class="fa fa-spinner fa-spin"></i> Loading Sub Types </div>
        </div>
        <div class="col-md-12 text-center base-lbl hidden">      
            <strong>Select a Base Unit of Measure</strong>
        </div>
        <div class="col-md-12 child-types text-center">
           
        </div>
    </div>
        <div class="form-group">
            <div class="col-md-6">
                <label>U/M Set Name *</label>
                <input type="text" required class="form-control" name="um_set_name" id="um_set_name">
            </div>
        </div>
        <input type="hidden" class="type_id" name="um_type">
        <input type="hidden" class="other-choices" name="other" id="other">
        <div class="form-group">        
                <label>Base Unit </label>
            <div class="col-md-12">            
                <label>Name </label>
                <input type="text" required class="form-control" readonly="true" name="base_name" id="base_name">
            </div>
            <div class="col-md-12">            
                <label>Abbreviation </label>
                <input type="text" required class="form-control" readonly="true" name="base_abbreviation" id="base_abbreviation">
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
                                <th class="text-center" style="width: 200px;">Qty per <span class="abb"></span></th>
                                <th style="width: 20px"></th>
                            </tr>
                        </thead>
                        <tbody class="draggable tbody-item">
                            <tr class="tr-draggable">
                                <td class="invoice-number-td text-right">1</td>
                                <td class="">
                                   <input type="text" name="related_name[]" class="form-control input-sm">
                                </td>
                                <td>
                                   <input type="text" class="form-control input-sm" name="related_abb[]">
                                </td>
                                <td>                                    
                                   <input type="text" class="form-control number-input input-sm" name="related_qty[]">
                                </td>
                                <td class="text-center cursor-pointer remove-tr"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                            </tr>
                            <tr class="tr-draggable">
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
                                <td class="text-center cursor-pointer remove-tr"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>    
    </div>
</div>
<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Save U/M</button>
</div>
</form>
<div class="div-script">
    <table class="div-item-row-script hide">
         <tr class="tr-draggable">
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
            <td class="text-center cursor-pointer remove-tr"><i class="fa fa-trash-o " aria-hidden="true"></i></td>
        </tr>
    </table>
</div>
<script type="text/javascript" src="/assets/member/js/unit_measurement.js"></script>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>