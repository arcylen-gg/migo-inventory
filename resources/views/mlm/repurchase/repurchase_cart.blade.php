<div class="loader hide">
    <div class="loader-holder">
        <img src="/assets/mlm/img/ellipsis.gif">
    </div>
</div>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" style="margin-bottom: 0;">
        <thead>
            <tr>
                <th>Name</th>
                <th>Qty</th>
                <th>UPC</th>
                <th>Discount</th>
                <th>Price</th>
                <th></th>
            </tr>
        </thead>
        @if(isset($_cart) && count($_cart) > 0)
            <tbody>
                @foreach($_cart as $cart)
                <tr item-id="{{ $cart["item_id"] }}">
                    <td>{{ $cart["item_info"]->item_name }}</td>
                    <td>{{ $cart["quantity"] }}</td>
                    <td>{{ currency("PHP", $cart["item_price_single"]) }}</td>
                    <td style="cursor: pointer;" data-toggle="tooltip" title="{{ currency("PHP", $cart["item_discount"]) }}"> {{ currency("PHP", $cart["item_discount"]) }}</td>
                    <td>{{ currency("PHP", $cart["item_price_subtotal"]) }}</td>
                    <td><a class="remove-item-cart" item-id="{{ $cart["item_id"] }}" href="javascript:" style="color: red;"><i class="fa fa-times" aria-hidden="true"></i></a></td>
                </tr>
                @endforeach
            </tbody>
            @else
            <tbody class="empty">
                <tr>
                    <td class="text-center" colspan="6">
                        <h2>Your Cart is Empty</h2>
                    </td>
                </tr>
            </tbody>
        @endif
    </table>
</div>