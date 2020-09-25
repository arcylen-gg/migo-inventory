<div class="popup-buy-a-kit">
    <div class="modal-content cart">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><img src="/themes/{{ $shop_theme }}/img/cart.png"> My Shopping Cart</h4>
        </div>
        <div class="cart-loader-holder">
            <div style="margin: 50px auto;" class="cart-loader loader-16-gray hide"></div>
            <div class="not-loader">
                @if(isset($cart) && $cart)
                <table class="table" style="margin: 0;">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart["_item"] as $item)
                        <tr>
                            <td><img src=""></td>
                            <td>{{ $item->item_name }}</td>
                            <td>P {{ number_format($item->item_price, 2) }}</td>
                            <td><input class="item-qty form-control text-center" style="width: 75px; margin: auto;" type="number" name="quantity" min="1" step="1" value="{{ $item->quantity }}" item-id="{{ $item->item_id }}"></td>
                            <td>P {{ number_format($item->subtotal, 2) }}</td>
                            <td><div class="remove-container remove-item-cart" item-id="{{ $item->item_id }}" style="cursor: pointer;"><i class="fa fa-times" aria-hidden="true"></i></div></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <br>
                <h3 style="text-align: center; font-weight: 300; color: #1c1c1c;">NO PRODUCT IN CART YET</h3>
                <br>
                @endif
            </div>
        </div>
        <div class="modal-footer row clearfix">
            <div class="col-md-8">
                <div class="left-btn-container">
                    <div style="color: #1c1c1c;"><i class="fa fa-long-arrow-left" aria-hidden="true">&nbsp;</i>&nbsp;Continue Shopping</div>
                    <button class="btn-checkout" onclick="location.href='/members/checkout'">Checkout</button>
                </div>
            </div>
            <div class="col-md-4">
                @if(isset($cart) && $cart)
                    <div class="total">Total: {{ $cart["_total"]->display_grand_total }}</div>
                @else
                    <div class="total">Total: 0.00</div>
                @endif
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/cart_modal.js"></script>