<form class="global-submit" action="/member/item/mulitple_price_modal">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="item_id" value="{{$item_id}}">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Multiple item price</h4> 
        <input type="hidden" id="" value="">
    </div>
    <div class="modal-body">
        <div class="table-responsive">
            <table class="digima-table">
                <thead >
                    <tr>
                        <th class="text-center" width="5%"></th>
                        <th class="text-center" width="30%">Qty</th>
                        <th class="text-center" width="30%">Price / Pc</th>
                        <th class="text-center" width="30%">Total  Price</th>
                        <th width="5%"></th>
                    </tr>
                </thead>
                <tbody class="tbody-item-price">
                    @if(isset($_multiple_price))    
                        @foreach($_multiple_price as $price)
                            <tr class="tr-item-price">
                                <td class="text-center add-tr-price cursor-pointer"><i class="fa fa-plus" aria-hidden="true"></i></td> 
                                <td><input class="text-center form-control input-sm item-price-qty" type="text" name="multiprice_qty[]" value="{{$price->multiprice_qty}}" /></td>
                                <td><input class="text-center form-control input-sm item-price-value" type="text" name="multiprice_price[]" value="{{$price->multiprice_price}}" /></td>
                                <td><input class="text-center form-control input-sm item-price-total" type="text" name="multiprice_total_price[]"/></td>
                                <td class="text-center remove-tr-price cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                            </tr>
                        @endforeach
                    @endif
                    <tr class="tr-item-price">
                        <td class="text-center add-tr-price cursor-pointer"><i class="fa fa-plus" aria-hidden="true"></i></td> 
                        <td><input class="text-center form-control input-sm item-price-qty" type="text" name="multiprice_qty[]"/></td>
                        <td><input class="text-center form-control input-sm item-price-value" type="text" name="multiprice_price[]"/></td>
                        <td><input class="text-center form-control input-sm item-price-total" type="text" name="multiprice_total_price[]"/></td>
                        <td class="text-center remove-tr-price cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer"">
        <button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-custom-primary">Submit</button>
    </div>
</form>

<div class="div-script">
    <table class="div-script-row-price hide">
        <tr class="tr-item-price">
            <td class="text-center add-tr-price cursor-pointer"><i class="fa fa-plus" aria-hidden="true"></i></td> 
            <td><input class="text-center form-control input-sm item-price-qty" type="text" name="item_qty[]" value=""/></td>
            <td><input class="text-center form-control input-sm item-price-value" type="text" name="item_price[]" value="0.00"/></td>
            <td><input class="text-center form-control input-sm item-price-total" type="text" name="item_total_price[]" value="0.00" /></td>
            <td class="text-center remove-tr-price cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>

<script type="text/javascript" src="/assets/member/js/item_multiple_price.js"></script>
