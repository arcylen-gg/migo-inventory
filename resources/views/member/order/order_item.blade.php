  <table class="table table-condensed">
                      
  @foreach($_item['item'] as $key =>$item)
    <tr id="trorder_{{$item['tbl_order_item_id']}}">
      <td width="10%" class="valign-center">
        <img  class="img50x50" src="{{$item['image_path']}}">
      </td>
      <td width="25%" class="valign-center">
        <a href="/member/product/edit/variant/{{$item['variant_product_id']}}?variant_id={{$item['variant_id']}}">{{$item['product_name']}}</a>
        <p class="light-brown">{{$item['variant_name']}}</p>
        <p class="light-brown">SKU&nbsp;:&nbsp;{{$item['variant_sku']}}</p>
      </td>
      <td width="10%" class="text-right">
        <p class="light-brown pull-right padding0 f10" id="discount_amount_{{$item['tbl_order_item_id']}}">{{$item['amount_to_show']}}</p>
        <a href="#" class="discountpopover" id="a_new_amount_{{$item['tbl_order_item_id']}}" rel="popover" data-popover-content="#popdiscount{{$item['tbl_order_item_id']}}">{{$item['less_discount']}}</a>
        
        <div class="hide" id="popdiscount{{$item['tbl_order_item_id']}}">
          <label class="pull-left">Discount this item by</label><Br>
          <div class="form-horizontal">
            <div class="form-group">
              <div class="col-md-10">
                <div class="input-group">
                  <span class="input-group-btn">
                    <button class="btn btn-def-white btn-cat-toggle" data-content="₱" data-id="{{$item['tbl_order_item_id']}}" data-trigger="amount">₱</button>
                  </span>
                  <span class="input-group-btn">
                    <button class="btn btn-def-white btn-cat-toggle" data-content="%" data-id="{{$item['tbl_order_item_id']}}" data-trigger="percent">%</button>
                  </span>
                  <span class="peso-back left-76 span-discount-cat" id="discount-cat-{{$item['tbl_order_item_id']}}">₱</span>
                  <input type="number" step="any" name="" class="form-control text-right" id="number-discount-{{$item['tbl_order_item_id']}}" >
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <label class="pull-left">Reason</label>
              </div>
              <div class="col-md-12">
                <input type="text" name="" class="form-control" id="indi_discount_reason_{{$item['tbl_order_item_id']}}" placeholder="Damage item, loyalty discount">
              </div>
            </div>
            <hr>
            <div class="form-group">
              <div class="col-md-12">
                <button class="btn btn-def-white pull-left btn-close-popover">Close</button>
                <button class="btn btn-primary pull-right indi-discount" id="indi-discount-{{$item['tbl_order_item_id']}}" data-content="{{$item['tbl_order_item_id']}}" data-trigger="{{$item['discount_var']}}">Apply</button>
              </div>
            </div>
          </div>
          
        </div>
      </td>
      <td width="5%" class="valign-center">
        <p class="light-brown">x</p>
      </td>
      <td width="15%" class="valign-center">
        <div class="input-group">
        <span class="input-group-btn">
          <button class="btn btn-def-white btn-plus-inventory" data-content="{{$item['tbl_order_item_id']}}"><i class="fa fa-plus" aria-hidden="true"></i></button>
        </span>
        <input type="text" name="" readonly class="form-control text-center" id="oder_quantity_{{$item['tbl_order_item_id']}}" value="{{$item['quantity']}}">
        <span class="input-group-btn"><button class="btn btn-def-white btn-minus-inventory" data-content="{{$item['tbl_order_item_id']}}"><i class="fa fa-minus" aria-hidden="true"></i></button></span>
        </div>
      </td>
      <td width="10%" class="valign-center text-right">
        <p class="light-brown item-amount-{{$item['tbl_order_item_id']}}">{{$item['total_amount']}}</p>

      </td>
      <td width="5%" class="valign-center"r>
        <a href="#" class="remove-order-item" data-content="{{$item['tbl_order_item_id']}}" title="Remove item"><i class="fa fa-times" aria-hidden="true"></i></a>
        <input type="hidden" name="" class="float-amount float-amount-{{$item['tbl_order_item_id']}}" value="{{$item['total_amount_def']}}">
        <input type="hidden" name="" id="def_amount_{{$item['tbl_order_item_id']}}" value="{{$item['less_discount_def']}}">
        <input type="hidden" name="" id="orig_amount_{{$item['tbl_order_item_id']}}" value="{{$item['item_amount_def']}}">
      </td>
    </tr>
    
  @endforeach
</table>