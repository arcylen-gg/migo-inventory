
<form class="global-submit form-to-submit-add" action="/member/vendor/update-tagging-item" method="post">
<style type="text/css">
    .chosen-container
    {
        width: 100% !important;
    }
</style>
<input type="hidden" name="_token" value="{{csrf_token()}}">
<input type="hidden" name="vendor_id" value="{{$vendor->vendor_id}}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Tag & Quotation per Item</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-12">
                <h3>{{$vendor->vendor_company}}</h3>
                <small>{{strtoupper($vendor->vendor_first_name." ".$vendor->vendor_middle_name." ".$vendor->vendor_last_name)}}</small>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12"><h3>Select Item</h3></div>
            </div>
        <div class="row clearfix draggable-container">
            <div class="table-responsive">
                <div class="col-sm-12">
                    <table class="digima-table">
                        <thead >
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Item Name</th>
                                <th style="width: 300px;">Item Description</th>
                                <th style="width: 100px;">SKU</th>
                                <th style="width: 100px;">Quotation</th>
                                <th style="width: 20px"></th>
                            </tr>
                        </thead>
                        <tbody class="draggable tbody-item">
                            @if($vendor_item != null)
                                @foreach($vendor_item as $item)
                                    <tr class="tr-draggable">
                                        <td class="invoice-number-td text-right">1</td>
                                        <td class="">
                                            <select class="form-control input-sm droplist-item select-item" name="item_id[]">
                                                @include('member.load_ajax_data.load_item_category', ["add_search" => "", "item_id" => $item->tag_item_id]);
                                            </select>
                                        </td>
                                        <td><span class="txt-desc">{{$item->item_sales_information}}</span></td>
                                        <td><label class="txt-sku">{{$item->item_sku}}</label></td>
                                        <td><input type="text" class="form-control text-right txt-rate" name="item_qt[]" value="{{$item->tag_item_quotation}}"></td>
                                        <td class="text-center cursor-pointer remove-tr"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                    </tr>
                                @endforeach
                            @endif
                            <tr class="tr-draggable">
                                <td class="invoice-number-td text-right">1</td>
                                <td class="">
                                    <select class="form-control input-sm droplist-item select-item" name="item_id[]">
                                        @include('member.load_ajax_data.load_item_category', ["add_search" => ""]);
                                    </select>
                                </td>
                                <td><span class="txt-desc"></span></td>
                                <td><label class="txt-sku"></label></td>
                                <td><input type="text" class="form-control text-right txt-rate" name="item_qt[]"></td>
                                <td class="text-center cursor-pointer remove-tr"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                            </tr>
                            <tr class="tr-draggable">
                                <td class="invoice-number-td text-right">2</td> 
                                <td>
                                    <select class="form-control droplist-item select-item input-sm" name="item_id[]">
                                      @include('member.load_ajax_data.load_item_category', ["add_search" => ""]);
                                    </select>
                                </td>
                                <td><span class="txt-desc"></span></td>
                                <td><label class="txt-sku"></label></td>
                                <td><input type="text" class="form-control text-right txt-rate" name="item_qt[]"></td>
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
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Save Tagging</button>
</div>
</form>
<div class="div-script">
    <table class="div-item-row-script hide">
         <tr class="tr-draggable">
            <td class="invoice-number-td text-right">1</td>
            <td>
                <select class="form-control input-sm select-item" name="item_id[]">
                     @include('member.load_ajax_data.load_item_category', ["add_search" => ""]);
                </select>
            </td>
            <td><span class="txt-desc"></span></td>
            <td ><label class="txt-sku"></label></td>
            <td><input type="text" class="form-control text-right txt-rate" name="item_qt[]"></td>
            <td class="text-center cursor-pointer remove-tr"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
    </div>
</div>
<script type="text/javascript" src="/assets/member/js/vendor_tag.js"></script>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>