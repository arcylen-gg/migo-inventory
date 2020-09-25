<div class="popup-buy-a-kit">
    <div class="modal-content cart">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">My Shopping Cart</h4>
        </div>
        <div class="cart-loader-holder">
            <div style="margin: 50px auto;" class="cart-loader loader-16-gray hide"></div>
            <div class="not-loader">
                @if(isset($cart) && $cart)
                {{-- <div class="table-responsive"> --}}
                    <table class="table footable-cart" style="margin: 0;">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th data-breakpoints="xs">Unit Price</th>
                                <th data-breakpoints="xs">Quantity</th>
                                <th data-breakpoints="xs sm">Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart["_item"] as $item)
                            <tr>
                                <td><img src=""></td>
                                <td>{{ $item->item_name }}</td>
                                <td>P {{ number_format($item->item_price) }}</td>
                                <td><input class="item-qty form-control text-center" style="width: 75px; margin: auto;" type="number" name="quantity" min="1" step="1" value="{{ $item->quantity }}" item-id="{{ $item->item_id }}"></td>
                                <td>P {{ number_format($item->subtotal, 2) }}</td>
                                <td><div class="remove-container remove-item-cart" item-id="{{ $item->item_id }}" style="cursor: pointer;"><i class="fa fa-times" aria-hidden="true"></i></div></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                {{-- </div> --}}
                @else
                <br>
                <h3 style="text-align: center; font-weight: 300; color: #1c1c1c;">NO PRODUCT IN CART YET</h3>
                <br>
                @endif
            </div>
        </div>
        <div class="modal-footer row clearfix">
            <div class="col-md-8 clearfix">
                <div class="left-btn-container">
                    <div style="color: #1c1c1c; cursor: pointer;" data-dismiss="modal" aria-label="Close"><i class="fa fa-long-arrow-left" aria-hidden="true">&nbsp;</i>&nbsp;Continue Shopping</div>
                    <button class="btn-checkout" onClick="location.href='/members/checkout'">Checkout</button>
                </div>
            </div>
            <div class="col-md-4 clearfix">
                @if(isset($cart) && $cart)
                    <div class="total">Total: {{ $cart["_total"]->display_grand_total }}</div>
                @else
                    <div class="total">Total: 0.00</div>
                @endif
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/cart_modal.css?version=1">
<script type="text/javascript" src="/assets/front/js/global_cart_modal.js?version=2"></script>