<input type="hidden" class="quantity-cart-holder" value="{{ get_cart_item_total_quantity($get_cart) }}">
<div class="cart-header clearfix">
  <div>
      <div class="text">
        <div class="title-label">MY CART</div>
        <div class="items">You have {{ get_cart_item_total_quantity($get_cart) }} item/s in your Shopping Bag</div>
        <div class="price"><span class="total">{{ get_cart_item_total($get_cart) }}</span></div>
        <div class="btn-holder">
          <button class="btn" type="button" onClick="location.href='/checkout/login'">CHECK OUT</button>
        </div>
      </div>
  </div>
  <div class="pull-right">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
</div>
<div class="table-holder">
    @if(isset($get_cart['cart']) && $get_cart['cart'])
     <table class="table">
        <thead>
            <tr>
                <th>IMAGE</th>
                <th>PRODUCT NAME</th>
                <th>UNIT PRICE</th>
                <th>QTY</th>
                <th>TOTAL</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach($get_cart['cart'] as $cart)
            <tr class="stripe per-item-container">
                <td><img class="img" src="{{ get_cart_item_image($cart) }}"></td>
                <td class="name">{{ get_cart_item_name($cart) }}</td>
                <td><span  vid="{{ get_cart_item_id($cart) }}" class="raw-price">{{ get_cart_item_price($cart) }}</span></td>
                <td><input vid="{{ get_cart_item_id($cart) }}" class="input-quantity form-control text-center" type="number" name="quantity" min="1" step="1" value="{{ get_cart_item_quantity($cart) }}"></td>
                <td><span  vid="{{ get_cart_item_id($cart) }}" class="sub-total">{{ get_cart_item_subtotal($cart) }}</span></td>
                <td><div   vid="{{ get_cart_item_id($cart) }}" class="remove-container remove-item-from-cart" style="cursor: pointer;"><i class="fa fa-times" aria-hidden="true"></i></div></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @else
        <br>
        <h3 style="text-align: center; font-weight: 300;">NO PRODUCT IN CART YET</h3>
        <br>
    @endif
</div>