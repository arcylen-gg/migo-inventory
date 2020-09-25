<tr data-toggle="modal" data-target="#ShippingModal" class="shipping-click" data-content="{{$ship->shipping_id}}">
    <td>
        <a href="#" data-toggle="modal" data-target="#ShippingModal" class="shipping-click" data-content="{{$ship->shipping_id}}">{{$ship->shipping_name}}</a>
    </td>
    <td>{{$ship->contact}}</td>
    <td class="text-center">{{$ship->currency}}{{number_format($ship->shipping_fee,2)}}/{{$ship->unit.' '.$ship->measurement}}</td>
    
</tr>