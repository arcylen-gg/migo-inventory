<table class="table table-boreded table-striped">
    <thead>
        <tr>
            <th>Code ID</th>
            <th>Activation Code</th>
            <th>Package Name</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        @if($_voucher_product)
            @foreach ($_voucher_product as $voucher_product)
                <tr>
                    <td>{{$voucher_product->membership_code_id}}</td>
                    <td>{{$voucher_product->membership_activation_code}}</td>
                    <td>{{$voucher_product->membership_package_name}}</td>
                    <td>{{$voucher_product->membership_code_price}}</td>
                </tr>
            @endforeach

            <tr><td class="text-right" colspan="7"><strong>Item total Price :{{$subtotal}}</strong></td></tr>
            <tr><td class="text-right" colspan="7"><strong>Discount : {{$discount}}</strong></td></tr>
             <!-- <tr><td class="text-right" colspan="7"><strong>Additional : </strong></td></tr> -->
            <tr><td class="text-right" colspan="7"><strong>Grand Total :{{$total}} </strong></td></tr>
        @endif
    </tbody>
</table>