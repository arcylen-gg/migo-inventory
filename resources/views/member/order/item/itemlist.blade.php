<table class="table table-condensed">
                        
  @foreach($_item['item'] as $key =>$item)
    <tr id="trorder_{{$item['tbl_order_item_id']}}">
      <td  class="valign-center">
        <img  class="img50x50" src="{{$item['image_path']}}">
      </td>
      <td  class="valign-center">
        <a href="/member/product/edit/variant/{{$item['variant_product_id']}}?variant_id={{$item['variant_id']}}">{{$item['product_name']}}</a>
        <p class="light-brown">{{$item['variant_name']}}</p>
        <p class="light-brown">SKU&nbsp;:&nbsp;{{$item['variant_sku']}}</p>
      </td>
      <td  class="text-right">
       <p>{{$item['less_discount']}}</p>
       <input type="hidden" name="" id="def_amount_{{$item['tbl_order_item_id']}}" value="{{$item['less_discount_def']}}">
      </td>
      <td  class="valign-center">
        <p class="light-brown">&times;</p>
      </td>
      <td width="15%" class="valign-center">
        @if($item['quantity'] > 0)
        <div class="input-group">
          <span class="input-group-btn"><button class="btn btn-def-white btn-plus-refund" data-content="{{$item['tbl_order_item_id']}}" data-max="{{$item['quantity']}}"><i class="fa fa-plus" aria-hidden="true"></i></button>
          <input type="text" name="" readonly class="form-control refund-quanitity text-center" id="refund_quantity_{{$item['tbl_order_item_id']}}" value="0" data-max="{{$item['quantity']}}" data-content="{{$item['tbl_order_item_id']}}">
          <span class="input-group-btn"><button class="btn btn-def-white btn-minus-refund" data-content="{{$item['tbl_order_item_id']}}" data-max="{{$item['quantity']}}"><i class="fa fa-minus" aria-hidden="true"></i></button>
          </span>
        </div>
        @endif
      </td>
      <td width="10%" class="valign-center text-right">
        <span>₱&nbsp;<span class="light-brown item-amount-{{$item['tbl_order_item_id']}}">0.00</span></span>
      </td>
    
    </tr>
    
  @endforeach
</table>
<input type="hidden" name="" id="subtotal" value="{{$_item['total']}}">
<input type="hidden" name="" id="discount_refund" value="{{$brk->discount}}" data-trigger="{{$brk->discount_var}}">
<input type="hidden" name="" id="tax_refund" value="{{$brk->tax_percentage}}" data-trigger="{{$brk->hasTax}}">
<input type="hidden" name="" id="total_discount_refund" value="0">
<input type="hidden" name="" id="total_tax_refund" value="0">